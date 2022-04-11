<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Chat;
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
    public function Listchat()
    {$repository = $this->getDoctrine()
        ->getRepository(Chat::class);
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $messages=$repository->findByIdReceiver($user->getId());
        return $this->render('chat/Listchat.html.twig', [
            'messages' =>$messages,
        ]);
    }
}
