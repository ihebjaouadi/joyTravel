<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\BlogPostRepository;
use App\Repository\ChambreRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("/", name="app_reservation_index", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_reservation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReservationRepository $reservationRepository, MailerInterface $mailer): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $reservation = new Reservation();
        $reservation->setIDUser($user);
        $reservation->setDateReservation(new \DateTime('now'));
        $reservation->setPrixTotal(100);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            dump($reservation->getIDChambre());
//            dd($reservation->getIDChambre());
            $somme = $this->calculSomme($reservation, $form);
//            dd($somme);
            $nbrJours = intval(date_diff($reservation->getDateArrivee(),$reservation->getDateDepart())->format('%d'));
            $sommeFinale = $somme * $nbrJours;
//            dd($sommeFinale);
            $reservation->setPrixTotal($somme*$nbrJours);
            $reservationRepository->add($reservation);
            $this->createPDFTicket($reservation);
            $this->sendEmail($mailer,$reservation);
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/newReservation", name="app_res_json", methods={"GET", "POST"})
     */
    public function newJSON(ChambreRepository $chambreRepository,Request $request, ReservationRepository $repository, NormalizerInterface $normalizer, UserRepository $userRepository,MailerInterface $mailer): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
//        $user = $userRepository->find($request->get('user'));
        $reservation = new Reservation();
        $reservation->setIDUser($user);
        $reservation->setDateReservation(new \DateTime('now'));
        $reservation->setPrixTotal($request->get('prix'));
        $dateA = date_create_from_format('d-m-Y',$request->get('dateA'));
        $dateD = date_create_from_format('d-m-Y',$request->get('dateD'));
        $reservation->setDateArrivee($dateA);
        $reservation->setDateDepart($dateD);
        $reservation->setNbrPersonnes($request->get('nbr'));
        $reservation->setIDFormule($request->get('formule'));
        $ch = $request->get('idchambres');
        $idChambres =  explode (",", $ch);
        foreach ($idChambres as $i){
            $reservation->addIDChambre($chambreRepository->find($i));
        }
        $repository->add($reservation);
        $this->createPDFTicket($reservation);
        $this->sendEmail($mailer,$reservation);
//        $resJson = $normalizer->normalize($reservation, 'json', ['groups' => 'g']);
//        return new Response("Res Created" . json_encode($resJson));
        return $this->redirectToRoute('resuser',['id'=>$reservation->getIDUser()->getId()]);
    }

    public function calculSomme(Reservation $reservation, FormInterface $form)
    {
        $total = 0;
        $chambres = $reservation->getIDChambre();
        foreach ($chambres as $c){
            $total+=$c->getPrixnuite();
            if(strcasecmp(strval($form->get('ID_formule')->getData()),"Pension Complete")==0){
                $total=$total+15;
            }
            elseif(strcasecmp(strval($form->get('ID_formule')->getData()),"All Inclusive")==0){
                $total=$total+30;
            }
        }
        return $total;
    }
    /**
     * @Route("/pay")
     */
    public function pay(){
        $amount = 6.58;
        return $this->render('reservation/payement.html.twig', [
            'amount' => $amount,
        ]);
    }

    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer, Reservation $reservation/*,path*/)
    {
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/assets/documents';
        $pdfFilepath =  $publicDirectory . '/ticket.pdf';
//        $filesystem = new Filesystem();
//        $filesystem->chmod($publicDirectory.'/ticket.pdf', 0777);
        $email = (new TemplatedEmail())
            ->from('joytraveldevstudio@gmail.com')
            ->to($reservation->getIDUser()->getEmail())
//            ->to('mradfida10@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Thank you  for Booking With JoyTravel')
//            ->text('Sending emails is fun again!')
            ->attachFromPath($pdfFilepath)
//            ->attach(fopen($pdfFilepath, 'r+'))
//            ->html('<p>See Twig integration for better HTML integration!</p>');
            ->htmlTemplate('reservation/confirmation.html.twig')
        ->context(['id'=>$reservation->getId(),'dateArrivee'=>$reservation->getDateArrivee()]);

        $mailer->send($email);

//        return  $this->redirectToRoute('app_reservation_index');
//        return $this->render('reservation/ticket.html.twig');
    }
    /**
     * @Route("/ticket")
     */
    public function createPDFTicket(Reservation $reservation){
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->isRemoteEnabled();

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reservation/ticket.html.twig', [
            'dateReservation' => $reservation->getDateReservation(),
            'resid'=>$reservation->getId(),
            'user'=>$reservation->getIDUser()->getEmail(),
        ]);
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // Render the HTML as PDF
        $dompdf->render();
        // Store PDF Binary Data
        $output = $dompdf->output();
        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/assets/documents';
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/ticket.pdf';
        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);
    }

    /**
     * @Route("/{id}", name="app_reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_reservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->add($reservation);
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reservation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/user/{id}", name="resuser", methods={"GET"})
     */
    public function reservationsParUser(int $id,ReservationRepository $reservationRepository) :Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $reservations = $reservationRepository->findBy(array('ID_user'=>$id));
        return $this->render('reservation/resuser.html.twig',['reservations'=>$reservations]);
    }


}
