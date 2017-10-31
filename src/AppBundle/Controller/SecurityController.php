<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\LoginType;
use AppBundle\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package AppBundle\Controller
 * @Route("/login")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {

        $errors = $authenticationUtils->getLastAuthenticationError();
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            return $this->redirectToRoute('admin_dashboard');
        }
        return $this->render('AppBundle:Security:login.html.twig', array(
            'form' => $form->createView(),
            'errors' => $errors,
            'active_login' => true
        ));
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $userManager = $this->get('app.user_manager');
            $userManager->register($user);

            $request->getSession()->getFlashBag()->add('success', 'Enregistrement ok');
            return $this->redirectToRoute('login');
        }
        return $this->render('AppBundle:Security:register.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        return $this->render('AppBundle:Login:logout.html.twig', array(
            // ...
        ));
    }

}
