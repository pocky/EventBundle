<?php

/*
 * This file is part of the Blackengine package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Black\Bundle\EventBundle\Model;
use Black\Bundle\EngineBundle\Model\PersonInterface;

interface EventInterface
{
   function getId();
   
   function setDuration($duration);
   function getDuration();
   
   function setEndDate($endDate);
   function getEndDate();
   
   function setLocation($location);
   function getLocation();
   
   function setOffers($offers);
   function getOffers();
   
   function setStartDate($startDate);
   function getStartDate();
   
   function setSubEvent(EventInterface $subEvent);
   function setSubEvents($subEvents);
   function getSubEvents();
   
   function addSubEvent(EventInterface $subEvent);
   function removeSubEvent(EventInterface $subEvent);
   
   function setSuperEvent(EventInterface $superEvent);
   function getSuperEvent();
   
   function removeAttendee(PersonInterface $attendee);
}