<?php

namespace OSS\MatchBundle\Repository;

use Doctrine\ORM\EntityRepository;
use OSS\CoreBundle\Entity\GameDate;
use OSS\LeagueBundle\Entity\League;
use OSS\MatchBundle\Entity\Fixture;

class FixtureRepository extends EntityRepository
{
    /**
     * @param GameDate $gameDate
     *
     * @return Fixture[]
     */
    public function findByGameDate(GameDate $gameDate)
    {
        return $this->findBy(array('season' => $gameDate->getSeason(), 'week' => $gameDate->getWeek()));
    }

    /**
     * @param League $league
     * @param int $season
     *
     * @return Fixture[]
     */
    public function findByLeagueAndSeasonWithEvents(League $league, $season)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('f', 'e'));
        $qb->from('MatchBundle:Fixture', 'f');
        $qb->where('f.league = :league');
        $qb->setParameter('league', $league->getId());
        $qb->andWhere('f.season = :season');
        $qb->setParameter('season', $season);
        $qb->join('f.events', 'e');

        return $qb->getQuery()->getResult();
    }
}
