<?php

namespace App\Http\Contracts;

interface PointsCalculator
{
    /**
     * @param string $position
     * @param array $points
     *
     * @return mixed
     */
    public function calculate(string $position, array $points);
}
