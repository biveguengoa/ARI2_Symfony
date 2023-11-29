<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    #[Route(path:"/", name:"recipe.index")]
    public function index(RecipeRepository $repo) : Response {
        return $this->render("recipe/index.html.twig", ['recipes' => $repo->findAll()]);
    }

    #[Route('recipe/create', name:'recipe.create')]
    public function create(Request $request, EntityManagerInterface $em): Response {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success','Recipe successfully created !');

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('recipe/create.html.twig', ['form' => $form]);
    }

    #[Route('recipe/edit/{id}', name:'recipe.edit')]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em) : Response {
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success','Recipe successfully edited !');

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('recipe/edit.html.twig', ['form' => $form]);
    }

    #[Route('recipe/delete/{id}', name:'recipe.delete')]
    public function delete(EntityManagerInterface $em, Recipe $recipe) : Response {
        if (!$recipe) {
            $this->addFlash('error','Recipe does not exit !');

            return $this->redirectToRoute('recipe.index');
        }

        $em->remove( $recipe);
        $em->flush();
        $this->addFlash('success','Recipe successfully removed !');

        return $this->redirectToRoute('recipe.index');
    }

}