<?php
// source https://stackoverflow.com/questions/48909964/symfony-inject-entitymanager-in-logoutlistener

namespace App\EventListener;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class LogoutListener implements LogoutHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        // TODO: Implement logout() method.
        // pour avoir l'entité User
        $user = $token->getUser();

        // set la propriété lastLogin
        $user->setLastLogOut(new \DateTime());

        // persist en BDD
        $this->em->persist($user);
        $this->em->flush();
    }
}