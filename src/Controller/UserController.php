<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
        ]);
    }

    /**
     * @Route("admin/index", name="user_list")
     */
    public function UserList(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {
        $dql   = "SELECT a FROM App:User a";
        $query = $em->createQuery($dql);
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        return $this->render('admin/index.html.twig', [
            'users'=> $users
            ]);
    }

    /**
     * @Route("admin/add-user", name="add_user")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        
        $form = $this->createForm(UserType::class, new User());

        $form->handleRequest($request);

       if($form->isSubmitted() and $form->isValid()){
           $user = $form->getData();
           $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($user);
           $entityManager->flush();
           $this->addFlash(
            'notice',
            'L\'utilisateur '.$user->getId().' a été ajouté !');
           return $this->redirectToRoute('user_list');
       } else {

           return $this->render('admin/add-user.html.twig',
               [
                   'form'=> $form->createView()
               ]);
       }
    }

        /**
     * @Route("admin/detail/{user}", name="detail_user")
     */
    public function detailArticle(User $user)
    {

        return $this->render('admin/detail-user.html.twig', ['user'=>$user]);
    }

      /**
     * @Route("admin/edit-user/{user}", name="edit_user")
     */
    public function updateArticle(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'L\'utilisateur '.$user->getId().' a été mis à jour !');
            return $this->redirectToRoute('user_list');
        } else {
            return $this->render('admin/edit-user.html.twig',
                [
                    'form'=> $form->createView()
                ]);
        }
    }

    /**
     * @Route("admin/delete-user/{user}", name="delete_user")
     */
    public function deleteArticle(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        
        $this->addFlash(
            'notice',
            'L\'utilisateur '.$user->getId().' a été supprimé !');
        $entityManager->flush();
        return $this->redirectToRoute('user_list');
    }
}
