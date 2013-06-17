<?php

/*
 * This file is part of the Blackengine package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Black\Bundle\EventBundle\Document;

use Black\Bundle\EventBundle\Model\Event as AbstractEvent;
use Black\Bundle\EngineBundle\Traits\ThingDocumentTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Black\Bundle\EngineBundle\Model\PersonInterface;

/**
 * @ODM\MappedSuperClass()
 */
abstract class Event extends AbstractEvent
{
    use ThingDocumentTrait;

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
     * @ODM\EmbedOne(targetDocument="Black\Bundle\EngineBundle\Document\PostalAddress")
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
    
    /**
     * @ODM\PreRemove
     */
    public function onRemove()
    {
        parent::onRemove();
    }
}
