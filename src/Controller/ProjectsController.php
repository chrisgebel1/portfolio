<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProjectRepository;
use App\Repository\TypeRepository;
use App\Service\ProjectsHome;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProjectsController
 * @package App\Controller
 * @Route("/projects")
 */
class ProjectsController extends AbstractController
{
    /**
     * @Route("/{page}", defaults={"page"=1})
     * @param ProjectRepository $projectRepository
     * @param TypeRepository $typeRepository
     * @param CategoryRepository $categoryRepository
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allProjects(ProjectRepository $projectRepository, TypeRepository $typeRepository, CategoryRepository $categoryRepository, $page)
    {
        $limit = 6;
        $currentPage = $page;

        $projectsAll = $projectRepository->findBy(
            [],
            ['id'  => 'DESC']
        );

//        $projects = $projectRepository->findBy(
//            [],
//            ['id'  => 'DESC'],
//            $limit
//        );

        $types = $typeRepository->findBy(
            [],
            ['name' => 'ASC']
        );

        $categories = $categoryRepository->findBy(
            [],
            ['name' => 'ASC']
        );

        $nbPages = ceil(count($projectsAll) / $limit);

        if ( $page < 1 || !preg_match("/^\d+$/", $page) ) {
            return $this->redirectToRoute('app_projects_allprojects');
        } elseif ( $page >= 1 and $page <= $nbPages ) {
            $currentPage = $page;
        } elseif ( $page > $nbPages ) {
            $currentPage = $nbPages;
        }

        $projects = array_slice($projectsAll, (($currentPage-1)*$limit), $limit);

        return $this->render('projects/all-projects.html.twig',
            [
                'projects'      => $projects,
                'types'         => $types,
                'categories'    => $categories,
                'currentPage'   => $currentPage,
                'nbpages'       => $nbPages
            ]
        );
    }

    /**
     * @param ProjectRepository $projectRepository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/project/{id}", requirements={"{id}":"^\d+$"})
     */
    public function singleProject(ProjectRepository $projectRepository, $id)
    {
        $limit = 6;

        $project = $projectRepository->find($id);

        $moreProjects = $projectRepository->findBy(
            ['category'  => $project->getCategory()],
            ['id'  => 'DESC'],
            $limit
        );

        return $this->render('projects/single-project.html.twig',
            [
                'project'       => $project,
                'moreProjects'  => $moreProjects
            ]
        );
    }


}
