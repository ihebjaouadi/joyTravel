<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
/*use Symfony\Component\HttpFoundation\File\Exception\FileException;*/
use Symfony\Component\HttpFoundation\File\UploadedFile;
/*use Symfony\Component\String\Slugger\SluggerInterface;*/

use Symfony\Component\String\Slugger\SluggerInterface;



/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{
    /**
     * @Route("/", name="app_evenement_index", methods={"GET"})
     */
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }





//Affichage de le client

    /**
     * @Route("/userGui", name="userGui", methods={"GET"})
     */
    public function userGui(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/UserEvent.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }






    /**
     * @Route("/new", name="app_evenement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EvenementRepository $repository, EntityManagerInterface $em): Response
    {

        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$file = $evenement->getImg();
            $file = $form->get('Img')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $em = $this->getDoctrine()->getManager();
            $evenement->setImg($fileName);

            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
        $evenement->setImg($fileName);

            $em->persist($evenement);
            $em->flush();
            $repository->add($evenement);

            return $this->redirectToRoute('app_evenement_index');
        }
        return $this->render('evenement/new.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}", name="app_evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_evenement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id,EvenementRepository $evenementRepository): Response
    {

        $evenement=$evenementRepository->find($id);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('Img')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $em = $this->getDoctrine()->getManager();
            $evenement->setImg($fileName);

            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
            $evenement->setImg($fileName);




            $em=$this->getDoctrine()->getManager();
           $em->flush();
            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement);
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
