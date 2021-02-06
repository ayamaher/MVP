<?php

namespace App\Models;

use App\Http\Contracts\Player;
use App\Http\Contracts\PointsCalculator;
use App\Http\Services\Calculators\HandballPointsService;

/**
 * Class HandballPlayer
 *
 * @package App\Models
 */
class HandballPlayer implements Player
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    public string $nickName;

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
    private int $goalsMade;

    /**
     * @var int
     */
    private int $goalsReceived;

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
     * @param $line
     */
    public function __construct(PointsCalculator $pointsCalculator, $line)
    {
        $this->pointsCalculator = $pointsCalculator;
        $line = explode(';', $line);

        $this->name     = $line[0];
        $this->nickName = $line[1];
        $this->number   = $line[2];
        $this->team     = $line[3];
        $this->setPosition($line[4]);
        $this->goalsMade     = $line[5];
        $this->goalsReceived = $line[6];

        $this->calculateTotalPoints();
    }

    private function calculateTotalPoints(): void
    {
        $this->totalPoints = $this->pointsCalculator->calculate($this->position, [ $this->goalsMade, $this->goalsReceived ]);
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
        if ($position !== 'G' && $position !== 'F') {
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
            'Name'          => $this->name,
            'NickName'      => $this->nickName,
            'Number'        => $this->number,
            'Team'          => $this->team,
            'Position'      => $this->position,
            'GoalsMade'     => $this->goalsMade,
            'GoalsReceived' => $this->goalsReceived,
            'TotalPoints'   => $this->totalPoints,
            'winner'        => $this->isInWinnerTeam
        ];
    }
}
