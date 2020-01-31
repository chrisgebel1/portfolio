<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 * @Route("/")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(ProjectRepository $projectRepository, TypeRepository $typeRepository)
    {
        $projects = $projectRepository->findAll();
        $types = $typeRepository->findAll();

//        dump($types);

        return $this->render(
            'index/index.html.twig',
            [
                'projects' => $projects,
                'types' => $types
            ]
        );
    }
}
