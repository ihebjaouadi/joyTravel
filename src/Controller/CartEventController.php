<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartEventController extends AbstractController
{

    /**
     * @Route("/Pannier/event", name="MonPanier")
     */
    public function index(SessionInterface $session, EvenementRepository $evenementRepository): Response
    {
    $panier= $session->get('panier', []);
    $panierWithData =[];
    foreach ($panier as $id => $quantity){
        $panierWithData[]= [
            'event'=> $evenementRepository->find($id),
            'quantity'=>$quantity
        ];
    }
//dd($panierWithData);
        $total=0;
    foreach ($panierWithData as $item){
    $totalItem=$item['event']->getPrix()  * $item['quantity'];
    $total += $totalItem;
    }

        return $this->render('cart_event/index.html.twig', [
            'items' => $panierWithData,
            'total'=>$total,
        ]);
    }

    /**
     * @Route("/Pannier/event/add/{id}", name="Add_cart_event")
     */
    public function SessionEvent($id,  SessionInterface $session): Response
    {
       // $session = $request->getSession();   SessionInterface $session
        $panier= $session->get('panier', []);
        $session->set('panier', $panier);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id]=1;
        }
        $session->set('panier', $panier);
     //   $session->remove('panier');

//dd($session->get('panier'));
        return $this->redirectToRoute("MonPanier");
    }


    /**
     * @Route("/Panier/event/Remove/{id}", name="Remove_cart_event")
     */
    public function SessionEventRemove(SessionInterface $session ,$id): Response
    {

        $panier= $session->get('panier', []);   //extraire mon panier
        if(!empty($panier [$id])) {
            unset($panier[$id]);  //suprimer
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute("MonPanier");
    }








}
