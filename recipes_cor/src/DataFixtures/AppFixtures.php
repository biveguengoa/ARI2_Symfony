<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture {
    
    public function load(ObjectManager $manager): void {
        $ingredients = [];
        for ($i = 1; $i <= 15; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName("Ingredient " .$i)
            ->setPrice(mt_rand(0, 20));

            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        for ($i = 1; $i <= 25; $i++) {
            $recipe = new Recipe();
            $recipe->setName("Recette ".$i);
            $recipe->setNbPerson(mt_rand(1,20));
            $recipe->setDifficulty(mt_rand(1,5));
            $recipe->setDuration(mt_rand(0,720));
            $recipe->setDescription("Recipe ".$i. " description");

            for($j = 0; $j < (mt_rand(3, 10)); $j++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) -1)]);
            }
            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
