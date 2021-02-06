<?php

namespace App\Http\Factories;

use App\Http\Contracts\Player;

class PlayerFactory
{
    /**
     * @param $sport
     * @param $row
     *
     * @return Player
     */
    public static function getPlayer($sport, $row): Player
    {
        $className = 'App\\Models\\' . $sport . 'Player';

        $sportService = 'App\\Http\\Services\\GamePoints\\' . $sport . 'PointsService';
        //dd($sportService);

        if (!class_exists($className)) {
            throw new \RuntimeException("The model class {$className} not found");
        }

        if (!class_exists($sportService)) {
            throw new \RuntimeException("The points service class {$sportService} not found");
        }

        return new $className(new $sportService, $row);
    }
}
