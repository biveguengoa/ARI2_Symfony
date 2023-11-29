<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'ingredient.index')]
    public function index(IngredientRepository $repo): Response {

        return $this->render('ingredient/index.html.twig', ['ingredients' => $repo->findAll()]);
    }

    #[Route('ingredient/create', name:'ingredient.create')]
    public function create(Request $request, EntityManagerInterface $em): Response {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $em->persist($ingredient);
            $em->flush();

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('ingredient/create.html.twig', ['form' => $form]);
    }

    #[Route('ingredient/edit/{id}', name:'ingredient.edit')]
    public function edit(Ingredient $ingredient, Request $request, EntityManagerInterface $em) : Response {
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $em->persist($ingredient);
            $em->flush();

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('ingredient/edit.html.twig', ['form' => $form]);
    }

    #[Route('ingredient/delete/{id}', name:'ingredient.delete')]
    public function delete(EntityManagerInterface $em, Ingredient $ingredient) : Response {
        if (!$ingredient) {
            $this->addFlash('error','Ingredient does not exit !');

            return $this->redirectToRoute('ingredient.index');
        }

        $em->remove( $ingredient);
        $em->flush();
        $this->addFlash('success','Ingredient successfully removed !');

        return $this->redirectToRoute('ingredient.index');
    }

}
