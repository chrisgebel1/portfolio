<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Type;
use App\Form\CategoryProjectType;
use App\Form\TypeProjectType;
use App\Repository\CategoryRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SectionController
 * @package App\Controller\Admin
 * @Route("/section")
 */
class SectionController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function section(CategoryRepository $categoryRepository, TypeRepository $typeRepository)
    {
//        $categories = $categoryRepository->findAll();
        $categories = $categoryRepository->findBy(
            [],
            ['name'=>'ASC']
        );

        $types = $typeRepository->findBy(
            [],
            ['id'=>'ASC']
        );

        return $this->render(
            'admin/section.html.twig',
            [
                'categories' => $categories,
                'types' => $types
            ]
        );
    }

    /**
     * @Route("/type/{id}", defaults={"id"=null}, requirements={"id": "\d+"})
     */
    public function editType(
        Request $request,
        EntityManagerInterface $entityManager,
        TypeRepository $typeRepository,
        $id)
    {
        $types = $typeRepository->findBy(
            [],
            ['id'=>'DESC']
        );

        if ( is_null($id) ) // création
        {
            $type = new Type();
        } else // modification
        {
            $type = $entityManager->find(Type::class, $id);

            if ( is_null($type) )
            {
                throw new NotFoundHttpException();
            }
        }

        $form = $this->createForm(TypeProjectType::class, $type);
        $form->handleRequest($request);

        if ( $form->isSubmitted() )
        {
            if ( $form->isValid() )
            {
                $entityManager->persist($type);
                $entityManager->flush();

                $this->addFlash('success', 'Le type a été enregistré');
                return $this->redirectToRoute('app_admin_section_section');
            } else
            {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }


        return $this->render(
            'admin/type.html.twig',
            [
                'form' => $form->createView(),
                'types' => $types
            ]
        );
    }

    /**
     * @Route("/type/delete/{id}", requirements={"id": "\d+"})
     */
    public function deleteType(
        EntityManagerInterface $entityManager,
        $id)
    {

        $type = $entityManager->find(Type::class, $id);

        if ( is_null($type) )
        {
            // throw new NotFoundHttpException();
            $this->addFlash('error', 'Ce type n\'existe pas');
            return $this->redirectToRoute('app_admin_section_section');
        }

        $entityManager->remove($type);
        $entityManager->flush();

        $this->addFlash('success', 'Le type "' . $type->getName() . '" a été supprimé');
        return $this->redirectToRoute('app_admin_section_section');
    }

    /**
     * @Route("/category/{id}", defaults={"id"=null}, requirements={"id": "\d+"})
     */
    public function editCategory(
        Request $request,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository,
        TypeRepository $typeRepository,
        $id)
    {
        $types = $typeRepository->findBy(
            [],
            ['id'=>'DESC']
        );

        if ( is_null($id) ) // création
        {
            $category = new Category();
        } else // modification
        {
            $category = $entityManager->find(Category::class, $id);

            if ( is_null($category) )
            {
                throw new NotFoundHttpException();
            }
        }

        $form = $this->createForm(CategoryProjectType::class, $category);
        $form->handleRequest($request);

        if ( $form->isSubmitted() )
        {
            if ( $form->isValid() )
            {
                // dump($request);die();
                $typeCategory = $request->request->get('category_project')['type'];

                if ( $typeCategory == "" )
                {
                    $this->addFlash('error', 'Un type est obligatoire');
                    return $this->redirectToRoute('app_admin_section_section');
                }

                $entityManager->persist($category);
                $entityManager->flush();

                $this->addFlash('success', 'Catégorie a été enregistrée');
                return $this->redirectToRoute('app_admin_section_section');
            } else
            {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }

        return $this->render(
            'admin/category.html.twig',
            [
                'form' => $form->createView(),
                'types' => $types
            ]
        );
    }


    /**
     * @Route("/category/delete/{id}", requirements={"id":"\d+"})
     */
    public function deleteCategory(
        EntityManagerInterface $entityManager,
        $id)
    {
        $category = $entityManager->find(Category::class, $id);

        if ( is_null($category) )
        {
            // throw new NotFoundHttpException();
            $this->addFlash('error', 'Cette catégorie n\'existe pas');
            return $this->redirectToRoute('app_admin_section_section');
        }

        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success', 'Le type "' . $category->getName() . '" a été supprimé');
        return $this->redirectToRoute('app_admin_section_section');
    }

}
