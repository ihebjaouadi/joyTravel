<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Repository\ChambreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chambres")
 */
class ChambresController extends AbstractController
{
    /**
     * @Route("/", name="app_chambres_index", methods={"GET"})
     */
    public function index(ChambreRepository $chambreRepository): Response
    {
        $chs = $chambreRepository->chambresDispo();
//        $chambres = $chambreRepository->estDisponible(date_create('2022-04-20'), date_create('2022-04-25'));
//        dump($chambres);
        dump($chambreRepository->findAll());
        return $this->render('chambres/index.html.twig', [
            'chambres' => $chambreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_chambres_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ChambreRepository $chambreRepository): Response
    {
        $chambre = new Chambre();
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chambreRepository->add($chambre);
            return $this->redirectToRoute('app_chambres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chambres/new.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dispo", name="app_chambres_dispo")
     */
    public function ListChDispo(ChambreRepository $chambreRepository) : Response
    {
        $chambres = $chambreRepository->chambresDispo();
        return $this->render('chambres/index.html.twig', [
            'chambres' => $chambres,
        ]);
    }

    /**
     * @Route("/{id}", name="app_chambres_show", methods={"GET"})
     */
    public function show(Chambre $chambre): Response
    {
        return $this->render('chambres/show.html.twig', [
            'chambre' => $chambre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_chambres_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Chambre $chambre, ChambreRepository $chambreRepository): Response
    {
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chambreRepository->add($chambre);
            return $this->redirectToRoute('app_chambres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chambres/edit.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_chambres_delete", methods={"POST"})
     */
    public function delete(Request $request, Chambre $chambre, ChambreRepository $chambreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chambre->getId(), $request->request->get('_token'))) {
            $chambreRepository->remove($chambre);
        }

        return $this->redirectToRoute('app_chambres_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/{id}/dispo", name="chDispo")
     */
    public function estDisponible(ChambreRepository $chambreRepository, int $id)
    {
        $chambres = $chambreRepository->estDisponible(date_create('2022-04-10'), date_create('2022-04-15'),$id);
        dump($chambres);
        return $this->render('chambres/show.html.twig');
    }


}
