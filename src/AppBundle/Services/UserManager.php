<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 24/10/2017
 * Time: 11:07
 */
namespace AppBundle\Services;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $em;
    private $encoder;
    public $token;

    public function __construct(
        ObjectManager $em,
        UserPasswordEncoderInterface $encoder,
        TokenStorageInterface $token)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->token = $token;
    }

    public function register(User $user)
    {
        $encode_password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encode_password);
        $user->setRoles(['ROLE_USER']);

        $this->em->persist($user);
        $this->em->flush();
    }


}