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

interface EventInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getDuration();

    /**
     * @return mixed
     */
    public function getEndDate();

    /**
     * @return mixed
     */
    public function getLocation();

    /**
     * @return mixed
     */
    public function getOffers();

    /**
     * @return mixed
     */
    public function getStartDate();

    /**
     * @return mixed
     */
    public function getSubEvents();

    /**
     * @return mixed
     */
    public function getSuperEvent();

}
