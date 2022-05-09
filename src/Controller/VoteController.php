<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Form\VoteType;
use App\Entity\User;
use App\Repository\VoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vote")
 */
class VoteController extends AbstractController
{


    /**
     * @Route("/", name="app_vote_index", methods={"GET"})
     */
    public function index(Request $request, VoteRepository $voteRepository): Response
    {
        $vote = new vote();
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);
        dump($voteRepository->findAll());
        return $this->render('vote/index.html.twig', [
            'votes' => $voteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home", name="app_vote_index1", methods={"GET"})
     */
    public function index1(Request $request, VoteRepository $voteRepository): Response
    {
        $vote = new vote();
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);
        dump($voteRepository->findAll());
        return $this->render('vote/index1.html.twig', [
            'votes' => $voteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/stats", name="app_vote_stats")
     */
    public function statistiques(VoteRepository $voteRepository)
    {
        $vote = $voteRepository->getVoteCount();
        $vvote = [];
        $vvvote = [];
        dump($vote);

        foreach ($vote as $votes) {
            $vvote[] = $votes[1];
            $vvvote[] = $votes["vote"];
        }

        return $this->render('vote/stats.html.twig', [
            'vvote' => json_encode($vvote),
            'vvvote' => json_encode($vvvote)

        ]);
    }

    /**
     * @Route("/new", name="app_vote_new", methods={"GET", "POST"})
     */
    public function new(Request $request, VoteRepository $voteRepository): Response
    {
        $vote = new Vote();
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vote->setIDUser($this->getUser());

            $voteRepository->add($vote);
            return $this->redirectToRoute('app_vote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vote/new.html.twig', [
            'vote' => $vote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_vote_show", methods={"GET"})
     */
    public function show(Vote $vote): Response
    {
        return $this->render('vote/show.html.twig', [
            'vote' => $vote,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_vote_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Vote $vote, VoteRepository $voteRepository): Response
    {
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voteRepository->add($vote);
            return $this->redirectToRoute('app_vote_index1', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vote/edit.html.twig', [
            'vote' => $vote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_vote_delete", methods={"POST"})
     */
    public function delete(Request $request, Vote $vote, VoteRepository $voteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vote->getId(), $request->request->get('_token'))) {
            $voteRepository->remove($vote);
        }

        return $this->redirectToRoute('app_vote_index1', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/front/add", name="app_vote_front_addVote", methods={"GET","POST"})
     */
    public function addVote(Request $request, VoteRepository $voteRepository, UserRepository $userRepository): Response
    {
        $vote = new Vote();

        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $x = $this->get('security.token_storage')->getToken()->getUser();
            $user = $userRepository->find($x->getId());
            $vote->setIDUser($user);

            $voteRepository->add($vote);
            return $this->redirectToRoute('app_vote_index1', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('vote/voteFront.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
