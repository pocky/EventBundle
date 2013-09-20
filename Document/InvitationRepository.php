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
 * Class InvitationRepository
 *
 * @package Black\Bundle\EventBundle\Document
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class InvitationRepository extends DocumentRepository
{

    /**
     * @param $event
     * @param $person
     *
     * @return mixed
     */
    public function getEventForPerson($event, $person)
    {
        $qb = $this->getQueryBuilder()
            ->field('event')->equals(new \MongoId($event))
            ->field('person')->equals(new \MongoId($person))
            ->getQuery();

        return $qb->getSingleResult();
    }

    /**
     * @param $event
     *
     * @return array|bool|\Doctrine\MongoDB\ArrayIterator|\Doctrine\MongoDB\Cursor|\Doctrine\MongoDB\EagerCursor|mixed|null
     */
    public function getInvitationsForEvent($event)
    {
        $qb = $this->createQueryBuilder()
            ->field('event')->equals(new \MongoId($event))
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
