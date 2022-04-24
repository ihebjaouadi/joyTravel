<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use App\Form\SearchContactType;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contacts")
 */
class ContactsController extends AbstractController
{
    /**
     * @Route("/pdf", name="PDF_contact", methods={"GET"})
     */
    public function pdf(ContactRepository $ContactRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $png = file_get_contents("uploads/images/logo.png");
        $pngbase64 = base64_encode($png);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('contacts/PDF_contact.html.twig', [
            'contacts' => $ContactRepository->findAll(),
            "img64" => $pngbase64,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (inline view)
        $dompdf->set_base_path(realpath('plugins/fontawesome-free/css/all.min.css'));
        $dompdf->stream("ListeDesReclamtions.pdf", [
            "contacts" => true
        ]);
    }

    /**
     * @Route("/listReclamWithSearch", name="SearchContact")
     */
    public function listReclamWithSearch(Request $request, ContactRepository $contactRepository)
    {
        //All of Student
        $contact = $contactRepository->findAll();
        //search
        $searchForm = $this->createForm(SearchContactType::class);
        $searchForm->add("Rechercheer ", SubmitType::class, [
            'attr' => ['class' => 'btn btn-success float-right'],
        ]);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $statue = $searchForm['statue']->getData();
            $resulta = $contactRepository->searchHotel($statue);
            return $this->render('contacts/Search_Contact.html.twig', array(
                "contacts" => $resulta,
                "Search_Contact" => $searchForm->createView()));
        }
        return $this->render('contacts/Search_Contact.html.twig', array(
            "contacts" => $contact,
            "Search_Contact" => $searchForm->createView()));
    }

    /**
     * @Route("/orderByHotel", name="orderByHotel" ,methods={"GET"})
     */
    public function orderByHotel(Request $request, ContactRepository $ContactRepository): Response
    {
//list of students order By Dest
        $contact = $ContactRepository->orderByHotel();

        return $this->render('contacts/Trier_Contact.html.twig', [
            'contacts' => $contact,
        ]);

        //trie selon Date normal

    }

    /**
     * @Route("/", name="app_contacts_index", methods={"GET"})
     */
    public function index(Request $request, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        dump($contactRepository->findAll());
        return $this->render('contacts/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_contacts_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ContactRepository $contactRepository, UserRepository $userRepository): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->find(1);
            $contact->setIDUser($user);
            $contact->setStatue(0);
            $contactRepository->add($contact);
            return $this->redirectToRoute('app_contacts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contacts/new.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_contacts_show", methods={"GET"})
     */
    public function show(Contact $contact): Response
    {
        return $this->render('contacts/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_contacts_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Contact $contact, ContactRepository $contactRepository): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactRepository->add($contact);
            $this->addFlash('info', 'Réclamation Modifiée !');
            return $this->redirectToRoute('app_contacts_front_listeReclamation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contacts/edit.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edittraitment", name="app_contacts_editTraitment", methods={"GET", "POST"})
     */
    public function changerTraitment(Contact $contact, ContactRepository $contactRepository): Response
    {

        $contact->setStatue(1);
        $contactRepository->add($contact);
        return $this->redirectToRoute('app_contacts_index', [], Response::HTTP_SEE_OTHER);


    }

    /**
     * @Route("/{id}", name="app_contacts_delete", methods={"POST"})
     */
    public function delete(Request $request, Contact $contact, ContactRepository $contactRepository): Response
    {

        if ($this->isCsrfTokenValid('delete' . $contact->getId(), $request->request->get('_token'))) {
            $contactRepository->remove($contact);
            $this->addFlash('info', 'Réclamation Supprimée !');

        }

        return $this->redirectToRoute('app_contacts_front_listeReclamation', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/front/listReclamation", name="app_contacts_front_listeReclamation", methods={"GET","POST"})
     */
    public function list(Request $request, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        dump($contactRepository->findAll());
        return $this->render('contacts/listReclamation.html.twig', [
            'contacts' => $contactRepository->findAll(),
        ]);
    }


    /**
     * @Route("/front/new", name="app_contacts_front_addReclamation", methods={"GET","POST"})
     */
    public function addreclamation(Request $request, ContactRepository $contactRepository, UserRepository $userRepository): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $x = $this->get('security.token_storage')->getToken()->getUser();
            $user = $userRepository->find($x->getId());
            $contact->setIDUser($user);
            $contact->setStatue(0);
            $contactRepository->add($contact);
            $this->addFlash('info', 'Réclamation ajoutée !');
            return $this->redirectToRoute('app_contacts_front_listeReclamation', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('contacts/contactFront.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
