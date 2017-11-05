<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 05/11/2017
 * Time: 11:24
 */

namespace AppBundle\Event;


use AppBundle\Entity\Box;
use Symfony\Component\EventDispatcher\Event;

class BoxEvent extends Event
{
    const STATE = 'box.state';
    const PRODUCT = 'box_product.state';
    const EVENT = 'box.event';

    private $box;

    public function __construct(Box $box)
    {
        $this->box = $box;
    }

    public function getBox()
    {
        return $this->box;
    }
}