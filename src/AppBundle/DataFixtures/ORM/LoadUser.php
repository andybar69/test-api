<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;


class LoadUser implements ORMFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $username = 'API';
        $user = new User();
        $user->setUsername($username);
        $password = strtolower($username);
        $password = $this->encoder->encodePassword($user, $password);
        $user->setPassword($password);
        $user->setSalt('');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();
    }

}