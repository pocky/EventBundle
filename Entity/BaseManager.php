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

use Black\Bundle\EventBundle\Model\ManagerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Class ConfigManager
 *
 * @package Black\Bundle\EventBundle\Entity\Config
 */
class BaseManager extends EntityManager implements ManagerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $manager;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    public function __construct(EntityManager $manager, $class)
    {
        $this->manager     = $manager;
        $this->repository  = $manager->getRepository($class);

        $metadata          = $manager->getClassMetadata($class);
        $this->class       = $metadata->name;
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\EntityRepository
     */
    public function getRepository($classname = '')
    {
        return $this->repository;
    }

    /**
     * @param object $document
     *
     * @throws \InvalidArgumentException
     */
    public function persist($document)
    {
        if (!$document instanceof $this->class) {
            throw new \InvalidArgumentException(gettype($document));
        }

        $this->getManager()->persist($document);
    }

    /**
     *
     */
    public function flush()
    {
        $this->getManager()->flush();
    }

    /**
     * @param object $document
     *
     * @throws \InvalidArgumentException
     */
    public function remove($document)
    {
        if (!$document instanceof $this->class) {
            throw new \InvalidArgumentException(gettype($document));
        }
        
        $this->getManager()->remove($document);
    }

    /**
     * Save and Flush a new document
     *
     * @param $document
     */
    public function persistAndFlush($document)
    {
        $this->persist($document);
        $this->flush();
    }

    /**
     * @param $document
     */
    public function removeAndFlush($document)
    {
        $this->getManager()->remove($document);
        $this->getManager()->flush();
    }

    /**
     * Create a new document
     *
     * @return $config object
     */
    public function createInstance()
    {
        $class  = $this->getClass();
        $document = new $class;

        return $document;
    }

    /**
     * @return string
     */
    protected function getClass()
    {
        return $this->class;
    }
}
