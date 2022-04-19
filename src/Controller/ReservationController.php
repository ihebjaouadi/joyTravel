<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

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
            $reservationRepository->add($reservation);
//            $this->createPDFTicket($reservation);
//            $this->sendEmail($mailer,$reservation);
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
    public function calculSomme(Reservation $reservation, Form $form)
    {
        $total = 0;
        $chambres = $reservation->getIDChambre();
        foreach ($chambres as $c){
            if(strcasecmp($c->getType(),"single")){
                $total+=$c->getDisponibilite(); // change dispo to prix nuité and from int to float/double
            }
            elseif (strcasecmp($c->getType(),"double")){
                $total+=$c->getDisponibilite()*1.2;
            }
            elseif (strcasecmp($c->getType(),"triple")){
                $total+=$c->getDisponibilite()*1.5;
            }
            elseif (strcasecmp($c->getType(),"quadruple")){
                $total+=$c->getDisponibilite()*1.7;
            }
            elseif (strcasecmp($c->getType(),"suite")){
                $total+=$c->getDisponibilite()*2;
            }
            if(strcasecmp($form->get('ID_formule'),"Pension Complete")){
                $total+=15;
            }
            elseif (strcasecmp($form->get('ID_formule'),"All Inclusive")){
                $total+=30;
            }

        }
        return $total;


    }
    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer, Reservation $reservation)
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
