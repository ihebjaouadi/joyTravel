<?php

namespace App\Controller;

use App\Entity\BlogCommentaires;
use App\Entity\BlogPost;
use App\Entity\PostLike;
use App\Entity\User;
use App\Form\BlogCommentairesType;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use App\Repository\PostLikeRepository;
use Doctrine\Persistence\ObjectManager;
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
     * @Route("/temp")
     */
    public function openTemp()
    {
        return $this->render('blog_post/temp.html.twig');
    }

    /**
     * @Route("/{id}", name="app_blog_post_show")
     */
    public function show(BlogPost $blogPost, Request $request): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($request->getMethod() == "POST") {
            $commentaire = new BlogCommentaires();
            $commentaire->setBody($request->request->get('body'));
            $commentaire->setUser($user);
            $commentaire->setPost($blogPost);
            $this->getDoctrine()->getManager()->persist($commentaire);
            $this->getDoctrine()->getManager()->flush();
        }
        $comments = $blogPost->getBlogCommentaires();
        return $this->render('blog_post/show.html.twig', [
            'blog_post' => $blogPost,
            'commentaires' => $comments,
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
        if ($this->isCsrfTokenValid('delete' . $blogPost->getId(), $request->request->get('_token'))) {
            $blogPostRepository->remove($blogPost);
        }

        return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/like/{id}",name="like")
     */
    public function like(BlogPost $post, PostLikeRepository $likeRepo, int $id): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
//        if (!$user) return $this->json([
//            'code' => 403,
//            'message' => "Unauthorized"
//        ], 403);
        if ($post->isLikedByUser($user)) {
            $like = $likeRepo->findOneBy([
                'post' => $post,
                "user" => $user
            ]);
            $manager->remove($like);
            $manager->flush();
//            return $this->json([
//                'code' => 200,
//                'message' => 'Like supprime',
//                'likes' => $likeRepo->count(['post' => $post])
//            ], 200);
        }
        $like = new PostLike();
        $like->setPost($post);
        $like->setUser($user);
        $manager->persist($like);
        $manager->flush();
//        return $this->json([
//            'code' => 200,
//            'message' => 'Like Ajoute',
//            'likes' => $likeRepo->count(['post' => $post])], 200);
        return $this->redirectToRoute('app_blog_post_show',['id'=>$id]);
    }
}
