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

        // sélectionner aléatoirement au minimum 3 projets de chaque type si possible
        // il faut un total de 6 projets à afficher

        $maxProjectsByType = 3; // nbre de projet à afficher par type
        $totalProjects = $maxProjectsByType * ( count($types) ); // nbre total de projets à afficher
        $selectProjects = []; // les projets à afficher
        $typProjects = []; // sélection des projets par type avec une limite

        if ( count($types) > 0 ) {
            foreach ( $types as $type ) {
                $p = $projectRepository->findBy(
                    [ 'type'    => $type->getId() ],
                    [ 'id'      => 'DESC'  ],
                    $totalProjects
                );
                $typProjects[$type->getId()] = $p;
            }

            while ( count($selectProjects) < $totalProjects ) {

                foreach ( $typProjects as $typProject ) { // $typVal tous les projets du type

                    $typProjectsById = []; // contient les projets du même type indexés par l'ID du projet
                    foreach ( $typProject as $project ) {
                        $typProjectsById[$project->getId()] = $project;
                    }

                    if ( count($typProject) > 0 ) {
                        $randomProject = array_slice($typProjectsById, rand(0, count($typProjectsById)-1), 1)[0]; // sélection aléatoire d'un projet
                        $exist = false;
                        foreach ($selectProjects as $p) { // on vérifie qu'on ne l'a pas déjà sélectionné
                            if ($randomProject->getId() == $p->getId()) {
                                $exist = true;
                                break;
                            }
                        }

                        if (!$exist) { // si on ne l'a pas déjà sélectionné on l'enregistre dans les projets à afficher
                            foreach($typProject as $project) {
                                if ($project->getId() == $randomProject->getId()) {
                                    $selectProjects[] = $randomProject;
                                    break;
                                }
                            }

                        }

                    }
                }
            }
            shuffle($selectProjects); // mélange l'ordre des projets sélectionnés
            $projects = $selectProjects;
        }



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
