<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Repository\ChambreRepository;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chambres")
 */
class ChambresController extends AbstractController
{
    /**
     * @Route("/", name="app_chambres_index", methods={"GET"})
     */
    public function index(ChambreRepository $chambreRepository): Response
    {
        return $this->render('chambres/index.html.twig', [
            'chambres' => $chambreRepository->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/test/{id}/{dateA}/{dateD}/{type}",name="test")
     */
    public function test(int $id, Request $request, ChambreRepository $chambreRepository, HotelRepository $hotelRepository, \DateTime $dateA, \DateTime $dateD, string $type)
    {
        if (strcasecmp($type, "Null") == 0) {
            $chambres = $chambreRepository->chambresDispoParHotelDate($hotelRepository->find($id)->getId(), $dateA, $dateD);
        } else {
            $chambres = $chambreRepository->chambresDispoParHotelTypeChambre($hotelRepository->find($id)->getId(), $dateA, $dateD, $type);
        }
        return $this->render('chambres/index.html.twig',
            ['chambres' => $chambres,
                'dateA' => $dateA,
                'dateD' => $dateD,
                'da' => $dateA->format('d-m-Y'),
                'dd' => $dateD->format('d-m-Y'),
                'type' => $type]);
    }

    /**
     * @Route("/admin", name="app_chambres_index_admin", methods={"GET"})
     */
    public function indexAdmin(ChambreRepository $chambreRepository): Response
    {
        return $this->render('chambres/indexAdmin.html.twig', [
            'chambres' => $chambreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_chambres_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ChambreRepository $chambreRepository): Response
    {
        $chambre = new Chambre();
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chambreRepository->add($chambre);
            return $this->redirectToRoute('app_chambres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chambres/new.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/addP/{id}/{da}/{dd}/{type}",name="addP")
     */
    public function ajouterChambrePanier(Chambre $chambre, SessionInterface $session, Request $request, string $da, string $dd, string $type)
    {
        $id = $chambre->getId();
        $IDhotel = $chambre->getIDHotel()->getId();
        $panier = $session->get("panier", []);
        $panier[$id] = 1;
        $session->set("panier", $panier);
//        return $this->redirectToRoute('app_chambres_index');
//        return $this->redirect("http://127.0.0.1:8000/hotel/" . $IDhotel . "/" . ($dateA->format('d-m-Y') . ("/" . $dateD->format('d-m-Y'))));
        return $this->redirect("http://127.0.0.1:8000/chambres/test/" . $IDhotel . "/" . $da . ("/" . $dd) . ("/" . $type));
    }

    /**
     * @Route("/deleteP/{da}/{dd}/{id}",name="deleteP")
     */
    public function supprimerChambrePanier(Chambre $chambre, SessionInterface $session, String $da,String $dd)
    {
        $id = $chambre->getId();
        $panier = $session->get("panier", []);
        unset($panier[$id]);
        $session->set("panier", $panier);
        return $this->redirectToRoute('panier',['da'=>$da,'dd'=>$dd]);
    }

    /**
     * @Route("/deleteAll",name="deleteAll")
     */
    public function viderPanier(SessionInterface $session)
    {
        $session->remove("panier");
        return $this->redirectToRoute('panierV');
    }

    public function nbrPersonnes(Chambre $chambre){
        $nbrPersonnes = 0;
        if(strcasecmp($chambre->getType(),"Suite")==0){
            $nbrPersonnes=5;
        }elseif (strcasecmp($chambre->getType(),"Double")==0){
            $nbrPersonnes=2;
        }elseif (strcasecmp($chambre->getType(),"Triple")==0){
            $nbrPersonnes=3;
        }elseif (strcasecmp($chambre->getType(),"Quadruple")==0){
            $nbrPersonnes=4;
        }
        return $nbrPersonnes;
    }

    /**
     * @Route("/panier/{da}/{dd}",name="panier")
     */
    public function panier(SessionInterface $session, ChambreRepository $chambreRepository, Request $request, \DateTime $da,\DateTime $dd)
    {
        $form = $this->createFormBuilder()
            ->add('Formule', ChoiceType::class, [
                'choices' => [
                    'Demi-Pension' => 'Demi-Pension',
                    'Pension Complete' => 'Pension Complete',
                    'All Inclusive' => 'All Inclusive',
                ], 'required' => true])
            ->add('Payer',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        $amount = 0;
        $nbrPersonnes = 0;
        $panier = $session->get("panier", []);
        $dataPanier = [];
        foreach ($panier as $id => $quantite) {
            $chambre = $chambreRepository->find($id);
            $amount += $chambre->getPrixnuite();
            $nbrPersonnes+=$this->nbrPersonnes($chambre);
            $dataPanier[] = [
                "chambre" => $chambre,
                "quantite" => $quantite
            ];
        }
        if ($form->isSubmitted()) {
            $formule = $form->get('Formule')->getData();
            $nbrJours = intval(date_diff($da,$dd)->format('%d'));
            if(strcasecmp($formule,"Pension Complete")==0){
                $amount+=15*count($dataPanier);
            }
            elseif(strcasecmp($formule,"All Inclusive")==0){
                $amount+=30*count($dataPanier);
            }
            $somme  = $amount*$nbrJours;
            $sommeUSD = $somme*0.33;
            return $this->render('reservation/payement.html.twig',['amount'=>$somme,'dataPanier' => $dataPanier,'formule'=>$formule,'dateA'=>$da->format('d-m-Y'),'dateD'=>$dd->format('d-m-Y'),'sommeUSD'=>$sommeUSD,'nbr'=>$nbrPersonnes]);
        }
        return $this->render('reservation/panier.html.twig', [
            'dataPanier' => $dataPanier, 'amount' => $amount, 'form' => $form->createView(),'da'=>$da->format('d-m-Y'),'dd'=>$dd->format('d-m-Y')]);
    }
    /**
     * @Route("/panier",name="panierV")
     */
    public function panierVide(SessionInterface $session, ChambreRepository $chambreRepository, Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('Formule', ChoiceType::class, [
                'choices' => [
                    'Demi-Pension' => 'Demi-Pension',
                    'Pension Complete' => 'Pension Complete',
                    'All Inclusive' => 'All Inclusive',
                ], 'required' => true])
            ->add('Payer',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        $amount = 0;
        $panier = $session->get("panier", []);
        $dataPanier = [];
        foreach ($panier as $id => $quantite) {
            $chambre = $chambreRepository->find($id);
            $amount += $chambre->getPrixnuite();
            $dataPanier[] = [
                "chambre" => $chambre,
                "quantite" => $quantite
            ];
        }
        return $this->render('reservation/panierv.html.twig', [
            'dataPanier' => $dataPanier, 'amount' => $amount, 'form' => $form->createView()]);
    }

    /**
     * @Route("/dispo", name="app_chambres_dispo")
     */
    public function ListChDispo(ChambreRepository $chambreRepository, HotelRepository $hotelRepository): Response
    {
        $hotels = $hotelRepository->findAll();
        $chambresParHotel = array();
        foreach ($hotels as $h) {
//            array_push($chambresParHotel,$chambreRepository->chambresDispoParHotel($h->getId()));
            $chambresParHotel[$h->getId()] = $chambreRepository->chambresDispoParHotel($h->getId());
        }
//        dd($chambresParHotel);
//        $chambres = $chambreRepository->chambresDispo();
        return $this->render('chambres/chambresDispoParHotel.html.twig', [
            'chambres' => $chambresParHotel,
            'hotels' => $hotels,
        ]);
    }

    /**
     * @Route("/dispoDate", name="app_chambres_dispo_date")
     */
    public function ListChDispoDate(Request $request, ChambreRepository $chambreRepository, HotelRepository $hotelRepository): Response
    {
        $dateA = $request->request->get('dateA');
        $dateD = $request->request->get('dateD');
        $hotels = $hotelRepository->findAll();
        $chambresParHotel = array();
        foreach ($hotels as $h) {
//            array_push($chambresParHotel,$chambreRepository->chambresDispoParHotel($h->getId()));
            $chambresParHotel[$h->getId()] = $chambreRepository->chambresDispoParHotelDate($h->getId(), $dateA, $dateD);
        }
//        dd($chambresParHotel);
//        $chambres = $chambreRepository->chambresDispo();
        return $this->render('chambres/chambresDispoParHotel.html.twig', [
            'chambres' => $chambresParHotel,
            'hotels' => $hotels,
        ]);
    }

    /**
     * @Route("/dispo/{id}", name="app_chambres_dispo_par_Hotel")
     */
    public function ListChDispoParHotel(ChambreRepository $chambreRepository, int $id, HotelRepository $hotelRepository): Response
    {
        $chambres = $chambreRepository->chambresDispoParHotel($id);
//        dd($chambres);
        return $this->render('chambres/chambresDispoParHotel.html.twig', [ //change Vue
            'chambres' => $chambres,
            'hotel' => $hotelRepository->find($id),
        ]);
    }

    /**
     * @Route("/{id}", name="app_chambres_show", methods={"GET"})
     */
    public function show(Chambre $chambre): Response
    {
        return $this->render('chambres/show.html.twig', [
            'chambre' => $chambre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_chambres_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Chambre $chambre, ChambreRepository $chambreRepository): Response
    {
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chambreRepository->add($chambre);
            return $this->redirectToRoute('app_chambres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chambres/edit.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_chambres_delete", methods={"POST"})
     */
    public function delete(Request $request, Chambre $chambre, ChambreRepository $chambreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $chambre->getId(), $request->request->get('_token'))) {
            $chambreRepository->remove($chambre);
        }

        return $this->redirectToRoute('app_chambres_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/dispo", name="chDispo")
     */
    public function estDisponible(ChambreRepository $chambreRepository, int $id)
    {
        $chambres = $chambreRepository->estDisponible(date_create('2022-04-10'), date_create('2022-04-15'), $id);
        dump($chambres);
        return $this->render('chambres/show.html.twig');
    }


}
