<?php

/*
 * This file is part of the Black package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Black\Bundle\EventBundle\Doctrine;

use Black\Bundle\EventBundle\Model\EventManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class InvitationManager
 *
 * @package Black\Bundle\EventBundle\Doctrine
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class InvitationManager implements EventManagerInterface
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * Constructor
     *
     * @param ObjectManager $dm
     * @param string        $class
     */
    public function __construct(ObjectManager $dm, $class)
    {
        $this->manager     = $dm;
        $this->repository  = $dm->getRepository($class);

        $metadata          = $dm->getClassMetadata($class);
        $this->class       = $metadata->name;
    }

    /**
     * @return ObjectManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param object $model
     *
     * @throws \InvalidArgumentException
     */
    public function persist($model)
    {
        if (!$model instanceof $this->class) {
            throw new \InvalidArgumentException(gettype($model));
        }

        $this->getManager()->persist($model);
    }

    /**
     * Flush
     */
    public function flush()
    {
        $this->getManager()->flush();
    }

    /**
     * Remove the document
     * 
     * @param object $model
     *
     * @throws \InvalidArgumentException
     */
    public function remove($model)
    {
        if (!$model instanceof $this->class) {
            throw new \InvalidArgumentException(gettype($model));
        }
        $this->getManager()->remove($model);
    }

    /**
     * Save and Flush a new document
     *
     * @param mixed $model
     */
    public function persistAndFlush($model)
    {
        $this->persist($model);
        $this->flush();
    }

    /**
     * Remove and flush
     * 
     * @param mixed $model
     */
    public function removeAndFlush($model)
    {
        $this->getManager()->remove($model);
        $this->getManager()->flush();
    }

    /**
     * @param $event
     * @param $person
     *
     * @return mixed
     */
    public function findEventForPerson($event, $person)
    {
        return $this->getRepository()->getEventForPerson($event, $person);
    }

    /**
     * @param $event
     *
     * @return mixed
     */
    public function findInvitationsForEvent($event)
    {
        return $this->getRepository()->getInvitationsForEvent($event);
    }

    /**
     * Create a new model
     *
     * @return $config object
     */
    public function createInstance()
    {
        $class  = $this->getClass();
        $model = new $class;

        return $model;
    }

    /**
     * @return string
     */
    protected function getClass()
    {
        return $this->class;
    }
}
