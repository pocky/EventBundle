<?php

/*
 * This file is part of the Blackengine package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Black\Bundle\EventBundle\Entity;

use Black\Bundle\EngineBundle\Entity\BaseManager;

class EventManager extends BaseManager
{
    public function findEventsForPerson($id)
    {
        return $this->getRepository()
              ->getEventsForPerson($id);
    }
}
