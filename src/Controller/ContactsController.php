<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contacts")
 */
class ContactsController extends AbstractController
{
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
            return $this->redirectToRoute('app_contacts_front_listeReclamation', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('contacts/contactFront.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
