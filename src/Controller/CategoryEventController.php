<?php

namespace App\Controller;

use App\Entity\CategoryEvent;
use App\Form\CategoryEventType;
use App\Repository\CategoryEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/category/event")
 */
class CategoryEventController extends AbstractController
{



    /**
     * @Route("/afficher", name="app_categoryJSON", methods={"GET"})
     */
    public function afficher(NormalizerInterface $Normalizer)
    {
        $repository= $this->getDoctrine()->getRepository(CategoryEvent::class);
        $CategoryEvent = $repository->findAll();

        $jsonContent = $Normalizer->normalize($CategoryEvent,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));

    }





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
     * @Route("/newJson", name="app_category_event_newJSON", methods={"GET"})
     */
    public function newJson(Request $request, CategoryEventRepository $categoryEventRepository, NormalizerInterface $Normalizer)
    {
        $categoryEvent = new CategoryEvent();
        $em=$this->getDoctrine()->getManager();
$categoryEvent->setNom($request->get("Nom"));
            $em->persist($categoryEvent);
            $em->flush();

        $jsonContent = $Normalizer->normalize($categoryEvent,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));

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

        $this->addFlash('success', 'Category Modifier avec succes !');
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
        $this->addFlash('success', 'Category Supprimer!');
        return $this->redirectToRoute('app_category_event_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/Suppjson/{id}", name="jsonDelete", methods={"GET"})
     *
     */
    function DeleteJson($id, CategoryEventRepository $repository, NormalizerInterface $Normalizer){
        $Event=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Event);
        $em->flush();

        $jsonContent = $Normalizer->normalize($Event,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));

    }




}
