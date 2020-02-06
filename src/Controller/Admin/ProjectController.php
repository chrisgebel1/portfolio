<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\CategoryRepository;
use App\Repository\ProjectRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ProjectController
 * @package App\Controller\Admin
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/type/{type}", defaults={"type"=null})
     */
    public function index(ProjectRepository $projectRepository,
                          TypeRepository $typeRepository, CategoryRepository $categoryRepository,
                          $type)
    {

        $types = $typeRepository->findBy(
            [],
            ['name'=>'ASC']
        );

        $categories = $categoryRepository->findBy(
            [],
            ['name'=>'ASC']
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


        return $this->render('admin/project.html.twig',
            [
                'projects'      => $projects,
                'types'         => $types,
                'categories'    => $categories,
                'nameType'      => $nameType
            ]
        );
    }


//    fonctions pour les projets \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @Route("/edit/{id}", defaults={"id"=null}, requirements={"id":"\d+"})
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        TypeRepository $typeRepository,
        $id
    )
    {
        $types = $typeRepository->findBy(
            [],
            ['id'=>'DESC']
        );

        $originalImage = null; // dans le cas de modification
        $originalImages = null; // dans le cas de modification
        dump('toto');

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
                $originalImages = $project->getFiles();
                // dump($originalImages);

                $originalImagesToBDD = [];
                foreach ($originalImages as $originalImage)
                {
                    array_push($originalImagesToBDD, new File($this->getParameter('upload_dir_portfolio') . $originalImage ));
                }
                // dump($originalImagesToBDD);die();
                // on sette l'image avec un objet file sur l'emplacement de l'image
                // pour le traitement par le formulaire
                $project->setFiles(
                    $originalImagesToBDD
                );
            }


        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ( $form->isSubmitted()  )
        {
            if ( $form->isValid() )
            {
                // dump($request);
                $images = $request->files->get('project')['files'];
                // dump($images);die();

                if ( count($images) > 0 )
                {
                    // array contenant le nom des fichiers uploadés dans la BDD
                    $filenames = [];

                    foreach ($images as $image)
                    {
                        // nom sous lequel l'image va être enregistrée
                        /**
                         * @var UploadedFile $image
                         */
                        $filename = uniqid() . '_' . strtoupper(str_replace(' ', '', $project->getName())) . '.' . $image->guessExtension();
                        // dump($image->getMimeType());

                        $acceptedImage = ['image/jpeg', 'image/png'];
                        // si le fichier est une image de type jpeg ou png
                        if ( in_array($image->getMimeType(), $acceptedImage) )
                        {
                            // déplace l'image uploadée
                            $image->move(
                            // vers le répertoire public/images
                            // cf config/services.yaml
                                $this->getParameter('upload_dir_portfolio'),
                                // nom du fichier
                                $filename
                            );

                            // ajoute le nom du fichier dans filenames
                            array_push($filenames, $filename);

                        } else {
                            $this->addFlash('error', 'Seul les fichiers jpeg et png sont acceptés comme image');
                            return $this->redirectToRoute('app_admin_project_editprojet');
                        }

                    }

                    // on sette l'attribut files du projet avec le nom du fichier
                    // pour l'enregistrement en BDD
                    $project->setFiles($filenames);

                    // en modification on ajoute la/les images dans la BDD
                    // s'il y en a de nouvelle(s)
                    if ( !is_null($originalImages) )
                    {
                        foreach ( $filenames as $filename)
                        {
                            array_push($originalImages, $filename);
                        }
                        $project->setFiles($originalImages);
                    }


                } else {
                    // en modification, sans upload, on sette l'image
                    // avec le nom de l'ancienne
                    $project->setFiles($originalImages);
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
            'admin/editproject.html.twig',
            [
                'form' => $form->createView(),
                'original_image' => $originalImages,
                'types' => $types,
                'project' => $project
            ]
        );
    }

    /**
     * @Route("/delete/{id}", requirements={"id":"\d+"})
     */
    public function deleteProjet(
        EntityManagerInterface $entityManager,
        Project $project
    )
    {
        // si le projet supprimé a une image
        if ( !is_null($project->getFiles()) )
        {
//            $file = $this->getParameter('upload_dir_portfolio') . $project->getFiles();
            $filesDelete = $project->getFiles();
//            dump($filesDelete);die();

            foreach ($filesDelete as $file)
            {
                $file = $this->getParameter('upload_dir_portfolio') . $file;

                if ( file_exists($file) )
                {
                    unlink($file);
                }
            }
        }

        // suppression du projet
        $entityManager->remove($project);
        $entityManager->flush();

        $this->addFlash('success', 'Le projet a été supprimé');
        return $this->redirectToRoute('app_admin_project_index');
    }




    /**
     * @Route("/ajaxdeleteoneimageproject/{id}/img/{img}", requirements={"id":"\d+", "img":"\d+"})
     */
    public function ajaxDeleteOneImageProject(
        EntityManagerInterface $entityManager,
        ProjectRepository $projectRepository,
        $id, $img): Response
    {
        $project = $projectRepository->find($id);
        // dump($project);die();

        if ( is_null($project) ) // si l'ID ne correspond pas à un projet existant
        {
            return $this->json([
                'status'=> 403,
                'message'=>'Projet inexistant',
                'projet' => $id,
                'image' => $img
                ],403);
        }

        $images = $project->getFiles();
        // dump($images);

        if ( is_null($images[$img]) ) // si l'image n'existe pas
        {
            return $this->json([
                'status'=> 403,
                'message'=>'Image inexistante',
                'projet' => $id,
                'image' => $img
            ], 403
            );
        } else // donc le projet et l'image à supprimée existent
        {
            // suppression du fichier de l'image
                $file = $this->getParameter('upload_dir_portfolio') . $images[$img];
                if ( file_exists($file) )
                {
                    unlink($file);
                }

            array_splice($images, $img, 1);
            //dump($images);die();

            $project->setFiles(
                $images
            );

            $entityManager->persist($project);
            $entityManager->flush();

            return $this->json([
                'status'=> 200,
                'message'=>'Image supprimée',
                'projet' => $id,
                'image' => $img
            ],200);
        }

    }
}
