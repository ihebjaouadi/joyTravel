<?php

namespace App\Form;

use App\Entity\CategoryEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class CategoryEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom',TextType::class,['label'=>"Type d'evenement  :",

                'attr'=>[
                    'placeholder'=>"Merci de donner le Type de l'evenement"
                ]])

            /*      ->add('Nom',TextType::class,['label'=>"Type d'evenement  :",
                'constraints'=>new Length([
                    'min'=>2, 'max'=>50
                ]),

                'attr'=>[
                    'placeholder'=>"Merci de donner le Type de l'evenement"
                ]])
        */

            ->add('submit',SubmitType::class, ['label'=>"Enregisterer"] )


        ;

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryEvent::class,
        ]);
    }
}
