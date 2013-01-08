<?php

/*
 * This file is part of the Blackengine package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Blackroom\Bundle\EventBundle\Document\Event;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentNotFoundException;

class EventRepository extends DocumentRepository
{
    public function getLastEvents($limit = 3)
    {
        $qb = $this->getQueryBuilder()
                ->sort('createdAt', 'desc')
                ->limit($limit)
                ->getQuery()
        ;

        return $qb->execute();
    }

    private function getQueryBuilder()
    {
        return $this->createQueryBuilder();
    }
}
