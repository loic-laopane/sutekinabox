<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class AdminControllerController
 * @package AppBundle\Controller
 * @Route("/admin")
 *
 */
class AdminController extends Controller
{
    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function dashboardAction()
    {
        return $this->render('AppBundle:Admin:dashboard.html.twig', array(
            // ...
        ));
    }

    public function notificationAction()
    {

        $notifications = $this->getDoctrine()->getRepository('AppBundle:Notification')->findLastNotification($this->getUser());
        return $this->render('AppBundle:Menu:notif.html.twig', array(
            'notifications' => $notifications
        ));
    }

}
