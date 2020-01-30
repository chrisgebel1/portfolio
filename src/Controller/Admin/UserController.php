<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller\Admin
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(UserRepository $userRepository,
                            TypeRepository $typeRepository)
    {
        $types = $typeRepository->findAll();

        $users = $userRepository->findAll();

        return $this->render(
            'admin/user.html.twig',
            [
                'users' => $users,
                'types' => $types
            ]
        );
    }

    /**
     * @Route("/ajaxedit/{id}/role/{role}", requirements={"id": "\d+", "role":"^[A-Z_]+"})
     */
    public function editRole(
        EntityManagerInterface $entityManager,
        $id, $role): Response
    {

        $currentUser = $this->getUser();
        $userEdit = $entityManager->find(User::class, $id);

        if ( is_null($userEdit) ) // si l'id ne correspond pas un un utilisateur
        {
            return $this->json([
                'status'=> 403,
                'message'=>'Utilisateur inexistant',
                'user' => $id,
            ],403);
        } elseif ( $currentUser->getRole() == 'ROLE_ADMIN' ) // si l'utilisateur connecté est Admin
        {
            if ( $currentUser->getId() == $userEdit->getId() ) // si l'utilisateur connecté modifie son rôle
            {
                return $this->json([
                    'status'=> 403,
                    'message'=>'Vous ne pouvez pas modifier votre rôle',
                    'user' => $id,
                ],403);
            }

            if ( in_array($role, $userEdit->getRolesAccepted()) ){
                $userEdit->setRole($role);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($userEdit);
                $entityManager->flush();

                return $this->json([
                    'status'=> 200,
                    'message'=>'Le rôle de l\'utilisateur ' . $userEdit->getPseudo() . ' a été modifié',
                    'user' => $id,
                ],200);
            }

        }


    }

    /**
     * @Route("/delete/{id}", requirements={"id":"\d+"})
     */
    public function delete(
        EntityManagerInterface $entityManager,
        $id)
    {
        $user = $entityManager->find(User::class, $id);
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        if ( is_null($user) )
        {
            // throw new NotFoundHttpException();
            $this->addFlash('error', 'Cet utilisateur n\'existe pas');
            return $this->redirectToRoute('app_admin_user_index');
        }

        if ( $currentUser->getId() == $id )
        {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer votre compte utilisateur');
            return $this->redirectToRoute('app_admin_user_index');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été supprimé');
        return $this->redirectToRoute('app_admin_user_index');
    }

}
