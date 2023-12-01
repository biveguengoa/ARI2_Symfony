<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController {


    #[Route('/book', name: 'book.index')]
    public function index(BookRepository $repo): Response {

        return $this->render('book/index.html.twig', ['books' => $repo->findBy(['theUser' => $this->getUser()])]);
    }

    
    #[Route('book/create', name:'book.create')]
    public function create(Request $request, EntityManagerInterface $em): Response {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $book->setTheUser($this->getUser());

            $em->persist($book);
            $em->flush();
            $this->addFlash('success','Book successfully added !');

            return $this->redirectToRoute('book.index');
        }

        return $this->render('book/create.html.twig', ['form' => $form]);
    }
    
    #[Route('book/edit/{id}', name:'book.edit')]
    public function edit(Book $book, Request $request, EntityManagerInterface $em) : Response {
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();

            $em->persist($book);
            $em->flush();
            $this->addFlash('success','Book successfully edited !');

            return $this->redirectToRoute('book.index');
        }

        return $this->render('book/edit.html.twig', ['form' => $form]);
    }

    
    #[Route('book/delete/{id}', name:'book.delete')]
    public function delete(EntityManagerInterface $em, Book $book) : Response {
        if (!$book) {
            $this->addFlash('error','Book does not exit !');

            return $this->redirectToRoute('book.index');
        }

        $em->remove( $book);
        $em->flush();
        $this->addFlash('success','Book successfully removed !');

        return $this->redirectToRoute('book.index');
    }

    #[Route('book/borrow', name:'book.borrow')]
    public function borrowBook(BookRepository $repo): Response {

        return $this->render('book/index.html.twig', ['books' => $repo->findNotOwnedByTheUser(['theUser' => $this->getUser()])]);
    }
}
