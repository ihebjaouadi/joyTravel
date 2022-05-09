<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Hotel;
use App\Entity\Image;
use App\Form\CommentaireType;
use App\Form\HotelType;
use App\Repository\CommentaireRepository;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\ChambreRepository;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Loader\LoaderInterface;

/**
 * @Route("/hotel")
 */
class HotelController extends AbstractController
{
    /**
     * @Route("/", name="app_hotel_index", methods={"GET","POST"})
     */
    public function index(HotelRepository $hotelRepository, Request $request): Response
    {
        $hotels = $hotelRepository->findAll();

        $hotelName = $hotelCity = $typeChambre = $tri = '';
        if ($request->getMethod() == "POST") {
            $hotelName = $request->request->get('name');
            $hotelCity = $request->request->get('city');
            $typeChambre = $request->request->get('typeChambre');
            $tri = $request->request->get('tri');

            $hotels = $hotelRepository->getHotelByFilters($hotelName, $hotelCity, $typeChambre, $tri);

        }
        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotels,
            'hotelNames' => $hotelRepository->getHotelNames(),
            'hotelCities' => $hotelRepository->getCities(),
            'hotelCity' => $hotelCity,
            'hotelName' => $hotelName,
            'typeChambre' => $typeChambre,
            'tri' => $tri,


        ]);
    }

    /**

     * @Route("/indexfida", name="app_hotel_index_fida", methods={"GET", "POST"})
     */
    public function indexFida(HotelRepository $hotelRepository, Request $request, ChambreRepository $chambreRepository): Response
    {
        $form = $this->createFormBuilder()
            ->add('dateA', DateType::class)
            ->add('dateD', DateType::class)
            ->add('typeChambre', ChoiceType::class, [
                'choices' => [
                    '' => 'Null',
                    'Single' => 'Single',
                    'Double' => 'Double',
                    'Triple' => 'Triple',
                    'Suite' => 'Suite',
                ]])
            ->add('rechercher', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $dateA = $form->get('dateA');
            $dateA = $dateA->getData();
            $dateD = $form->get('dateD');
            $dateD = $dateD->getData();
            $typeChambre = $form->get('typeChambre')->getData();
            if (strcasecmp($typeChambre, "Null") != 0) {
                $hotels = $hotelRepository->hotelsContenantChambresDispoTypeDate($dateA, $dateD, $typeChambre);
            } else {
                $hotels = $hotelRepository->hotelsContenantChambresDispoDate($dateA, $dateD);
            }
            $dateAFormatted = $dateA->format('d-m-Y');
            $dateDFormatted = $dateD->format('d-m-Y');
            return $this->render('hotel/hotelsDispo.html.twig', ['hotels' => $hotels, 'dateA' => $dateAFormatted, 'dateD' => $dateDFormatted, 'type' => $typeChambre]);
        }
        return $this->render('hotel/indexf.html.twig', [
            'hotels' => $hotelRepository->findAll(),
            'recherche' => $form->createView()
        ]);
    }
    /**
     * @Route("/adminHotel", name="app_hotel_admin_index", methods={"GET"})
     */
    public function indexAdmin(HotelRepository $hotelRepository): Response
    {
        return $this->render('hotel/index_admin.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/statistique", name="app_hotel_statistique", methods={"GET"})
     */
    public function statistique(HotelRepository $hotelRepository): Response
    {
        $stat = $hotelRepository->getStat();

        return $this->render('hotel/stat.html.twig', [
            'stats' => $hotelRepository->getStat(),
        ]);
    }

//    /**
//     * @Route("/statistique", name="app_hotel_statistique", methods={"GET"})
//     */
//    public function statistique(HotelRepository $hotelRepository): Response
//    {
//        $stat = $hotelRepository->getStat();
//
//        return $this->render('hotel/stat.html.twig', [
//            'stats' => $hotelRepository->getStat(),
//        ]);
//    }

    /**
     * @Route("/new", name="app_hotel_new", methods={"GET", "POST"})
     */
    public function new(Request $request, HotelRepository $hotelRepository): Response
    {
        $hotel = new Hotel();
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach ($images as $image) {

                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $imageEntity = new Image();
                $imageEntity->setChemain("/" . $fichier);
                $hotel->addImage($imageEntity);
            }
            $hotelRepository->add($hotel);

            $this->addFlash('success', 'Ajout efféctué avec succes');

            return $this->redirectToRoute('app_hotel_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hotel/new.html.twig', [
            'hotel' => $hotel,
            'form' => $form->createView(),
        ]);
    }

    /**

     *
     * @Route("/dispo",name="hotels_dispo")
     */
    public function hotelsContenatnChambresDispo(HotelRepository $hotelRepository)
    {
        $hotels = $hotelRepository->hotelsContenantChambresDispo();
//        dd($hotels);
        return $this->redirectToRoute('app_hotel_index');
    }

    /**
     * @Route("/{id}", name="app_hotel_show", methods={"GET","POST"})
     */
    public function show(Hotel $hotel, Request $request, CommentaireRepository $commentaireRepository): Response
    {

        if ($request->getMethod() == "POST") {
            $commentaire = new Commentaire();
            $commentaire->setContent($request->request->get('content'));
            $commentaire->setIDUser($this->getUser());
            $commentaire->setIDHotel($hotel);
            $commentaire->setDate(new \DateTime('now'));
            $this->getDoctrine()->getManager()->persist($commentaire);
            $this->getDoctrine()->getManager()->flush();

        }
        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel,
            'commentaires' => $commentaireRepository->findBy(['ID_hotel' => $hotel->getId()])

        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_hotel_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Hotel $hotel, HotelRepository $hotelRepository): Response
    {
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hotelRepository->add($hotel);
            $this->addFlash('success', 'Modification efféctué avec succes');
            return $this->redirectToRoute('app_hotel_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hotel/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_hotel_delete", methods={"POST"})
     */
    public function delete(Request $request, Hotel $hotel, HotelRepository $hotelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $hotel->getId(), $request->request->get('_token'))) {
            $hotelRepository->remove($hotel);
            $this->addFlash('success', 'Hotel supprimé avec succes');

        }

        return $this->redirectToRoute('app_hotel_admin_index', [], Response::HTTP_SEE_OTHER);
    }


}
