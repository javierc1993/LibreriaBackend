<?php

namespace App\Controller\Api;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\BookFormType;
//este codigo hace lo mismo que librarycontroller pero lo hace mejor y con mejores practicas de programación al 
//serialization
class BooksController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/book")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(BookRepository $bookRepository)
    {
        return $bookRepository->findAll();
    }
    /**
     * @Rest\Post(path="/createBook")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(EntityManagerInterface $em, Request $request)
    {
        $book = new Book();
        $book->setTitle('Installing FOS REST');
        $em->persist($book);
        $em->flush();
        return $book;
    
    }
    /**
     * @Rest\Post(path="/createFormBook")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true) 
     */
    public function postActions(EntityManagerInterface $em, Request $request){
        $book = new Book();
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($book);
            $em->flush();
            return $book;
        }
        print_r("hola");
        return $form;
    }

}

?>