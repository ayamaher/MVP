<?php

namespace App\Http\Controllers;

use App\Http\Services\MostValuablePlayerService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MostValuablePlayerController extends Controller
{
    /**
     * @var MostValuablePlayerService
     */
    private MostValuablePlayerService $mostValuablePlayerService;

    /**
     * MostValuablePlayerController constructor.
     *
     * @param MostValuablePlayerService $mostValuablePlayerService
     */
    public function __construct(MostValuablePlayerService $mostValuablePlayerService)
    {
        $this->mostValuablePlayerService = $mostValuablePlayerService;
    }

    /**
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function index()
    {
        $mvpPlayer = $this->mostValuablePlayerService->getMVP();

        return view('welcome')->with('sports', $mvpPlayer['Sports']);

        // return response()->json($mvpPlayer['Sports']);
    }
}
