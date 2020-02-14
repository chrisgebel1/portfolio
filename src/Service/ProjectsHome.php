<?php
/* https://symfonycasts.com/screencast/symfony-fundamentals/create-service */

namespace App\Service;


use App\Repository\ProjectRepository;
use App\Repository\TypeRepository;

class ProjectsHome
{
    public function projectsHome(ProjectRepository $projectRepository, TypeRepository $typeRepository)
    {
        $types = $typeRepository->findAll();

        // sélectionner aléatoirement au minimum 3 projets de chaque type si possible
        // il faut un total de 6 projets à afficher

        $maxProjectsByType = 3; // nbre de projet à afficher par type
        $totalProjects = $maxProjectsByType * ( count($types) ); // nbre total de projets à afficher
        $selectProjects = []; // les projets à afficher
        $typProjects = []; // sélection des projets par type avec une limite

        if ( count($types) > 0 ) {
            foreach ( $types as $type ) {
                $p = $projectRepository->findBy(
                    [ 'type'    => $type->getId() ]
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
                            if ( $randomProject->getId() == $p->getId() || $randomProject->getName() == $p->getName() ) {
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

//            $toto=[];
//            foreach ( $projects as $project ) {
//                $toto[] = $project->getName();
//            }
//            dump($toto);die();
        }

        return $selectProjects;
    }
}