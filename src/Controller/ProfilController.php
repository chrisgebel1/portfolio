<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Form\ModifUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil")
     */
    public function index(Request $request,
        UserPasswordEncoderInterface $passwordEncoder)
    {
        // si l'utilisateur n'est pas connecté on est redirigé vers la page login
        if (!$this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirectToRoute('app_inscription_login');
        }

        $user = $this->getUser();


        // modif des infos de l'utilisateur \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        // modif des infos de l'utilisateur \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

        $formInfos = $this->createForm(ModifUserType::class, $user);
        $formInfos->handleRequest($request);

        if ( $formInfos->isSubmitted() )
        {
            if ( $formInfos->isValid() ) {
                // dump($request);die();

                $em = $this->getDoctrine()->getManager();

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'L\'ensemble de vos information a  bien été modifié');
                return $this->redirectToRoute('app_profil_index');
            } else {
                $this->addFlash('error', 'Le formulaire comporte n\'est pas correct');
            }
        }

        // FIN modif des infos de l'utilisateur \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        // FIN modif des infos de l'utilisateur \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


        // modif du mot de passe \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        // modif du mot de passe \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        $formPassword = $this->createForm(ChangePasswordType::class, $user);
        $formPassword->handleRequest($request);

        if ( $formPassword->isSubmitted() )
        {
            if ( $formPassword->isValid() )
            {
                // dump($request);die();

                $em = $this->getDoctrine()->getManager();

                $oldPassword = $request->request->get('change_password')['oldPassword'];
                $newPassword = $request->request->get('change_password')['plainpassword']['first'];

                // si le nouveau mot de passe est vide
                if ( $newPassword == "" )
                {
                    $this->addFlash('error', 'Le nouveau mot de passe ne peut être vide');
                    return $this->redirectToRoute('app_profil_index');
                }

                // si l'ancien MDP est bon
                if ( $passwordEncoder->isPasswordValid($user, $oldPassword) )
                {
                    $newEncodedPassword = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($newEncodedPassword);

                    $em->persist($user);
                    $em->flush();

                    $this->addFlash('success', 'Votre mot de passe a bien été modifié');

                    return $this->redirectToRoute('app_profil_index');
                } else {
                    $this->addFlash('error', 'L\'ancien mot de passe n\'est pas correct');
                    return $this->redirectToRoute('app_profil_index');
                }
            }
        }
        // FIN modif du mot de passe \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        // FIN modif du mot de passe \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


        return $this->render(
            'profil/index.html.twig',
            [
                'formInfos' => $formInfos->createView(),
                'formPassword' => $formPassword->createView()
            ]
        );
    }



}
