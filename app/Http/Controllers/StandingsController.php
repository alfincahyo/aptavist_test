<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StandingsController extends Controller
{
    public function index(Request $request)
    {
        $config = [
            'title' => 'Standings'
        ];

        if ($request->ajax()) {
            $data = DB::table('matches_log')
                ->selectRaw(
                    'club.name as club, SUM(IF(matches_log.points=3,1,0)) AS win, COUNT(matches_log.club_id) as match_played, SUM(IF(matches_log.points=1,1,0)) AS draw, SUM(IF(matches_log.points=0,1,0)) AS lose,
                    SUM(matches_log.gm) AS gm,SUM(matches_log.gk) AS gk, SUM(matches_log.points) AS points'
                )
                ->join('club', 'club.id', '=', 'matches_log.club_id')
                ->groupBy('matches_log.club_id')
                ->orderBy('points', 'DESC')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()->make(true);
        }

        return view('layouts.pages.standings.index', compact('config'));
    }
}
