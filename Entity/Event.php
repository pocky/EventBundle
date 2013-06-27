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

use Black\Bundle\EventBundle\Model\Event as AbstractEvent;
use Black\Bundle\EngineBundle\Traits\ThingEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Black\Bundle\EngineBundle\Model\PersonInterface;

/**
 * Event Entity
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="Black\Bundle\EventBundle\Entity\EventRepository")
 */
class Event extends AbstractEvent
{
    use ThingEntityTrait;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="duration", type="string", nullable=true)
     * @Assert\Type(type="string")
     */
    protected $duration;

    /**
     * @ORM\Column(name="end_date", type="date", nullable=true)
     */
    protected $endDate;

    /**
     * @ORM\ManyToMany(targetEntity="Black\Bundle\EngineBundle\Entity\Person", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="event_attendees",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="person_id", referencedColumnName="id")}
     *      )
     */
    protected $attendees;

    /**
     * @ORM\ManyToMany(targetEntity="Black\Bundle\EngineBundle\Entity\PostalAddress", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="event_postal_address",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id", unique=true)},
     *      inverseJoinColumns={@ORM\JoinColumn(name="postal_address_id", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $location;

    /**
     * @ORM\Column(name="offers", type="string", nullable=true)
     * @Assert\Type(type="string")
     */
    protected $offers;

    /**
     * @ORM\Column(name="performers", type="string", nullable=true)
     * @Assert\Type(type="string")
     */
    protected $performers;

    /**
     * @ORM\Column(name="start_date", type="date", nullable=true)
     */
    protected $startDate;

    /**
     * @ORM\ManyToOne(targetEntity="Event", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="event_super_event",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="sub_event_id", referencedColumnName="id")}
     *      )
     */
    protected $subEvents;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="subEvents", cascade={"persist", "remove"})
     */
    protected $superEvent;
    
    public function __construct() {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->subEvents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->attendees = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * @ORM\PreRemove
     */
    public function onRemove()
    {
        parent::onRemove();
    }
}
