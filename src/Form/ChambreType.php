<?php

namespace App\Form;

use App\Entity\Chambre;

use App\Entity\Hotel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Type')
            ->add('ID_hotel')
            ->add('Disponibilite', ChoiceType::class, [
                'choices' => [
                    'Disponibile' => 1,
                    'Non Disponibile' => 0,

                ]
            ])
            ->add('Type', ChoiceType::class, [
                'choices' => [
                    ''=>'',
                    'Single' => 'Single',
                    'Double' => 'Double',
                    'Triple' => 'Triple',
                    'quadruple' => 'quadruple',
                    'Suite' => 'Suite',

                ]
            ])
            ->add('Prixnuite')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
        ]);
    }
}
