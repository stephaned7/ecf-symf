<?php

namespace App\Form;

use App\Entity\User;
use App\DTO\DateConverter;
use App\DTO\RolesArrayConverter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserType extends AbstractType
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'Email',
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'Prénom',
            ])
            ;
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',    
                ],
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'Roles',
            ]);

            $builder->get('roles')->addModelTransformer(new RolesArrayConverter());
        }
        ;
        $builder
            ->add('birthdate', TextType::class, [
                'attr' => [
                    'type' => 'text',
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une date de naissance.'
                    ]),
                    new Callback([
                        'callback' => function ($value, ExecutionContextInterface $context) {
                            if ($value instanceof \DateTime) {
                                $value = $value->format('d/m/Y');
                            }

                            $date = \DateTime::createFromFormat('d/m/Y', $value);
                            if ($date === false || $date->format('d/m/Y') !== $value) {
                                $context->buildViolation('La date de naissance doit être au format JJ/MM/AAAA.')
                                    ->addViolation();
                            }
                        }
                    ])
                ],
                'label' => 'Date de naissance - Format JJ/MM/AAAA',
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'Adresse',
            ])
            ->add('zipcode', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'Code postal',
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'Ville',
            ])
            ->add('phone_num', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'Numéro de téléphone',
            ]);

        // $builder->get('roles')->addModelTransformer(new RolesArrayConverter());
        $builder->get('birthdate')->addModelTransformer(new DateConverter());

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
