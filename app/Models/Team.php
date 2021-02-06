<?php

namespace App\Models;

use App\Http\Contracts\Player;
use Illuminate\Support\Collection;

class Team
{

    /**
     */
    public static Collection $winnerTeams;

    /**
     * @var Collection
     */
    private static Collection $TeamsTotalPoints;

    /**
     * @var array
     */
     static array $sportTeams =[];

    /**
     * @param $sport
     * @param $player
     */
    public static function appendPlayerToTeam($sport, $player): void
    {
        if (!isset(self::$sportTeams[$sport])) {
            self::$sportTeams[$sport] = [];
        }

        if (!isset(self::$sportTeams[$sport][$player->team])) {
            self::$sportTeams[$sport][$player->team] = [];
        }

        self::$sportTeams[$sport][$player->team][] = $player;
    }

    public static function addPointsToWinnerTeams(): void
    {
        self::setTeamsTotalPoints();
        self::setWinnerTeams();
        self::addWinningPointsToPlayers();
    }

    private static function setWinnerTeams(): void
    {
        self::$winnerTeams = collect(self::$TeamsTotalPoints)->map(static function (Collection $team) {
            $max = $team->max();
            return [ 'team' => $team->search($max), 'totalPoints' => $max ];
        });
    }

    public static function setTeamsTotalPoints(): void
    {
        self::$TeamsTotalPoints = collect(self::$sportTeams)->map(static function ($teams, $sport) {
            return collect($teams)->map(static function ($players) {
                return collect($players)->sum(static function (Player $player) {
                    return $player->totalPoints;
                });
            });
        });
    }

    /**
     * @param $team
     * @param $sport
     *
     * @return bool
     */
    private static function isWinnerTeamOfGame($team, $sport): bool
    {
        return $team === self::$winnerTeams->get($sport)['team'];
    }

    private static function addWinningPointsToPlayers(): void
    {
        foreach (self::$sportTeams as $sport => $players) {
            collect($players)->filter(static function ($players, $team) use ($sport) {
                return self::isWinnerTeamOfGame($team, $sport);
            })->collapse()->map(static function ($player) {
                $player->addWinningPoints();
                $player->isInWinnerTeam = true;
            });
        }
    }

}
