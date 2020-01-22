<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class InscriptionController
 * @package App\Controller
 */
class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder) : Response
    {
        // si l'utilisateur est déjà connecté on est redirigé vers la page d'accueil
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirectToRoute('app_index_index');
        }

        $user = new User();
        $form = $this->createForm(InscriptionType::class, $user, ["validation_groups"=>["Default", "registration"]]);
        $form->handleRequest($request);

        if ( $form->isSubmitted() )
        {
            if ( $form->isValid() )
            {
                $user->setPassword(
                  $passwordEncoder->encodePassword(
                      $user,
                      $user->getPlainPassword()
                  )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre compte est créé');
                return $this->redirectToRoute('app_inscription_login');
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }


        return $this->render(
            'inscription/inscription.html.twig',
            [
            'inscriptionForm' => $form->createView()
            ]
        );
    }

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @Route("/connexion")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // si l'utilisateur est déjà connecté on est redirigé vers la page d'accueil
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirectToRoute('app_index_index');
        }

        // traitement du formulaire par Security
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if (!empty($error))
        {
            $this->addFlash('error', 'Identification incorrect');
        }

        return $this->render(
            'inscription/login.html.twig',
            [
                'last_usernname' => $lastUsername
            ]
        );





    }

    /**
     * @Route("/deconnexion")
     */
    public function logout()
    {
        /*
            La méthode peut restée vide
            Il suffit que la route existe et qu'elle soit configurée dans security.yaml
            pour que la deconnexion soit gérée.
        */
    }

}
