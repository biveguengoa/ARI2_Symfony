<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Repository\IngredientRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RecipeType extends AbstractType
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
            ->add('duration', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min'=> '1',
                    'max' => '720',
                ],
                'required' => false,
                'label' => 'Duration (min)',
                'label_attr' => ['class'=> 'form-label mt-4'],
                'constraints' => [
                    new Assert\Positive,
                    new Assert\LessThan(720),
                ]
                ])
            ->add('nb_person', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => '1',
                    'max' => '30'
                ],
                'label' => 'Number of person',
                'label_attr' => ['class'=> 'form-label mt-4'],
                'required' => false,
                'constraints' => [
                    new Assert\Positive,
                    new Assert\LessThan(30),
                ]
                ])
            ->add('difficulty', RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => '1',
                    'max' => '5'
                ],
                'label' => 'Difficulty (1-5)',
                'label_attr' => ['class'=> 'form-label mt-4'],
                'required'=> false,
                'constraints' => [
                    new Assert\Positive,
                    new Assert\LessThan(5),
                ]
                ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Description',
                'label_attr' => ['class'=> 'form-label mt-4'],
                'constraints' => [
                    new Assert\NotBlank()
                ]
                ])
                ->add('ingredients', EntityType::class, [
                    'class' => Ingredient::class,
                    'query_builder' => function (IngredientRepository $r) {
                        return $r->createQueryBuilder('i')
                            ->orderBy('i.name', 'ASC');
                    },
                    'label' => 'Ingredients',
                    'label_attr' => ['class'=> 'form-label mt-4'],
                    'choice_label' => 'name',
                    'multiple' => 'true',
                    'expanded' => 'true'
                ])
                ->add('save', SubmitType::class, [
                    'attr' => ['class' => "btn btn-primary mt-4"],
                    'label' => 'Create recipe'
                ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
