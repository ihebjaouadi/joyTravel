<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\PostLike;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\PostLikeRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * @Route("/commentaire")
 */
class CommentaireController extends AbstractController
{
    /**
     * @Route("/", name="app_commentaire_index", methods={"GET"})
     */
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_commentaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CommentaireRepository $commentaireRepository): Response
    {

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->add($commentaire);
            return $this->redirectToRoute('app_commentaire_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('hotel/show.html.twig/', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_commentaire_show", methods={"GET"})
     */
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_commentaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->add($commentaire);
            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_commentaire_delete", methods={"GET", "POST"})
     */
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {

        $hotel = $commentaire->getIDHotel()->getId();

        $em = $this->getDoctrine()->getManager();
        $em->remove($commentaire);
        $em->flush();


        return $this->redirectToRoute('app_hotel_show', ['id' => $hotel], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/like", name="commentaire_like", methods={"GET","POST"})
     *
     * @param Commentaire $commentaire
     * @param PostLikeRepository $repository
     * @return Response
     */

    public function like(Commentaire $commentaire, PostLikeRepository $repository, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $manager = $this->getDoctrine()->getManager();
            $user = $this->getUser();

            if (!$user) {
                return $this->json([
                    'code' => 403,
                    'messga' => "Unauthorized"
                ], 403);
            }
            // chercher si l'utilisateur connecté a dèja liker un commentaire
            $isLiked = $manager->getRepository(PostLike::class)->findBy(['Post' => $commentaire->getId(), 'user' => $user]);

            // si oui on va disliker le commentaire en supprimant le Like depuis la table like
            if ($isLiked) {
                $like = $repository->findOneBy(['user' => $user->getId(), 'Post' => $commentaire->getId()]);
                $commentaire->removeLike($like);
                $manager->remove($like);
                $manager->persist($commentaire);
                $manager->flush();


            } else {
                //si l'utilisateur n'a jamais licker ce commentaire, on ajoute une ligne dans la table like
                $like = new PostLike();
                $like
                    ->setPost($commentaire)
                    ->setUser($user)
                    ->setValue(1);

                $manager->persist($like);// pour alimenter l'objet
                $manager->flush();// pour ajouter une ligne dans la base
            }
            $likes = $manager->getRepository(PostLike::class)->findBy(['Post' => $commentaire->getId()]);
            $isLiked = $manager->getRepository(PostLike::class)->findBy(['Post' => $commentaire->getId(), 'user' => $user]);

            // on charge la meme page avec le meme contenu de la form pour mettre à jour les données
            $html = $this->renderView('hotel/likeAjaxhtml.twig',
                [
                    'commentaire' => $commentaire,
                    'isliked' => $isLiked,
                    'likes' => count($likes)
                ]);
            // on envoie une reponse à la call ajax (html) pour remplacer la div likeComment avec le contenu de la page
            return new Response($html);
        }

    }
}
