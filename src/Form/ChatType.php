<?php

namespace App\Form;

use App\Entity\Chat;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
       


class ChatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message',TextareaType::class,[
                "attr"=>[
                    "class"=>"form-control"
                ]
            ])
            ->add('idReceiver',EntityType::class,[
                "class" => User::class,
                "choice_label" => "email",
                "attr"=>[
                    "class"=>"form-control"
                ]
            ])
            ->add('envoyer',SubmitType::class,[
                "attr"=>[
                    "class"=>"btn btn-primary"
                ] 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chat::class,
        ]);
    }
}
