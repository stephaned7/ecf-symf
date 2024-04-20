<?php

namespace App\Form;

use App\Entity\User;
use App\DTO\DateConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'E-mail',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse e-mail.'
                    ]),
                ]
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un prénom.'
                    ]),
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'Nom de famille',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un nom de famille.'
                    ]),
                ]
            ])
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
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse.'
                    ]),
                ]
            ])
            ->add('zipcode', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'Code postal',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un code postal.'
                    ]),
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une ville.'
                    ]),
                ]
            ])
            ->add('phoneNum', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-dark text-white border-0 my-1',
                ],
                'label' => 'Numéro de téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un numéro de téléphone.'
                    ]),
                ]
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input mx-2 d-flex',
                ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'label' => 'J\'accepte les conditions d\'utilisation de ce site',
                'label_attr' => [
                    'class' => 'custom-bold'
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'options' => [
                    'attr' => [
                        'class' => 'form-control bg-dark text-white border-0 my-1   ',
                    ]
                ],
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe.'
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                            'max' => 4096,
                        ]),
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                ],
            ]);
        $builder->get('birthdate')->addModelTransformer(new DateConverter());

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
