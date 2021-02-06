<?php

namespace App\Models;

use App\Http\Contracts\Player;
use App\Http\Contracts\PointsCalculator;

/**
 * Class BasketballPlayer
 *
 * @package App\Models
 */
class BasketballPlayer implements Player
{
    /**
     * @var string
     */
    private String $name='';

    /**
     * @var string
     */
    public string $nickName='';

    /**
     * @var int
     */
    private int $number;

    /**
     * @var string
     */
    public string $team;

    /**
     * @var string
     */
    private string $position;

    /**
     * @var int
     */
    private int $scoredPoints;

    /**
     * @var int
     */
    private int $rebounds;

    /**
     * @var int
     */
    private int $assists;

    /**
     * @var int
     */
    public int $totalPoints = 0;

    /**
     * @var bool
     */
    public bool $isInWinnerTeam = false;

    /**
     * @var PointsCalculator
     */
    private PointsCalculator $pointsCalculator;

    /**
     * BasketballPlayer constructor.
     *
     * @param PointsCalculator $pointsCalculator
     * @param $row
     */
    public function __construct(PointsCalculator $pointsCalculator, $row)
    {
        $this->pointsCalculator = $pointsCalculator;
        $row = explode(';', $row);

        $this->name     = $row[0];
        $this->nickName = $row[1];
        $this->number   = $row[2];
        $this->team     = $row[3];
        $this->setPosition($row[4]);
        $this->scoredPoints = $row[5];
        $this->rebounds     = $row[6];
        $this->assists      = $row[7];
        $this->calculateTotalPoints();
    }

    private function calculateTotalPoints(): void
    {
        $this->totalPoints = $this->pointsCalculator->calculate($this->position, [ $this->scoredPoints, $this->rebounds, $this->assists ]);
    }

    public function addWinningPoints(): void
    {
        $this->totalPoints +=10;
    }

    /**
     * @param string $position
     */
    private function setPosition(string $position): void
    {
        if ($position !== 'G' && $position !== 'F' && $position !== 'C') {
            throw new \RuntimeException("The position {$position} is invalid");
        }

        $this->position = $position;
    }

    /**
     * @return array
     */
    public function getPlayerInformation(): array
    {
        return [
            'Name'         => $this->name,
            'NickName'     => $this->nickName,
            'Number'       => $this->number,
            'Team'         => $this->team,
            'Position'     => $this->position,
            'Assist'       => $this->assists,
            'Rebound'      => $this->rebounds,
            'ScoredPoints' => $this->scoredPoints,
            'TotalPoints'  => $this->totalPoints,
            'winner'       => $this->isInWinnerTeam
        ];
    }
}
