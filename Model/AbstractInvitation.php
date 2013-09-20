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

use Black\Bundle\PersonBundle\Model\PersonInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractInvitation
 *
 * @package Black\Bundle\EventBundle\Model
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
abstract class AbstractInvitation implements InvitationInterface
{
    /**
     * @var
     */
    protected $event;

    /**
     * @var
     * @Assert\Choice(callback="getExpectedChoices")
     */
    protected $expected;

    /**
     * @var
     */
    protected $person;

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getId();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $event
     */
    public function setEvent(EventInterface $event)
    {
        $this->event = $event;

        if (!$event->getInvitations()->contains($this)) {
            $event->addInvitation($this);
        }
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $expected
     */
    public function setExpected($expected)
    {
        $this->expected = $expected;
    }

    /**
     * @return mixed
     */
    public function getExpected()
    {
        return $this->expected;
    }

    public static function getExpectedChoices()
    {
        return array('yes', 'no', 'maybe');
    }

    /**
     * @param mixed $person
     */
    public function setPerson(PersonInterface $person)
    {
        $this->person = $person;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }
}
