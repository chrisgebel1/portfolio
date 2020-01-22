<?php
// source https://rihards.com/2018/symfony-login-event-listener/

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class LoginListener
 * @package App\EventListener
 */
class LoginListener
{
    private $em;

    /**
     * LoginListener constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param InteractiveLoginEvent $event
     * @throws \Exception
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        // pour avoir l'entité User
        $user = $event->getAuthenticationToken()->getUser();

        // set la propriété lastLogin
        $user->setLastLogin(new \DateTime());

        // persist en BDD
        $this->em->persist($user);
        $this->em->flush();
    }
}