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

/**
 * Class EventInterface
 *
 * @package Black\Bundle\EventBundle\Model
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
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
