<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostlikeController extends AbstractController
{
    /**
     * @Route("/postlike", name="app_postlike")
     */
    public function index(): Response
    {
        return $this->render('postlike/index.html.twig', [
            'controller_name' => 'PostlikeController',
        ]);
    }
}
