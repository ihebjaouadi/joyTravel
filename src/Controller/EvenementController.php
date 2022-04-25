<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\ReservationEvenement;
use App\Entity\User;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationEvenementRepository;
use CalendarBundle\CalendarBundle;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\String\Slugger\SluggerInterface;



/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{

    /**
     * @Route("/", name="app_evenement_index", methods={"GET"})
     */
    public function index(EvenementRepository $evenementRepository, SessionInterface $session): Response
    {

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'evenements' => $evenementRepository->MiseAjourDeDataBase(),
            'evenements' => $evenementRepository->findAll(),
        ]);
    }


//Desplay for user

    /**
     * @Route("/userGui", name="userGui", methods={"GET"})
     */
    public function userGui(EvenementRepository $evenementRepository): Response
    {
        $evenement=new Evenement();
        $evenement=$evenementRepository->findAll();
        return $this->render('evenement/UserEvent.html.twig', [
            'evenements' =>  $evenement,
        ]);
    }



    /**
     * @Route("/userGui/calender", name="userGuiCallendre", methods={"GET"})
     */
    public function userGuiCallender(EvenementRepository $evenementRepository): Response
    {
        $evenement=new Evenement();
        $events=$evenementRepository->findAll();
//dd($evenement);
        $rdvs = [];
        foreach ($events as $event ) {
            $rdvs[] = [
                'id' => $event->getId(),
                'Nom' => $event->getNom(),
                'Date_debut' => $event->getDateDebut()->format('y-m-d'),
                'Date_fin' => $event->getDateFin()->format('y-m-d'),


            ];
        }
//dd($event);
        // dd($rdvs);
        $data= json_encode($rdvs);
        // dd($data);
        return $this->render('evenement/calendar.html.twig',  compact('data'),
        );
    }




    /**
     * @Route("/userGui/{id}", name="user_show", methods={"GET"})
     */
    public function Usershow(Evenement $evenement): Response
    {
        return $this->render('evenement/showUser.html.twig', [
            'evenement' => $evenement,
        ]);
    }


//Create An Event
    /**
     * @Route("/new", name="app_evenement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EvenementRepository $repository, EntityManagerInterface $em): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$file = $evenement->getImg();
            $file = $form->get('Img')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $em = $this->getDoctrine()->getManager();
            $evenement->setImg($fileName);
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
        $evenement->setImg($fileName);
            $em->persist($evenement);
            $em->flush();
            $repository->add($evenement);
            $this->addFlash('success', 'Evenement ajouter avec succes!');
            return $this->redirectToRoute('app_evenement_index');
        }
        return $this->render('evenement/new.html.twig', array(
            'form' => $form->createView()
        ));
    }




//update an event
    /**
     * @Route("/{id}/edit", name="app_evenement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id,EvenementRepository $evenementRepository): Response
    {

        $evenement=$evenementRepository->find($id);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('Img')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $em = $this->getDoctrine()->getManager();
            $evenement->setImg($fileName);
            $file->move(
                $this->getParameter('images_directory'),
                $fileName

            );
            $evenement->setImg($fileName);
            $em=$this->getDoctrine()->getManager();
           $em->flush();
            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }
    //delete
    /**
     * @Route("/{id}", name="app_evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement);
        }
        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }

    /**

     * @Route("/Test" ,name="Test", methods={"GET"}))
     */

function OrderByPriceSQL(EvenementRepository $repository){
        $evenement =$repository->OrderByPrice();
   return $this->render('evenement/UserEvent.html.twig', [
            'evenements' =>  $evenement,
        ]);
}

    /**
     * @Route("/Reserver/{id}", name="app_ress", methods={"GET"})
     */
    public function ReserverEvenement(Evenement $evenement,$id,EvenementRepository $repository, SessionInterface $session): Response
    {
        $user =new User();
        $reservationEvenement = new ReservationEvenement();
        $em=$this->getDoctrine()->getManager();
        $ev = $this->entityManager->getRepository(Evenement::class)->findOneByid($id);
        $reservationEvenement->setIDEvenement( $ev );
        $reservationEvenement->setIDUser($this->getUser());
        $evenement=$repository->DecriseNbrParticipants($id);
        $em= $this->getDoctrine()->getManager();
        $em->persist($reservationEvenement);
        $em->flush();
        $this->addFlash('success', 'Event Add it succusfuly! Knowledge is power!');
       // $some = $this->get('Add_cart_event')->SessionEvent($id, $session);
        return $this->redirectToRoute('userGui');
    }

    /**
     * @Route("/DeleteR/{id}" , name="DeleteR", methods={"GET"})
     */
    public function DeleteReservation(Evenement $evenement,$id,EvenementRepository $repository , ReservationEvenementRepository $repositoryRE): Response
    {

        $ev =new Evenement();
        $reservationEvenement = new ReservationEvenement();
        $ev = $this->entityManager->getRepository(Evenement::class)->findOneByid($id);
        $reservationEvenement->setIDEvenement( $ev );
        $idd=$reservationEvenement->getIDEvenement()->getId();
        $reservationEvenement->getIDEvenement()->getId();
        $em=$this->getDoctrine()->getManager();
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'Delete from  reservation_evenement where id_evenement_id = ?';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement ->bindValue(1, $idd);
        $statement->execute();
        $resultSet =  $statement->executeQuery();
        //$result = $statement->fetchAll();
        $this->addFlash('success', 'Reservation supprimer !');
        return $this->redirectToRoute('app_evenement_index');

    }
    /**

     * @Route("evenement/Recherche", name="recherche")
     */

    function RechercheAdmin(EvenementRepository $repository, Request $request){
        $value=$request->get('search');
        $evenement=$repository->findEventByValue($value);
        return $this ->render("evenement/index.html.twig",
            ['evenements' =>  $evenement]);
    }

    /**
     * @Route("evenement/Re", name="rechercheUser")
     */

    function RechercheUser(EvenementRepository $repository, Request $request){
        $value=$request->get('searchUser');
        $evenement =$repository->findPlanBySujet($value);
        return $this ->render("evenement/UserEvent.html.twig",
            ['evenements' =>  $evenement]);
    }


    /**
     * @Route("userGui/TrieA", name="TrieUser", methods={"GET"})
     */
    public function TrieUser(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/UserEvent.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'evenements' => $evenementRepository->OrderByPriceASC(),
        ]);
    }
    /**
     * @Route("userGui/TrieD", name="TriPriceDESCUser", methods={"GET"})
     */
    public function TrieDESCUser(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/UserEvent.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'evenements' => $evenementRepository->OrderByPriceDESC(),
        ]);
    }

    /**
     * @Route("/Trie", name="TriPriceASC", methods={"GET"})
     */
    public function Trie(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'evenements' => $evenementRepository->OrderByPriceASC(),
        ]);
    }
    /**
     * @Route("/TrieDESC", name="TriPriceDESC", methods={"GET"})
     */
    public function TrieDESC(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'evenements' => $evenementRepository->OrderByPriceDESC(),
        ]);
    }


    /**
     * @Route("/{id}", name="app_evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }



    /**
     * @param EvenementRepository $evenementRepository
     * @return Response
     * @Route("/pdf/pdf/pdf", name="app_pdf"  , methods={"GET"})
     */
    public function PDF(EvenementRepository $evenementRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
       // $pdfOptions->setTempDir('images_directory');
        //$pdfOptions->set('isRemoteEnabled', true);
        // $pdfOptions->set('images_directory', __DIR__);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $evenements= $evenementRepository->findAll();
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('evenement/pdf.html.twig', [
            'evenements' => $evenements,
        ]);
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }




}
