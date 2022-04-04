<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Formule;
use App\Entity\Reservation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Date_reservation')
            ->add('Date_arrivee')
            ->add('Date_depart')
            ->add('Nbr_personnes')
            ->add('Prix_total')
            ->add('ID_user',EntityType::class,[
                'class'=>User::class,
                'choice_label'=>'Email'
            ])
            ->add('ID_chambre', EntityType::class,[
                'class'=>Chambre::class,
                'choice_label'=>'id'
            ])
            ->add('ID_formule', EntityType::class,[
                'class'=>Formule::class,
                'choice_label'=>'Type_chambre'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
