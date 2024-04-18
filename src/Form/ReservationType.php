<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' =>[
                    'form' => 'form-control'
                ], 
                'label' => 'Nom du créneau'
            ])
            ->add('start', DateTimeType::class, [
               'date_widget' => 'single_text',
               'label' => 'Début'
            ])
            ->add('end', DateTimeType::class,[
                'date_widget' => 'single_text',
                'label' => 'Fin'
            ])
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
