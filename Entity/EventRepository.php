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

use Doctrine\ORM\EntityRepository;

/**
 * Class EventRepository
 *
 * @package Black\Bundle\EventBundle\Entity
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class EventRepository extends EntityRepository
{
    /**
     * @param integer $limit
     * 
     * @return array
     */
    public function getLastEvents($limit = 3)
    {
        $qb = $this->getQueryBuilder()
                ->select('e')
                ->orderBy('e.startDate', 'DESC')
                ->setMaxResults($limit)
                ->getQuery();

        return $qb->execute();
    }

    /**
     * @param integer $id
     * 
     * @return array
     */
    public function getEventsForPerson($id)
    {
        $qb = $this->getQueryBuilder()
                ->select('e')
                ->leftJoin('attendees', 'a')
                ->where('a.id = :a_id')
                ->setParameter('a_id', $id)
                ->getQuery();

        return $qb->execute();
    }

    /**
     * @param string $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getQueryBuilder($alias = 'e')
    {
        return $this->createQueryBuilder($alias);
    }
}
