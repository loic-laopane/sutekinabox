<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ProfileType;
use AppBundle\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function profileAction(Request $request, ObjectManager $em)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Profil mis à jour');
        }
        return $this->render('AppBundle:User:profile.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/users" , name="user_list")
     * @Security("has_role('ROLE_SUPERADMIN')")
     */
    public function listAction(ObjectManager $em)
    {
        $users = $em->getRepository('AppBundle:User')->findAll();
        return $this->render('AppBundle:User:index.html.twig', array(
            'users' => $users
        ));
    }

    /**
     * @Route("/users/new" , name="user_new")
     * @Security("has_role('ROLE_SUPERADMIN')")
     */
    public function newAction(Request $request, ObjectManager $em) {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Utilisateur créé');
            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }
        return $this->render('AppBundle:User:new.html.twig', array(
            'user' => $user,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/users/{id}/edit" , name="user_edit")
     * @Security("has_role('ROLE_SUPERADMIN')")
     */
    public function editAction(Request $request, User $user, ObjectManager $em) {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Utilisateur mis à jour');
        }
        return $this->render('AppBundle:User:edit.html.twig', array(
            'user' => $user,
            'form' => $form->createView()
        ));
    }

}
