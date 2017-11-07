<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/11/2017
 * Time: 15:43
 */

namespace AppBundle\Event\Subscriber;



use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class BoxSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents() {
        return [
            'workflow.box.transition.request' => 'boxRequested'
        ];
    }

    public function boxRequested(Event $event)
    {
        $entity = $event->getSubject();
        dump($entity);
    }
}