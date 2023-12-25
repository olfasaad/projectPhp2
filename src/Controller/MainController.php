<?php

namespace App\Controller;
use App\Form\PostType;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MainController extends AbstractController
{
    private $em;
    public function __Construct(EntityManagerInterface $em){
        $this->em=$em ;
    }
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        $posts=$this->em->getRepository(Post::class)->findAll();
        return $this->render('main/index.html.twig', [
            'posts' => $posts ,
        ]);
    }
    #[Route('/post', name: 'app_post')]
    public function createPost(Request $request)
    {
        $post=new Post ();
        $form=$this->createForm(PostType::class,$post);
        $form->handleRequest($request); 
        if($form->isSubmitted()&& $form->isValid()){
            $this->em->persist($post);
            $this->em->flush();
            $this->addFlash('message','Inserted succes');
            return $this->redirectToRoute('app_main');
        }
        return $this->render('main/post.html.twig',['form' => $form->CreateView()],);
    }

    #[Route('/editpost/{id}', name: 'app_edit')]
    public function editPost(Request $request ,$id)
    { 
        $post=$this->em->getRepository(Post::class)->find($id);
        $form=$this->createForm(PostType::class,$post);
        $form->handleRequest($request); 
        if($form->isSubmitted()&& $form->isValid()){
            $this->em->persist($post);
            $this->em->flush();
            $this->addFlash('message','Edited succes');
            return $this->redirectToRoute('app_main');
        }
        return $this->render('main/post.html.twig',['form' => $form->CreateView()],);
      
    }
    #[Route('/deletepost/{id}', name: 'app_delete')]
    public function deletePost(Request $request ,$id)
    { 
        $post=$this->em->getRepository(Post::class)->find($id);
    
            $this->em->remove($post);
            $this->em->flush();
            $this->addFlash('message','Deleted succes');
        return $this->redirectToRoute('app_main');
       
    }
}
