<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\EdituserType;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            'admin/backoffice/user.html.twig',
            [
                'users' => $users,
                'types' => $types
            ]
        );
    }

    /**
     * @Route("/edit/{id}", defaults={"id"=null}, requirements={"id": "\d+"})
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        TypeRepository $typeRepository,
        $id)
    {
        $types = $typeRepository->findAll();

        $users = $userRepository->findAll();
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        if ( !is_null($id) )
        {
            $userEdit = $entityManager->find(User::class, $id);
        }

        if ( is_null($userEdit) )
        {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(EdituserType::class, $userEdit);
        $form->handleRequest($request);

        if ( $form->isSubmitted() )
        {
            if ($form->isValid())
            {
                $entityManager = $this->getDoctrine()->getManager();

                if ( $currentUser->getId() == $userEdit->getId() && $currentUser->getRole() != 'ROLE_ADMIN' ) {
                    $userEdit->setRole('ROLE_ADMIN');
                    $this->addFlash('error', 'Un Administrateur ne peut pas modifier son propre rôle');
                }

                $entityManager->persist($userEdit);
                $entityManager->flush();

                $this->addFlash('success', 'L\'utilisateur a été modifié');
                return $this->redirectToRoute('app_admin_user_index');
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }

        return $this->render(
          'admin/backoffice/user.html.twig',
          [
              'users' => $users,
              'userEdit' => $userEdit,
              'formEdit' => $form->createView(),
              'types' => $types
          ]
        );
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
