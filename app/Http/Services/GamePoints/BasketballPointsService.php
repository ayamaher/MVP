<?php

namespace App\Http\Services\GamePoints;

use App\Http\Contracts\PointsCalculator;

final class BasketballPointsService implements PointsCalculator
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
       // echo($this->calculatePlayerTotalPoints($points, $positionPoints).'<br>');
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
                return [ 2, 3, 1 ];
            case 'F':
                return [ 2, 2, 2 ];
            case 'C':
                return [ 2, 1, 3 ];
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
        $totalPoints = array_map(static function ($x, $y) { return $x * $y; },
            $points, $positionPoints);

        return array_sum($totalPoints);
    }


}
