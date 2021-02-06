<?php

namespace App\Http\Services;

use App\Http\Factories\PlayerFactory;
use App\Models\Team;
use App\Models\WinnerPlayer;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

/**
 * Class MostValuablePlayerService
 *
 * @package App\Http\Services
 */
class MostValuablePlayerService
{
    /**
     * @var Filesystem
     */
     private Filesystem $filesystem;

    /**
     * MostValuablePlayerService constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @throws FileNotFoundException
     */
    public function getMVP(): array
    {
        $files = $this->filesystem->files(public_path('sport/'));
        // dd($files);
        $sports = [];

        foreach ($files as $file) {
            $contents     = $this->filesystem->lines($file);
            $sport         = ucfirst(strtolower($contents->first()));
            $sports[$sport] = [];

            foreach ($contents as $key => $row) {
                if ($key !== 0 && $row !== '') {
                    $player         = PlayerFactory::getPlayer($sport, $row);
                    $sports[$sport][] = $player;
                    Team::appendPlayerToTeam($sport, $player);
                }
            }
        }
        Team::addPointsToWinnerTeams();
        return WinnerPlayer::findTheWinnerPlayer($sports);
    }
}
