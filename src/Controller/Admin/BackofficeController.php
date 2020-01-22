<?php

namespace App\Controller\Admin;

use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BackofficeController
 * @package App\Controller\Admin
 * @Route("/backoffice")
 */
class BackofficeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(TypeRepository $typeRepository)
    {
        $types = $typeRepository->findBy(
            [],
            ['id'=>'DESC']
        );

        return $this->render('admin/backoffice/index.html.twig',
            [
                'types' => $types
            ]
        );
    }

    /**
     * @Route("/prefs")
     */
    public function prefs(TypeRepository $typeRepository)
    {
        $types = $typeRepository->findBy(
            [],
            ['id'=>'DESC']
        );

        return $this->render(
            'admin/backoffice/prefs.html.twig',
            [
                'types' => $types
            ]
        );
    }

}
