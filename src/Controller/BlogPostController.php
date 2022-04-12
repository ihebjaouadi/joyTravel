<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blogpost")
 */
class BlogPostController extends AbstractController
{
    /**
     * @Route("/", name="app_blog_post_index", methods={"GET"})
     */
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('blog_post/index.html.twig', [
            'blog_posts' => $blogPostRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_blog_post_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BlogPostRepository $blogPostRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $blogPost = new BlogPost();
        $blogPost->setUser($user);
        $blogPost->setDateCreation(new \DateTime('now'));
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogPostRepository->add($blogPost);
            return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_post/new.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_blog_post_show", methods={"GET"})
     */
    public function show(BlogPost $blogPost): Response
    {
        return $this->render('blog_post/show.html.twig', [
            'blog_post' => $blogPost,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_blog_post_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, BlogPost $blogPost, BlogPostRepository $blogPostRepository): Response
    {
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogPostRepository->add($blogPost);
            return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_post/edit.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_blog_post_delete", methods={"POST"})
     */
    public function delete(Request $request, BlogPost $blogPost, BlogPostRepository $blogPostRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->request->get('_token'))) {
            $blogPostRepository->remove($blogPost);
        }

        return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
