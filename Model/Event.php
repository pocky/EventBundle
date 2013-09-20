<?php

/*
 * This file is part of the Black package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Black\Bundle\EventBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Black\Bundle\EventBundle\Model\InvitationInterface;

/**
 * Class Event
 *
 * @package Black\Bundle\EventBundle\Model
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
abstract class Event implements EventInterface
{
    /**
     * @var
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $invitations;

    /**
     * @var
     */
    protected $duration;

    /**
     * @var
     */
    protected $endDate;

    /**
     * @var
     */
    protected $location;

    /**
     * @var
     */
    protected $offers;

    /**
     * @var
     */
    protected $performers;

    /**
     * @var
     */
    protected $startDate;

    /**
     * @var
     * @Assert\Choice(callback="getStatusChoices")
     */
    protected $status;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $subEvents;

    /**
     * @var
     */
    protected $superEvent;

    /**
     * @var
     * @Assert\Choice(callback="getVisibilityChoices")
     */
    protected $visibility;


    /**
     *
     */
    public function __construct()
    {
        $this->invitations  = new ArrayCollection();
        $this->subEvents    = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $invitations
     */
    public function setInvitations($invitations)
    {
        $this->invitations = $invitations;
    }

    /**
     * @return ArrayCollection
     */
    public function getInvitations()
    {
        return $this->invitations;
    }

    /**
     * @param $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param $offers
     */
    public function setOffers($offers)
    {
        $this->offers = $offers;
    }

    /**
     * @return mixed
     */
    public function getOffers()
    {
        return $this->offers;
    }

    /**
     * @param $performers
     */
    public function setPerformers($performers)
    {
        $this->performers = $performers;
    }

    /**
     * @return mixed
     */
    public function getPerformers()
    {
        return $this->performers;
    }

    /**
     * @param $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param EventInterface $subEvent
     */
    public function setSubEvent(EventInterface $subEvent)
    {
        $this->subEvents->add($subEvent);
    }

    /**
     * @param $subEvents
     */
    public function setSubEvents($subEvents)
    {
        foreach ($subEvents as $subEvent) {
            $this->setSubEvent($subEvent);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getSubEvents()
    {
        return $this->subEvents;
    }

    /**
     * @param $subEvent
     */
    public function addSubEvent($subEvent)
    {
        if ($subEvent instanceof Event) {
            $subEvent->setSuperEvent($this);
        }
    }

    /**
     * @param $subEvent
     */
    public function removeSubEvent($subEvent)
    {
        if ($subEvent instanceof Event) {
            $subEvent->setSuperEvent(null);

            if ($this->getSubEvents()->contains($subEvent)) {
                $this->getSubEvents()->removeElement($subEvent);
            }
        }
    }

    /**
     *
     */
    public function cleanSubEvents()
    {
        foreach ($this->subEvents as $subEvent) {
            $this->removeSubEvent($subEvent);
        }
        $this->getSubEvents()->clear();
    }

    /**
     * @param $superEvent
     */
    public function setSuperEvent($superEvent)
    {
        if ($superEvent instanceof Event || $superEvent === null) {
            $this->superEvent = $superEvent;
        }
    }

    /**
     * @return mixed
     */
    public function getSuperEvent()
    {
        return $this->superEvent;
    }

    /**
     * @param PersonInterface $invitation
     */
    public function addInvitation(InvitationInterface $invitation)
    {
        $this->getInvitations()->add($invitation);
    }

    /**
     * @param PersonInterface $invitation
     */
    public function removeInvitation(InvitationInterface $invitation)
    {
        $this->getInvitations()->removeElement($invitation);
    }

    /**
     *
     */
    public function cleanInvitations()
    {
        foreach ($this->getInvitations() as $invitation) {
            $this->removeInvitation($invitation);

        }
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public static function getStatusChoices()
    {
        return array('open', 'close');
    }

    /**
     * @return mixed
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @return array
     */
    public static function getVisibilityChoices()
    {
        return array('public', 'private');
    }

    /**
     * @param string $enabled
     *
     * @return $this
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        if ('public' === $this->getVisbility()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        if ('private' === $this->getVisbility()) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     *
     */
    public function onRemove()
    {
        if (null !== $this->getSuperEvent()) {
            $this->getSuperEvent()->removeSubEvent($this);
            $this->setSuperEvent(null);
        }
        
        $this->cleanInvitations();
    }
}
