<?php

/*
 * This file is part of the Black package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Black\Bundle\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

use Black\Bundle\EventBundle\Model\Event as AbstractEvent;
use Black\Bundle\EngineBundle\Traits\ThingEntityTrait;

/**
 * Class Event
 *
 * @package Black\Bundle\EventBundle\Entity
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class Event extends AbstractEvent
{
    use ThingEntityTrait;

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
     * @ORM\OneToOne(targetEntity="Black\Bundle\EngineBundle\Entity\PostalAddress", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="postal_address_id", referencedColumnName="id")
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
     * @ORM\OneToMany(targetEntity="Event", mappedBy="superEvent", cascade={"persist", "remove"})
     */
    protected $subEvents;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="subEvents", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="super_event_id", referencedColumnName="id", nullable=true)
     */
    protected $superEvent;

    /**
     * Constructor
     */
    public function __construct()
    {
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
