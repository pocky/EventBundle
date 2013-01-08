<?php

/*
 * This file is part of the Blackengine package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Blackroom\Bundle\EventBundle\Model\Event;

use Doctrine\ODM\MongoDB\Mapping\Annotations\MappedSuperclass;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @MappedSuperClass
 */
abstract class Event
{
    protected $id;

    protected $attendees;

    protected $duration;

    protected $endDate;

    protected $location;

    protected $offers;

    protected $performers;

    protected $startDate;

    protected $subEvents;

    protected $superEvent;

    public function __construct()
    {
        $this->attendees    = new ArrayCollection();
        $this->subEvents    = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAttendees()
    {
        return $this->attendees;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setOffers($offers)
    {
        $this->offers = $offers;
    }

    public function getOffers()
    {
        return $this->offers;
    }

    public function setPerformers($performers)
    {
        $this->performers = $performers;
    }

    public function getPerformers()
    {
        return $this->performers;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setSubEvents($subEvents)
    {
        $this->subEvents = $subEvents;
    }

    public function getSubEvents()
    {
        return $this->subEvents;
    }

    public function setSuperEvent($superEvent)
    {
        $this->superEvent = $superEvent;
    }

    public function getSuperEvent()
    {
        return $this->superEvent;
    }
}