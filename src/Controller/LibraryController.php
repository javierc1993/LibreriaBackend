<?php
namespace App\Controller;//todas las clases deben llevar esto
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{JsonResponse};
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;



class LibraryController extends AbstractController
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/library/list",name="library_list")
     */
public function list(Request $request){//clase usada para listar 
    $title= $request->get('title');
    $this->logger->info('list prueba prueba');
    $response = new JsonResponse();
    $response->setData([
        'success'=>true,
        'data'=>[
            [
                'id'=>1,
                'title'=>'Tormenta de Espadas'
            ],
            [
                'id'=>2,
                'title'=>'Tormenta de Espadas'
            ],
            [
                'id'=>3,
                'title'=>$title
            ]
        ]
    ]);
    return $response;
}

/**
 * @Route("/book/create",name="create_book")
 */
 public function createBook(Request $request, EntityManagerInterface $em){
     $book = new Book();
     $response = new JsonResponse();
     $title = $request->get('title',null);
     if(empty($title)){
        $response->setData([
            'success'=>false,
            'error'=>'title cannot be empty',
            'data'=>null
        ]);
        return $response;

     }
     $book->setTitle($title);
     $em->persist($book);//sirve para identificar la entidad a doctrain controla ese objeto doctrain
     $em->flush();//este envia a la base de datos
     $response->setData([
        'success'=>true,
        'data'=>[
            [
                'id'=>$book->getId(),
                'title'=>$book->getTitle()
            ]
        ]
    ]);
    return $response;
 }

 /**
  * @Route("/books",name="books_get")
  */
  public function list2(Request $request, BookRepository $bookRepository){//clase usada para listar 
    $title= $request->get('title');
    $books=$bookRepository->findAll();
    $booksAsArray=[];
    foreach($books as $book){
        $booksAsArray[]=[
            'id'=> $book->getId(),
            'title'=>$book->getTitle(),
            'image'=>$book->getImage()
        ];
    };
    $response = new JsonResponse();
    $response->setData([
        'success'=>true,
        'data'=>$booksAsArray
    ]);
    return $response;
}
}
