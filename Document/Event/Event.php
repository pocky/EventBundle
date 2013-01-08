<?php

/*
 * This file is part of the Blackengine package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Blackroom\Bundle\EventBundle\Document\Event;

use Blackroom\Bundle\EventBundle\Model\Event\Event as AbstractEvent;
use Blackroom\Bundle\EngineBundle\Traits\ThingDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Blackroom\Bundle\EngineBundle\Model\Person\PersonInterface;

/**
 * @ODM\MappedSuperClass()
 */
abstract class Event extends AbstractEvent
{
    use ThingDocument;

    /**
     * @ODM\String
     * @Assert\Type(type="string")
     */
    protected $duration;

    /**
     * @ODM\Date
     */
    protected $endDate;

    /**
     * @ODM\EmbedOne(targetDocument="Blackroom\Bundle\EngineBundle\Document\Intangible\PostalAddress")
     */
    protected $location;

    /**
     * @ODM\String
     * @Assert\Type(type="string")
     */
    protected $offers;

    /**
     * @ODM\String
     * @Assert\Type(type="string")
     */
    protected $performers;

    /**
     * @ODM\Date
     */
    protected $startDate;

    /**
     * @ODM\ReferenceMany(targetDocument="Event", cascade={"all"})
     */
    protected $subEvents;

    /**
     * @ODM\ReferenceOne(targetDocument="Event", cascade={"all"})
     */
    protected $superEvent;

    public function setAttendee($attendee)
    {
        $this->attendees[] = $attendee;
    }

    public function addAttendee(PersonInterface $attendee)
    {
        if (!$attendee->getEvents()->contains($this)) {
            $attendee->setEvent($this);
        }

        $this->setAttendee($attendee);
    }

    public function removeAttendee(PersonInterface $attendee)
    {
        if ($attendee->getEvents()->contains($this)) {
            $attendee->getEvents()->removeElement($this);
        }

        $this->getAttendees()->removeElement($attendee);
    }
}
