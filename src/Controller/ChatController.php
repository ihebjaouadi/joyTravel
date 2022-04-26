<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Entity\Chat;
use App\Entity\User;
use App\Form\ChatType;
class ChatController extends AbstractController
{
    /**
     * @Route("/chat", name="app_chat")
     */
    public function index(): Response
    {
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
        ]);
    }
    /**
     * @Route("/send", name="app_send")
     */
    public function send(Request $request) : Response
    {$chat=new Chat();
        $form=$this->createForm(ChatType::class,$chat);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $chat->setIdSender($this->getUser());
            $em=$this->getDoctrine()->getManager();
            $em->persist($chat);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'notice',
                'message send !'
            );
        }
     return $this->render("chat/send.html.twig",[
            "form"=>$form->createView()
     ]);
    }
     /**
     * @Route("/Listchat", name="app_List_chat")
     */
    public function Listchat(Request $request)
    {$repository = $this->getDoctrine()->getRepository(Chat::class);
        $nom ="";
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $messages=$repository->findByIdReceiver($user->getId());
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$propertySearch);
        $form->handleRequest($request);
       //initialement le tableau des articles est vide, 
       //c.a.d on affiche les articles que lorsque l'utilisateur clique sur le bouton rechercher
        $messages= [];
        //$messages=$this->getDoctrine()->getRepository(Chat::class)->findAll();
        $messages=$repository->findByIdReceiver($user->getId());
        
       if($form->isSubmitted() && $form->isValid()) {
       //on récupère le nom d'article tapé dans le formulaire
        $nom = $propertySearch->getNom();   
        if ($nom!="") {
            $user1=$this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $nom]);
          //si on a fourni un nom d'article on affiche tous les articles ayant ce nom
          $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'SELECT * FROM `chat` WHERE id_sender_id= :param1 AND id_receiver_id= :param2;';
        
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        // Set parameters 
        $statement->bindValue('param1',$user1->getId());
        $statement->bindValue('param2',$user->getId());
        $messages = $statement->execute()->fetchAll();
        //$messages = $statement->fetchAll();
          //$messages= $this->getDoctrine()->getRepository(Chat::class)->findByIdSender($user1);
        }
         
           else   {
                 //si si aucun nom n'est fourni on affiche tous les articles
                 $messages=$repository->findByIdReceiver($user->getId());
                 
           }
          
       }
        return $this->render('chat/Listchat.html.twig', [
            'messages' =>$messages,'form' =>$form->createView(),'nom'=>$nom
        ]);
        
    }
    /**
     * @Route("/Delete_chat/{id}", name="app_Delete_chat")
     */
    public function Delete_Chat($id)
    {  
        $entityManager=$this->getDoctrine()->getManager();
        $chat=$entityManager->getRepository(Chat::class)->find($id);
        $entityManager->remove($chat);
        $entityManager->flush();
        return $this->redirectToRoute('app_List_chat');

    }
}
