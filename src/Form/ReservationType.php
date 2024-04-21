<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Salle;
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReservationType extends AbstractType
{
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'mapped' => false,
                'constraints' => new NotBlank(),
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => $this->translator->trans('First name'),
            ])
            ->add('lastName', TextType::class, [
                'mapped' => false,
                'constraints' => new NotBlank(),
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => $this->translator->trans('Last name'),
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => $this->translator->trans('Title'),
            ])
            ->add('start', DateTimeType::class, [
               'date_widget' => 'single_text',
               'label' => $this->translator->trans('Start'),
            ])
            ->add('end', DateTimeType::class,[
                'date_widget' => 'single_text',
                'label' => $this->translator->trans('End'),
            ])
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => $this->translator->trans('nom'),
                'label' => $this->translator->trans('Room'),

            ])
            ->add('description', TextareaType::class, [
                'label' => $this->translator->trans('description'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'allow_extra_fields' => true,
        ]);
    }
}
