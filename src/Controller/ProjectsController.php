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
     * @Route("/")
     * @param ProjectRepository $projectRepository
     * @param TypeRepository $typeRepository
     * @param CategoryRepository $categoryRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allProjects(
        ProjectRepository $projectRepository,
        TypeRepository $typeRepository,
        CategoryRepository $categoryRepository
        )
    {
        $limit = 6;
        $page = "1";
        $currentPage = $page;
        $typeSelected = null;

        $projectsAll = $projectRepository->findBy(
            [],
            ['id'  => 'DESC']
        );

        $types = $typeRepository->findBy(
            [],
            ['name' => 'ASC']
        );

        $categories = $categoryRepository->findBy(
            [],
            ['name' => 'ASC']
        );


//        if ( isset($_GET['type']) ) {
//            $type = $_GET['type'];
//            if ( !empty($type) && preg_match("/^\d+$/", $type) ) {
//                $typeSelected = $typeRepository->find($type);
//                $projectsAll = $projectRepository->findBy(
//                    [ 'type'    => $typeSelected ],
//                    [ 'name'    => 'ASC' ]
//                );
//            } else {
//                return $this->redirectToRoute('app_projects_allprojects');
//            }
//        }

//        if ( isset($_GET['category']) ) {
//            $catGet = $_GET['category'];
//            $d = 'and';
//
//            if ( preg_match("/^(\w+((and\w+)+))$/", $catGet) )
//            {
//
//                if ( strpos($catGet, $d) )
//                {
//                    $tab = explode($d, $catGet, count($categories));
//                    $projects = [];
//
//                    foreach ($tab as $nameCat) {
//                        $idCat = $categoryRepository->findBy(
//                            ['name'   => $nameCat]
//                        );
//
//                        $project = $projectRepository->findBy(
//                            ['category'=>$idCat],
//                            ['category'=>'DESC']
//                        );
//
//                        if (count($project)>0) {
//                            foreach ($project as $p){
//                                array_push($projects, $p);
//                            }
//                        } else {
//                            // $this->addFlash('error', 'La catégorie ' . $idCat. ' n\'existe pas');
//                            return $this->redirectToRoute('app_projects_allprojects');
//                        }
//                    }
//
//                } else {
//                    // $this->addFlash('error', 'Une erreur dans l\'url a été détectée');
//                    return $this->redirectToRoute('app_projects_allprojects');
//                }
//
//            } elseif ( preg_match("/^\w+$/", $catGet) ) {
//                $idCat = $categoryRepository->findBy(
//                    ['name'   => $catGet]
//                );
//
//                $projects = $projectRepository->findBy(
//                    ['category'=>$idCat],
//                    ['category'=>'DESC']
//                );
//
//                if ( count($projects) == 0 ) {
//                    // $this->addFlash('error', 'La catégorie ' . $cat. ' n\'existe pas');
//                    return $this->redirectToRoute('app_projects_allprojects');
//                }
//
//            } else {
//                // $this->addFlash('error', 'Une erreur dans l\'url a été détectée');
//                return $this->redirectToRoute('app_projects_allprojects');
//            }
//            $projectsAll = $projects;
//        }


        $nbPages = ceil(count($projectsAll) / $limit);

        if ( isset($_GET['page']) ) {
            $page = $_GET['page'];
            if ( !empty($page) && preg_match("/^\d+$/", $page) ) {
                if ( $page < 1 ) {
                    return $this->redirectToRoute('app_projects_allprojects');
                } elseif ( $page >= 1 and $page <= $nbPages ) {
                    $currentPage = $page;
                } elseif ( $page > $nbPages ) {
                    $currentPage = $nbPages;
                }
            }
        }

        $projects = array_slice($projectsAll, (($currentPage-1)*$limit), $limit);

        return $this->render('projects/all-projects.html.twig',
            [
                'projects'      => $projects,
                'types'         => $types,
                'categories'    => $categories,
                'currentPage'   => $currentPage,
                'nbpages'       => $nbPages,
            ]
        );
    }


    /**
     * @Route("/type/{type}", requirements={"type":"^\d+$"})
     */
    public function indexByType(ProjectRepository $projectRepository,
        TypeRepository $typeRepository,
        CategoryRepository $categoryRepository,
        $type)
    {
        $limit = 6;
        $page = 1;
        $currentPage = $page;
        $typeSelected = null;

        if ( is_null($type) ) {
            return $this->redirectToRoute('app_projects_allprojects');
        }

        $types = $typeRepository->findBy(
            [],
            ['name'=> 'ASC']
        );

        $categories = $categoryRepository->findBy(
            [],
            ['name'=>'ASC']
        );

        $nameType = $typeRepository->find($type);

        if ( !$nameType ) {
            $this->addFlash('error', 'Le type ' . $type. ' n\'existe pas');
            return $this->redirectToRoute('app_projects_allprojects');
        }

        $projects = $projectRepository->findBy(
            ['type'=>$type],
            ['type'=>'DESC']
        );

        $nbPages = ceil(count($projects) / $limit);

        if ( isset($_GET['page']) ) {
            $page = $_GET['page'];
            if ( !empty($page) && preg_match("/^\d+$/", $page) ) {
                if ( $page < 1 ) {
                    return $this->redirectToRoute('app_projects_allprojects');
                } elseif ( $page >= 1 and $page <= $nbPages ) {
                    $currentPage = $page;
                } elseif ( $page > $nbPages ) {
                    $currentPage = $nbPages;
                }
            }
        }

        $projects = array_slice($projects, (($currentPage-1)*$limit), $limit);

        return $this->render('projects/all-projects.html.twig',
            [
                'projects'      => $projects,
                'types'         => $types,
                'categories'    => $categories,
                'currentPage'   => $currentPage,
                'nbpages'       => $nbPages,
            ]
        );
    }


    /**
     * @Route("/category/{cat}")
     */
    public function indexByCategory(ProjectRepository $projectRepository,
                                    TypeRepository $typeRepository,
                                    CategoryRepository $categoryRepository,
                                    $cat)
    {
        $limit = 6;
        $page = "1";
        $currentPage = $page;
        $projects = [];
        $msgError = null;

        if ( is_null($cat) ) {
            return $this->redirectToRoute('app_projects_allprojects');
        } else {
            $cat = explode(',', $cat);
        }

        $types = $typeRepository->findBy(
            [],
            ['name'=> 'ASC']
        );

        $categories = $categoryRepository->findBy(
            [],
            ['name'=>'ASC']
        );

        if ( is_array($cat) ) {
            foreach ( $cat as $idCat) {
                $project = $projectRepository->findBy(
                    ['category'=>$idCat],
                    ['category'=>'DESC']
                );

                if (count($project)>0) {
                    foreach ($project as $p){
                        array_push($projects, $p);
                    }
                } else {
                    $msgError = 'La catégorie ' . $idCat. ' n\'existe pas';
                    $this->addFlash('error', $msgError);
                }
            }

        } else {
            $msgError = 'Une erreur dans l\'url a été détectée';
            $this->addFlash('error', $msgError);
        }

        if ( !is_null($msgError) ) {
            return $this->redirectToRoute('app_projects_allprojects');
        }

        $nbPages = ceil(count($projects) / $limit);

        if ( isset($_GET['page']) ) {
            $page = $_GET['page'];
            if ( !empty($page) && preg_match("/^\d+$/", $page) ) {
                if ( $page < 1 ) {
                    return $this->redirectToRoute('app_projects_allprojects');
                } elseif ( $page >= 1 and $page <= $nbPages ) {
                    $currentPage = $page;
                } elseif ( $page > $nbPages ) {
                    $currentPage = $nbPages;
                }
            }
        }

        $projects = array_slice($projects, (($currentPage-1)*$limit), $limit);

        return $this->render('projects/all-projects.html.twig',
            [
                'projects'      => $projects,
                'types'         => $types,
                'categories'    => $categories,
                'currentPage'   => $currentPage,
                'nbpages'       => $nbPages,
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
