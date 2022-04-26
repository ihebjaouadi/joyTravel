<?php

namespace App\Form;

use App\Entity\BlogCommentaires;
use App\Entity\BlogPost;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogCommentairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('user',EntityType::class,[
//                'class'=>User::class,
//                'choice_label'=>'Email',
//                'label' => 'Utilisateur',
//            ])
            ->add('post',EntityType::class,[
                'class'=>BlogPost::class,
                'choice_label'=>'titre',
                'label' => 'Post',
            ])
            ->add('body',TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogCommentaires::class,
        ]);
    }
}
