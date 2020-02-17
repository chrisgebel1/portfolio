<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProjectRepository;
use App\Repository\TypeRepository;
use App\Service\ProjectsHome;
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
     * @param ProjectRepository $projectRepository
     * @param TypeRepository $typeRepository
     * @param CategoryRepository $categoryRepository
     * @param ProjectsHome $projectsHome
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ProjectRepository $projectRepository, TypeRepository $typeRepository, CategoryRepository $categoryRepository, ProjectsHome $projectsHome)
    {
//        $projects = $projectRepository->findAll();
        $types = $typeRepository->findAll();
        $categories = $categoryRepository->findAll();
//        $categories = $categoryRepository->findBy(
//            [],
//            ['name' => 'ASC']
//        );

        /* voir méthode de la classe ProjectsHome dans service */
        $projects = $projectsHome->projectsHome($projectRepository, $typeRepository);

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
     * @Route("/mix-projects")
     */
    public function mixProjects(ProjectRepository $projectRepository, TypeRepository $typeRepository, ProjectsHome $projectsHome)
    {
        $response = new JsonResponse();
        $types = $typeRepository->findAll();

        /* voir méthode de la classe ProjectsHome dans service */
        $projects = $projectsHome->projectsHome($projectRepository, $typeRepository);

        if ( count($projects) > 0 ) {
            $response->setData(
                [
                    'status'    => 'success',
                    'message'   => 'Projets sélectionnés',
                    'html'      => $this->renderView('index/_projects-homepage.html.twig',
                        [
                            'projects'  => $projects,
                            'types'     => $types
                        ]
                    )
                ]
            );
        } else {
            $response->setData(
                [
                    'status'    => 'error',
                    'message'   => 'Aucun projet sélectionné'
                ]
            );
        }

        return $response;
    }





    /**
     * @param Request $request
     * @param ProjectRepository $projectRepository
     * @Route("/search-result")
     * @return JsonResponse
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
