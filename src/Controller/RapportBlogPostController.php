<?php

namespace App\Controller;

use App\Entity\RapportBlogPost;
use App\Form\RapportBlogPostType;
use App\Repository\BlogPostRepository;
use App\Repository\RapportBlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportpost")
 */
class RapportBlogPostController extends AbstractController
{
    /**
     * @Route("/", name="app_rapport_blog_post_index", methods={"GET"})
     */
    public function index(RapportBlogPostRepository $rapportBlogPostRepository): Response
    {
        return $this->render('rapport_blog_post/index.html.twig', [
            'rapport_blog_posts' => $rapportBlogPostRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_rapport_blog_post_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RapportBlogPostRepository $rapportBlogPostRepository, int $id, BlogPostRepository $blogPostRepository): Response
    {
        $post = $blogPostRepository->find($id);
        $rapportBlogPost = new RapportBlogPost();
        $rapportBlogPost->setPost($post);
        $form = $this->createForm(RapportBlogPostType::class, $rapportBlogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rapportBlogPostRepository->add($rapportBlogPost);
            return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rapport_blog_post/new.html.twig', [
            'rapport_blog_post' => $rapportBlogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_rapport_blog_post_show", methods={"GET"})
     */
    public function show(RapportBlogPost $rapportBlogPost): Response
    {
        return $this->render('rapport_blog_post/show.html.twig', [
            'rapport_blog_post' => $rapportBlogPost,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_rapport_blog_post_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, RapportBlogPost $rapportBlogPost, RapportBlogPostRepository $rapportBlogPostRepository): Response
    {
        $form = $this->createForm(RapportBlogPostType::class, $rapportBlogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rapportBlogPostRepository->add($rapportBlogPost);
            return $this->redirectToRoute('app_rapport_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rapport_blog_post/edit.html.twig', [
            'rapport_blog_post' => $rapportBlogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_rapport_blog_post_delete", methods={"POST"})
     */
    public function delete(Request $request, RapportBlogPost $rapportBlogPost, RapportBlogPostRepository $rapportBlogPostRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rapportBlogPost->getId(), $request->request->get('_token'))) {
            $rapportBlogPostRepository->remove($rapportBlogPost);
        }

        return $this->redirectToRoute('app_rapport_blog_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
