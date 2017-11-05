<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 05/11/2017
 * Time: 11:25
 */

namespace AppBundle\Event\Listener;


use AppBundle\Entity\Box;
use AppBundle\Event\BoxEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Templating\EngineInterface;

class BoxChangeStateListener
{
    private $mailer;
    private $template;
    private $session;
    private $sender;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $template, SessionInterface $session, $sender)
    {
        $this->mailer = $mailer;
        $this->template = $template;
        $this->session = $session;
        $this->sender = $sender;
    }

    public function onBoxChangeState(BoxEvent $event)
    {
        $box = $event->getBox();

        $message = 'La box '.$box->getName().' a changé d\'état : '.$box->getState();
        //Envoi d'une notif
        $this->flash($box, $message);

        $this->sendMail($box);
    }


    private function sendMail(Box $box)
    {
        $user = $box->getCreator();
        $message = new \Swift_Message('Etat mis à jour');
        $message->setFrom($this->sender)
                ->setTo($user->getEmail())
                ->setBody($this->template->render('AppBundle:Mail:mail.state.html.twig', array(
                    'box' => $box
                )),
                  'text/html');
        $this->mailer->send($message);
    }

    private function flash($message, $type='success')
    {
        $this->session->getFlashBag()->add($type, $message);
    }

}