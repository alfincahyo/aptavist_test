<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\MatchesLog;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MatchesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $config = [
            'title' => 'Matches Data'
        ];

        if ($request->ajax()) {
            $data = Matches::with(['home_club', 'away_club'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Action
                        </button>
                       <ul class="dropdown-menu">
                            <li><a class="dropdown-item btn-delete" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id ="' . $row->id . '" >Hapus</a></li>
                       </ul>
                     </div>';
                    return $actionBtn;
                })->make(true);
        }

        return view('layouts.pages.matches.index', compact('config'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_single()
    {
        $config = [
            'title' => 'Add Single Matches'
        ];

        return view('layouts.pages.matches.single', compact('config'));
    }

    public function create_multiple()
    {
        $config = [
            'title' => 'Add Multiple Matches'
        ];

        return view('layouts.pages.matches.multiple', compact('config'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_single(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'home_club_id'          => 'required|different:away_club_id',
            'home_goal'          => 'required',
            'away_club_id'          => 'required',
            'away_goal'          => 'required',
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                // save matches

                $data = new Matches();
                $data->home_club_id = $request->home_club_id;
                $data->home_goal = $request->home_goal;
                $data->away_club_id = $request->away_club_id;
                $data->away_goal = $request->away_goal;
                $data->save();

                // save log home teams
                $home = new MatchesLog();
                $home->matches_id = $data->id;
                $home->club_id = $request->home_club_id;
                $home->gm = $request->home_goal;
                $home->gk = $request->away_goal;
                if ($request->home_goal == $request->away_goal) {
                    $home->points = 1;
                } else if ($request->home_goal > $request->away_goal) {
                    $home->points = 3;
                } else {
                    $home->points = 0;
                }
                $home->save();

                // save log Away teams
                $away = new MatchesLog();
                $away->matches_id = $data->id;
                $away->club_id = $request->away_club_id;
                $away->gm = $request->away_goal;
                $away->gk = $request->home_goal;
                if ($request->home_goal == $request->away_goal) {
                    $away->points = 1;
                } else if ($request->home_goal > $request->away_goal) {
                    $away->points = 3;
                } else {
                    $away->points = 0;
                }
                $away->save();

                DB::commit();
                $response = response()->json([
                    'status' => 'success',
                    'message' => 'Data saved !',
                    'redirect'  => route('matches.index')
                ]);
            } catch (\Throwable $throw) {
                dd($throw);
                DB::rollBack();
                $response = response()->json(['error' => $throw]);
            }
        } else {
            $response = response()->json(['error' => $validator->errors()->all()]);
        }
        return $response;
    }

    public function store_multiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'matches'        => 'required|array',
        ]);
        if ($validator->passes()) {
            DB::beginTransaction();
            try {

                foreach ($request->matches as $row) {
                    // save matches

                    $data = new Matches();
                    $data->home_club_id = $row['home_club_id'];
                    $data->home_goal = $row['home_goal'];
                    $data->away_club_id = $row['away_club_id'];
                    $data->away_goal = $row['away_goal'];
                    $data->save();

                    // save log home teams
                    $home = new MatchesLog();
                    $home->matches_id = $data->id;
                    $home->club_id = $row['home_club_id'];
                    $home->gm = $row['home_goal'];
                    $home->gk = $row['away_goal'];
                    if ($row['home_goal'] == $row['away_goal']) {
                        $home->points = 1;
                    } else if ($row['home_goal'] > $row['away_goal']) {
                        $home->points = 3;
                    } else {
                        $home->points = 0;
                    }
                    $home->save();

                    // save log Away teams
                    $away = new MatchesLog();
                    $away->matches_id = $data->id;
                    $away->club_id = $row['away_club_id'];
                    $away->gm = $row['away_goal'];
                    $away->gk = $row['home_goal'];
                    if ($row['home_goal'] == $row['away_goal']) {
                        $away->points = 1;
                    } else if ($row['home_goal'] > $row['away_goal']) {
                        $away->points = 3;
                    } else {
                        $away->points = 0;
                    }
                    $away->save();
                }

                DB::commit();
                $response = response()->json([
                    'status' => 'success',
                    'message' => 'Data saved !',
                    'redirect'  => route('matches.index')
                ]);
            } catch (\Throwable $throw) {
                dd($throw);
                DB::rollBack();
                $response = response()->json(['error' => $throw]);
            }
        } else {
            $response = response()->json(['error' => $validator->errors()->all()]);
        }
        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $config = [
            'title' => 'Edit Matches Data'
        ];

        $data = Matches::find($id);

        return view('layouts.pages.matches.single', compact('config', 'data'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = response()->json([
            'status' => 'error',
            'message' => 'failed'
        ]);
        DB::beginTransaction();

        try {
            $matches = Matches::find($id);
            $logs = MatchesLog::where('matches_id', $id)->delete();
            $matches->delete();
            $response = response()->json([
                'status' => 'success',
                'message' => 'Data deleted !'
            ]);
            DB::commit();
        } catch (Throwable $throw) {
            dd($throw);
            $response = response()->json(['error' => $throw]);
        }

        return $response;
    }
}
