<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Hotel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Type')
            ->add('Disponibilite')
            ->add('ID_hotel', EntityType::class,[
                'class'=>Hotel::class,
                'choice_label'=>'Nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
        ]);
    }
}
