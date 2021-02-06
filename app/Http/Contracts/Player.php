<?php

namespace App\Http\Contracts;

/**
 * Interface Player
 *
 * @package App\Http\Contracts
 */
interface Player
{
    /**
     * Player constructor.
     *
     * @param PointsCalculator $pointsCalculator
     * @param $line
     */
    public function __construct(PointsCalculator $pointsCalculator, $line);

    public function addWinningPoints():void;

    /**
     * @return array
     */
    public function getPlayerInformation():array;

}
