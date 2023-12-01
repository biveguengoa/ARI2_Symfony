<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '3',
                    'maxlength' => '50'
                ],
                'label' => 'Title',
                'label_attr' => ['class'=> 'form-label mt-4'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3, 'max' => 50])
                ]
            ])
            ->add('categories', ChoiceType::class, [
                'choices'  => Book::CATEGORIES,
                'choice_attr' => function() {
                    return ['class' => 'form-check-input me-1 ms-3'];
                },
                'label' => 'Categories',
                'label_attr' => ['class'=> 'form-label mt-4'],
                'multiple' => 'true',
                'expanded' => 'true'
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Description',
                'label_attr' => ['class'=> 'form-label mt-4'],
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => "btn btn-primary mt-4"],
                'label' => 'Add book'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
