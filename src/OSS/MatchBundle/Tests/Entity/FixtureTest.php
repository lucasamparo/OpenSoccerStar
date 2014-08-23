<?php

namespace OSS\MatchBundle\Tests\Entity;

use OSS\MatchBundle\Entity\Event;
use OSS\MatchBundle\Entity\Fixture;
use OSS\MatchBundle\Entity\Team;

class FixtureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \OSS\MatchBundle\Exception\MatchException
     */
    public function testSetTeam()
    {
        $fixture = new Fixture();
        $team = new Team();

        $fixture->setTeamHome($team);
        $fixture->setTeamAway($team);
    }

    public function testScoreMatchesEvents()
    {
        $fixture = new Fixture();
        $team1 = new Team();
        $team1->setId(1);
        $team2 = new Team();
        $team2->setId(2);
        $fixture->setTeamHome($team1);
        $fixture->setTeamAway($team2);

        $this->assertEquals(0, $this->countGoalsInEvents($fixture, $fixture->getTeamHome()));
        $this->assertEquals(0, $this->countGoalsInEvents($fixture, $fixture->getTeamAway()));
        $this->assertEquals(0, $fixture->getGoalsScored());

        $fixture->addEvent(Event::createGoal($fixture->getTeamHome()));
        $this->assertEquals(1, $this->countGoalsInEvents($fixture, $fixture->getTeamHome()));
        $this->assertEquals(0, $this->countGoalsInEvents($fixture, $fixture->getTeamAway()));
        $this->assertEquals(1, $fixture->getScoreHome());
        $this->assertEquals(0, $fixture->getScoreAway());
        $this->assertEquals(1, $fixture->getGoalsScored());
    }

    public function testNoScoreBeforeMatchStart()
    {
        $fixture = new Fixture();
        $this->assertNull($fixture->getScoreHome());
        $this->assertNull($fixture->getScoreAway());
    }

    /**
     * @param Fixture $fixture
     * @param Team $team
     *
     * @return int
     */
    private function countGoalsInEvents(Fixture $fixture, Team $team)
    {
        $goalsInEvents = 0;
        foreach ($fixture->getEvents() as $event) {
            if ($event->isGoal() && $event->getTeam()->equals($team)) {
                $goalsInEvents++;
            }
        }

        return $goalsInEvents;
    }
}
