<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Name',
                'label_attr' => [
                    'class'=> 'form-label mt-4', 
                    'minlength' => '2',
                    'maxlength' => '50' ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
                ])
            ->add('price', MoneyType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Price',
                'label_attr' => ['class'=> 'form-label mt-4'],
                'constraints' => [
                    new Assert\LessThan(200),
                    new Assert\Positive()
                ]
                ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => "btn btn-primary mt-4"],
                'label' => 'Create ingredient'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
