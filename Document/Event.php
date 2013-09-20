<?php

/*
 * This file is part of the Black package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Black\Bundle\EventBundle\Document;

use Black\Bundle\EventBundle\Model\Event as AbstractEvent;
use Doctrine\ODM\MongoDB\Mapping\Annotations\MappedSuperclass;
use Black\Bundle\CommonBundle\Traits\ThingDocumentTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Event
 *
 * @MappedSuperClass
 *
 * @package Black\Bundle\EventBundle\Document
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class Event extends AbstractEvent
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
     * @ODM\EmbedOne(targetDocument="Black\Bundle\EventBundle\Document\PostalAddress")
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
     * @ODM\String
     */
    protected $status;

    /**
     * @ODM\ReferenceMany(targetDocument="Event", cascade={"all"})
     */
    protected $subEvents;

    /**
     * @ODM\ReferenceOne(targetDocument="Event", cascade={"all"})
     */
    protected $superEvent;

    /**
     * @ODM\String
     */
    protected $visibility;

    /**
     * @ODM\PreRemove
     */
    public function onRemove()
    {
        parent::onRemove();
    }
}
