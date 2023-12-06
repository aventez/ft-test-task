<?php

namespace App\Resolvers;

class LevelResolver
{
    public static function resolveLevelFromPoints(int $currentPoints): int
    {
        $thresholds = config('game.thresholds');
        $highestLevel = 1;

        foreach($thresholds as $level => $desiredLevelPoints)  {
            if ($currentPoints >= $desiredLevelPoints) {
                $highestLevel = $level;
            } else {
                break;
            }
        }

        return $highestLevel;
    }

    public static function getNextLevelDesiredPoints(int $points): int
    {
        $thresholds = config('game.thresholds');
        $currentLevel = self::resolveLevelFromPoints($points);
        $newLevel = $currentLevel + 1;

        return isset($thresholds[$newLevel])
            ? $thresholds[$newLevel]
            : $thresholds[$currentLevel];
    }
}
