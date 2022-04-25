<?php

namespace App\Controller;

use App\Entity\CategoryEvent;
use App\Form\CategoryEventType;
use App\Repository\CategoryEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category/event")
 */
class CategoryEventController extends AbstractController
{
    /**
     * @Route("/", name="app_category_event_index", methods={"GET"})
     */
    public function index(CategoryEventRepository $categoryEventRepository): Response
    {
        return $this->render('category_event/index.html.twig', [
            'category_events' => $categoryEventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_category_event_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategoryEventRepository $categoryEventRepository): Response
    {
        $categoryEvent = new CategoryEvent();
        $form = $this->createForm(CategoryEventType::class, $categoryEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryEventRepository->add($categoryEvent);
            $this->addFlash('success', 'Category ajouter avec succes !');

            return $this->redirectToRoute('app_category_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category_event/new.html.twig', [
            'category_event' => $categoryEvent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_category_event_show", methods={"GET"})
     */
    public function show(CategoryEvent $categoryEvent): Response
    {
        return $this->render('category_event/show.html.twig', [
            'category_event' => $categoryEvent,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_category_event_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CategoryEvent $categoryEvent, CategoryEventRepository $categoryEventRepository): Response
    {
        $form = $this->createForm(CategoryEventType::class, $categoryEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryEventRepository->add($categoryEvent);
            return $this->redirectToRoute('app_category_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category_event/edit.html.twig', [
            'category_event' => $categoryEvent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_category_event_delete", methods={"POST"})
     */
    public function delete(Request $request, CategoryEvent $categoryEvent, CategoryEventRepository $categoryEventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryEvent->getId(), $request->request->get('_token'))) {
            $categoryEventRepository->remove($categoryEvent);
        }

        return $this->redirectToRoute('app_category_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
