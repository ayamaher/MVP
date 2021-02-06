<?php

namespace App\Http\Services\GamePoints;

use App\Http\Contracts\PointsCalculator;

final class HandballPointsService implements PointsCalculator
{
    /**
     * @param string $position
     * @param array $points
     *
     * @return float|int|mixed
     */
    public function calculate(string $position, array $points)
    {
        $positionPoints = $this->getPositionPoints($position);
        //echo($this->calculatePlayerTotalPoints($points, $positionPoints).'<br>');

        return $this->calculatePlayerTotalPoints($points, $positionPoints);
    }


    /**
     * @param string $position
     *
     * @return int[]|null
     */
    private function getPositionPoints(string $position): ?array
    {
        switch (strtoupper($position)) {
            case 'G':
                return [ 'initial_points' => 50, 'goal_made' => 5, 'goal_received' => -2 ];
            case 'F':
                return [ 'initial_points' => 20, 'goal_made' => 1, 'goal_received' => -1 ];
            default:
                throw new \RuntimeException("The position {$position} is invalid");

        }
    }

    /**
     * @param $points
     * @param $positionPoints
     *
     * @return float|int
     */
    private function calculatePlayerTotalPoints($points, $positionPoints)
    {
        return $positionPoints['initial_points'] + ($points[0] * $positionPoints['goal_made']) + ($points[1] * $positionPoints['goal_received']);
    }


}
