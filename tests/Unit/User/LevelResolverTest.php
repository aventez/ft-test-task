<?php

namespace Tests\Unit\User;

use App\Resolvers\LevelResolver;
use Tests\TestCase;

class LevelResolverTest extends TestCase
{
    public function testResolveLevelFromPoints()
    {
        // Level 1 - <= 99
        $this->assertEquals(1, LevelResolver::resolveLevelFromPoints(-1));
        $this->assertEquals(1, LevelResolver::resolveLevelFromPoints(0));
        $this->assertEquals(1, LevelResolver::resolveLevelFromPoints(99));

        // Level 2 - < 160
        $this->assertEquals(2, LevelResolver::resolveLevelFromPoints(100));
        $this->assertEquals(2, LevelResolver::resolveLevelFromPoints(130));
        $this->assertEquals(2, LevelResolver::resolveLevelFromPoints(159));

        // Level 3 - sky is the limit
        $this->assertEquals(3, LevelResolver::resolveLevelFromPoints(160));
        $this->assertEquals(3, LevelResolver::resolveLevelFromPoints(15000));
    }

    public function testResolveDesiredPointsForNextLevel()
    {
        $this->assertEquals(100, LevelResolver::getNextLevelDesiredPoints(0));
        $this->assertEquals(100, LevelResolver::getNextLevelDesiredPoints(15));
        $this->assertEquals(100, LevelResolver::getNextLevelDesiredPoints(99));
        $this->assertEquals(100, LevelResolver::getNextLevelDesiredPoints(-50));

        $this->assertEquals(160, LevelResolver::getNextLevelDesiredPoints(100));
        $this->assertEquals(160, LevelResolver::getNextLevelDesiredPoints(130));
        $this->assertEquals(160, LevelResolver::getNextLevelDesiredPoints(159));
        $this->assertEquals(160, LevelResolver::getNextLevelDesiredPoints(1900));
    }
}
