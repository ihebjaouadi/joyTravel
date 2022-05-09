<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostlikeController extends AbstractController
{
    /**
     * @Route("/PostLike", name="app_PostLike")
     */
    public function index(): Response
    {
        return $this->render('PostLike/index.html.twig', [
            'controller_name' => 'PostLikeController',
        ]);
    }
}
