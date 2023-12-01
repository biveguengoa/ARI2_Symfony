<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '3',
                    'maxlength' => '20'
                ],
                'label' => 'Username',
                'label_attr' => ['class'=> 'form-label mt-4'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3, 'max' => 20])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => [
                    'attr' => ['class' => 'password-field']
                ],
                'required' => true,
                'first_options'  => [
                    'attr' => ['class' => 'form-control'],
                    'label' => 'Password',
                    'label_attr' => ['class'=> 'form-label mt-4']
                ],
                'second_options' => [
                    'attr' => ['class' => 'form-control'],
                    'label' => 'Confirm password',
                    'label_attr' => ['class'=> 'form-label mt-4']
                ]
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => "btn btn-primary mt-4"],
                'label' => 'Sign up'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
