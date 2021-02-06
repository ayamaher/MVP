<?php

namespace App\Models;

use App\Http\Contracts\Player;
use Illuminate\Support\Collection;

/**
 * Class WinnerPlayer
 *
 * @package App\Http\Services
 */
class WinnerPlayer
{
    /**
     * @param $sports
     *
     * @return array
     */
    public static function findTheWinnerPlayer($sports): array
    {
        $winnerPlayerIfo = collect($sports)->collapse()->groupBy(static function ($player) {
            return $player->nickName;
        })->map(static function ($player) {
            return collect($player)->sum(static function ($player) {
                return $player->totalPoints;
            });
        })->sortDesc()->take(1)->toArray();

        return self::beautifyWinnerPlayerInfo($sports, $winnerPlayerIfo);
    }

    /**
     * @param $sports
     * @param $winnerPlayerIfo
     *
     * @return array
     */
    private static function beautifyWinnerPlayerInfo($sports, $winnerPlayerIfo): array
    {
//        $totalPoints = reset($winnerPlayerIfo);
        $nickName    = key($winnerPlayerIfo);
        $playerSports = [];

        foreach ($sports as $sport => $players) {
            foreach ($players as $player) {
                if ($player->nickName === $nickName) {
                    $playerSports[$sport] = $player->getPlayerInformation();
                }
            }

        }

        return [
            'Sports'       => $playerSports
        ];
    }
}

