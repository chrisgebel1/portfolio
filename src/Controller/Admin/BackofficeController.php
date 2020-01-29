<?php

namespace App\Controller\Admin;

use App\Repository\ProjectRepository;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BackofficeController
 * @package App\Controller\Admin
 * @Route("/")
 */
class BackofficeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(
        TypeRepository $typeRepository,
        UserRepository $userRepository,
        ProjectRepository $projectRepository)
    {
        // pour la navbar BO
        $types = $typeRepository->findBy(
            [],
            ['id'=>'DESC']
        );

        // pour les users
        $users = $userRepository->findAll();
        $roles = [];

        foreach ($users as $user )
        {
            $role = $user->getRole();
            if ( !in_array($role, $roles) )
            {
                array_push($roles, $user->getRole());
            }
        }

        // pour les projets
        $projects = $projectRepository->findAll();

        return $this->render('admin/index.html.twig',
            [
                'types' => $types,
                'projects' => $projects,
                'roles' => $roles,
                'users' => $users,
            ]
        );
    }

    /**
     * @Route("/prefs")
     */
    public function prefs(TypeRepository $typeRepository)
    {
        $types = $typeRepository->findBy(
            [],
            ['id'=>'DESC']
        );

        return $this->render(
            'admin/prefs.html.twig',
            [
                'types' => $types
            ]
        );
    }

}
