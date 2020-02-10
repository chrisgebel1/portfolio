<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProjectRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(ProjectRepository $projectRepository, TypeRepository $typeRepository, CategoryRepository $categoryRepository)
    {
        $projects = $projectRepository->findAll();
        $types = $typeRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render(
            'index/index.html.twig',
            [
                'projects'      => $projects,
                'types'         => $types,
                'categories'    => $categories
            ]
        );
    }

    /**
     * @param Request $request
     * @param ProjectRepository $projectRepository
     * @Route("/search-result")
     */
    public function searchProject(Request $request, ProjectRepository $projectRepository)
    {
        $response = [];

        if ( $request->query->has('term') )
        {
            $value = $request->query->get('term');
            $projects = $projectRepository->search($value);

            foreach ( $projects as $project )
            {
                $response[] = [
                    'id'            => $project->getId(),
                    'name'          => $project->getName(),
                    'type'          => $project->getType()->getName(),
                    'typeID'        => $project->getType()->getId(),
                    'category'      => $project->getCategory()->getName(),
                    'categoryID'    => $project->getCategory()->getId(),
                    'info_short'    => $project->getInfoShort(),
                    'info_long'     => $project->getInfoLong(),
                    'images'        => $project->getFiles()
                ];
            }
        }
        return new JsonResponse($response);
    }
}
