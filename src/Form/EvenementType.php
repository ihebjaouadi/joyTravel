<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Hotel;
use Doctrine\DBAL\Types\TextType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormTypeInterface;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom',\Symfony\Component\Form\Extension\Core\Type\TextType::class,[


                'attr'=>['placeholder'=>'Nom de event']
            ])









            ->add('Type')


            ->add('Date_debut',DateType::class,[
                'required' => false,
                'widget' => 'single_text',
                'by_reference' => true,

                'label'=>"  Date Debut   :"])
        ->add('Date_fin', DateType::class,[


            'required' => false,
            'widget' => 'single_text',
            'by_reference' => true,

        'label'=>"  Date Debut   :"])



            /*
            ->add('Date_debut', DateType::class, array(
                'label' => 'Date Fin  : ',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                    new DateType(),
                ]
            ))->add('Date_fin', DateType::class, array(
                'label' => 'Date Debut  :',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                    new DateType(),
                    new GreaterThan    ([
                        'propertyPath' => 'parent.all[Date_fin].data'
                    ]),
                ] ))   */
            ->add('Prix',NumberType::class,[
                'label'=>"Prix :"
            ])


            ->add('Nombre_Participants',NumberType::class,[
        'label'=>"Prix :"
    ])

            ->add('Description',TextareaType::class,[
                'label'=>"Description  :"
            ])

            ->add('ID_hotel',EntityType::class,[
              'class'=>Hotel::class,
              'choice_label'=> 'Nom',
               'multiple'=> false,
              'expanded'=> true,

                    'label'=>"Nom De l'Hotel :"
                        ])
            ->add('Category')


            //->add('Img')
           // ->add('Img',FileType::class,  ['data_class' => null,'required' => false] )
          ->add('Img', FileType::class , array("attr"=> array(),'data_class' => null, 'required' => false))
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
