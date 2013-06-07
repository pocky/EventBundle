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

use Doctrine\ODM\MongoDB\DocumentManager;

class EventManager extends DocumentManager
{
    protected $dm;
    protected $repository;
    protected $class;

    public function __construct(DocumentManager $dm, $class)
    {
        $this->dm           = $dm;
        $this->repository   = $dm->getRepository($class);

        $metadata       = $dm->getClassMetadata($class);
        $this->class    = $metadata->name;
    }

    public function persist($document)
    {
        if (!is_object($document)) {
            throw new \InvalidArgumentException(gettype($document));
        }

        $this->dm->persist($document);
    }

    public function flush()
    {
        $this->dm->flush();
    }

    public function remove($document)
    {
        if (!is_object($document)) {
            throw new \InvalidArgumentException(gettype($document));
        }

        $this->dm->remove($document);
    }

    public function getDocumentManager()
    {
        return $this->dm;
    }

    public function getDocumentRepository()
    {
        return $this->repository;
    }

    /**
     * Create a new Event Object
     *
     * @return $config object
     */
    public function createEvent()
    {
        $class  = $this->getClass();
        $config = new $class;

        return $config;
    }

    protected function getClass()
    {
        return $this->class;
    }
}
