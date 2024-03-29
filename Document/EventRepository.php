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

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentNotFoundException;

/**
 * Class EventRepository
 *
 * @package Black\Bundle\EventBundle\Document
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class EventRepository extends DocumentRepository
{
    /**
     * @param int $limit
     *
     * @return array|bool|\Doctrine\MongoDB\ArrayIterator|\Doctrine\MongoDB\Cursor|\Doctrine\MongoDB\EagerCursor|mixed|null
     */
    public function getLastEvents($limit = 3)
    {
        $qb = $this->getQueryBuilder()
                ->sort('startDate', 'desc')
                ->limit($limit)
                ->getQuery();

        return $qb->execute();
    }

    /**
     * @param $id
     *
     * @return array|bool|\Doctrine\MongoDB\ArrayIterator|\Doctrine\MongoDB\Cursor|\Doctrine\MongoDB\EagerCursor|mixed|null
     */
    public function getEventsForPerson($id)
    {
        $qb = $this->getQueryBuilder()
                ->field('attendees.$id', new \MongoId($id))
                ->getQuery();

        return $qb->execute();
    }

    /**
     * @return \Doctrine\ODM\MongoDB\Query\Builder
     */
    private function getQueryBuilder()
    {
        return $this->createQueryBuilder();
    }
}
