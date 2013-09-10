<?php
/*
 * This file is part of the Blackengine package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Black\Bundle\EventBundle\Form\ChoiceList;

use Black\Bundle\EventBundle\Model\EventManagerInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\LazyChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

/**
 * EventList
 */
class EventList extends LazyChoiceList
{
    /**
     * @var EventManagerInterface
     */
    private $manager;

    /**
     * @param EventManagerInterface $manager
     */
    public function __construct(EventManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return \Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface|SimpleChoiceList
     */
    protected function loadChoiceList()
    {
        $choices    = array();
        $events    = $this->getEvents();

        foreach ($events as $event) {
            $choices += array($event->getId() => $event->getName());
        }
        $choiceList = new SimpleChoiceList($choices);

        return $choiceList;
    }

    /**
     * @return mixed
     */
    protected function getEvents()
    {
        $events = $this->manager->findEvents();

        return $events;
    }
}
