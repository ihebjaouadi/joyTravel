<?php

namespace App\Controller;

use App\Entity\BlogCommentaires;
use App\Form\BlogCommentairesType;
use App\Repository\BlogCommentairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog/commentaires")
 */
class BlogCommentairesController extends AbstractController
{
    /**
     * @Route("/", name="app_blog_commentaires_index", methods={"GET"})
     */
    public function index(BlogCommentairesRepository $blogCommentairesRepository): Response
    {
        return $this->render('blog_commentaires/index.html.twig', [
            'blog_commentaires' => $blogCommentairesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_blog_commentaires_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BlogCommentairesRepository $blogCommentairesRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $blogCommentaire = new BlogCommentaires();
        $blogCommentaire->setUser($user);
        $form = $this->createForm(BlogCommentairesType::class, $blogCommentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogCommentairesRepository->add($blogCommentaire);
            return $this->redirectToRoute('app_blog_commentaires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_commentaires/new.html.twig', [
            'blog_commentaire' => $blogCommentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_blog_commentaires_show", methods={"GET"})
     */
    public function show(BlogCommentaires $blogCommentaire): Response
    {
        return $this->render('blog_commentaires/show.html.twig', [
            'blog_commentaire' => $blogCommentaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_blog_commentaires_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, BlogCommentaires $blogCommentaire, BlogCommentairesRepository $blogCommentairesRepository): Response
    {
        $form = $this->createForm(BlogCommentairesType::class, $blogCommentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogCommentairesRepository->add($blogCommentaire);
            return $this->redirectToRoute('app_blog_commentaires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_commentaires/edit.html.twig', [
            'blog_commentaire' => $blogCommentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_blog_commentaires_delete", methods={"POST"})
     */
    public function delete(Request $request, BlogCommentaires $blogCommentaire, BlogCommentairesRepository $blogCommentairesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogCommentaire->getId(), $request->request->get('_token'))) {
            $blogCommentairesRepository->remove($blogCommentaire);
        }

        return $this->redirectToRoute('app_blog_commentaires_index', [], Response::HTTP_SEE_OTHER);
    }
}
