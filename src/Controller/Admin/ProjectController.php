<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Project;
use App\Entity\Type;
use App\Form\CategoryProjectType;
use App\Form\ProjectType;
use App\Form\TypeProjectType;
use App\Repository\CategoryRepository;
use App\Repository\ProjectRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProjectController
 * @package App\Controller\Admin
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/{type}", defaults={"type"=null})
     */
    public function index(ProjectRepository $projectRepository,
                          TypeRepository $typeRepository,
                          $type)
    {

        $types = $typeRepository->findBy(
            [],
            ['id'=>'DESC']
        );


        if ( is_null($type) )
        {
            $nameType = '';
            $projects = $projectRepository->findAll();
        } else
        {
            $nameType = $typeRepository->find($type);
            $projects = $projectRepository->findBy(
                ['type'=>$type],
                ['type'=>'DESC']
            );
        }


        return $this->render('admin/backoffice/project.html.twig',
            [
                'projects' => $projects,
                'types' => $types,
                'nameType' => $nameType
            ]
        );
    }


//    funtions pour les projets \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @Route("/project/edit/{id}", defaults={"id"=null}, requirements={"id":"\d+"})
     */
    public function editProjet(
        Request $request,
        EntityManagerInterface $entityManager,
        ProjectRepository $projectRepository,
        TypeRepository $typeRepository,
        $id
    )
    {
        $types = $typeRepository->findBy(
            [],
            ['id'=>'DESC']
        );

        $originalImage = null; // dans le cas de modification

        if ( is_null($id) ) // création
        {
            $project = new Project();
        } else // modification
        {
            $project = $entityManager->find(Project::class, $id);

            if ( is_null($project) ) // si l'ID ne correspond pas à un projet existant
            {
                throw new NotFoundHttpException();
            }

            if ( !is_null($project->getFiles()) ) // si le projet à une image
            {
                // nom du fichier venant de la BDD
                $originalImage = $project->getFiles();

                // on sette l'image avec un objet file sur l'emplacement de l'image
                // pour le traitement par le formulaire
                $project->setFiles(
                    new File($this->getParameter('upload_dir_portfolio') . $originalImage )
                );
            }


        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ( $form->isSubmitted()  )
        {
            if ( $form->isValid() )
            {
                // dump($request);die();
                /** @var UploadedFile $image */
                $image = $project->getFiles();

                if ( !is_null($image) )
                {
                    // nom sous lequel l'image va être enregistrée
                    $filename = uniqid() . '_' . strtoupper(str_replace(' ', '', $project->getName())) . '.' . $image->guessExtension();

                    // déplace l'image uploadée
                    $image->move(
                    // vers le répertoire public/images
                    // cf config/services.yaml
                        $this->getParameter('upload_dir_portfolio'),
                        // nom du fichier
                        $filename
                    );

                    // on sette l'attribut files du projet avec le nom du fichier
                    // pour l'enregistrement en BDD
                    $project->setFiles($filename);

                    // en modification on supprime l'ancienne image
                    // s'il y a une nouvelle
                    if ( !is_null($originalImage) )
                    {
                        unlink($this->getParameter('upload_dir_portfolio') . $originalImage );
                    }
                } else {
                    // en modification, sans upload, on sette l'image
                    // avec le nom de l'ancienne
                    $project->setFiles($originalImage);
                }

                $entityManager->persist($project);
                $entityManager->flush();

                $this->addFlash('success', 'Le projet a bien été enregistré');
                return $this->redirectToRoute('app_admin_project_index');

            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }

        return $this->render(
            'admin/backoffice/editproject.html.twig',
            [
                'form' => $form->createView(),
                'original_image' => $originalImage,
                'types' => $types
            ]
        );
    }

    /**
     * @Route("/project/delete/{id}", requirements={"id":"\d+"})
     */
    public function deleteProjet(
        EntityManagerInterface $entityManager,
        Project $project
    )
    {
        // si le projet supprimé a une image
        if ( !is_null($project->getFiles()) )
        {
            $file = $this->getParameter('upload_dir_portfolio') . $project->getFiles();

            if ( file_exists($file) )
            {
                unlink($file);
            }
        }

        // suppression du projet
        $entityManager->remove($project);
        $entityManager->flush();

        $this->addFlash('success', 'Le projet a été supprimé');
        return $this->redirectToRoute('app_admin_project_index');
    }
}
