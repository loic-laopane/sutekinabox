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
use AppBundle\Services\BoxManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Workflow\Workflow;

class BoxChangeStateListener
{
    private $mailer;
    private $template;
    private $session;
    private $translator;
    private $wf;
    private $manager;
    private $sender;

    public function __construct(\Swift_Mailer $mailer,
                                EngineInterface $template,
                                SessionInterface $session,
                                TranslatorInterface $translator,
                                Workflow $wf,
                                BoxManager $manager,
                                $sender)
    {
        $this->mailer = $mailer;
        $this->template = $template;
        $this->session = $session;
        $this->translator = $translator;
        $this->wf = $wf;
        $this->manager = $manager;
        $this->sender = $sender;
    }

    /**
     * @param BoxEvent $event
     */
    public function onBoxChangeState(BoxEvent $event)
    {
        $box = $event->getBox();

        $message = 'La box '.$box->getName().' a changé d\'état : '.$this->translator->trans($box->getState());
        //Envoi d'une notif
        $this->flash($message);

        $this->sendMail($box);
    }

    /**
     * @param BoxEvent $event
     */
    public function onProductChangeState(BoxEvent $event)
    {
        $box = $event->getBox();
        $boxProducts = $box->getBoxProduct();
        foreach($boxProducts as $boxProduct)
        {
            if($boxProduct->getState() === 'cancelled')
            {
                //Update Box to unvailable
                $this->manager->changeState($box);
                return;
            }
            if($boxProduct->getState() !== 'ready')
            {
                return;
            }
        }
        $this->manager->changeState($box);
    }

    /**
     * @param Box $box
     */
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

    /**
     * @param $message
     * @param string $type
     */
    private function flash($message, $type='success')
    {
        $this->session->getFlashBag()->add($type, $message);
    }

}