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

use Black\Bundle\EventBundle\Model\AbstractInvitation;
use Doctrine\ODM\MongoDB\Mapping\Annotations\MappedSuperclass;
use Black\Bundle\CommonBundle\Traits\ThingDocumentTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Invitation
 *
 * @MappedSuperClass
 *
 * @package Black\Bundle\EventBundle\Document
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
abstract class Invitation extends AbstractInvitation
{
    /**
     * @ODM\ReferenceOne()
     */
    protected $event;

    /**
     * @ODM\String
     * @Assert\Choice(callback="getExpectedChoices")
     */
    protected $expected;

    /**
     * @ODM\ReferenceOne()
     */
    protected $person;
}
