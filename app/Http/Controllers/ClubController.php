<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $config = [
            'title' => 'Club Data'
        ];

        if ($request->ajax()) {
            $data = Club::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Action
                        </button>
                       <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"  data-bs-toggle="modal" data-bs-target="#updateModal">Edit</a></li>
                            <li><a class="dropdown-item btn-delete" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id ="' . $row->id . '" >Hapus</a></li>
                       </ul>
                     </div>';
                    return $actionBtn;
                })->make(true);
        }

        return view('layouts.pages.club.index', compact('config'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'city'          => 'required',
        ]);
        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $data = new Club();
                $data->name = $request->name;
                $data->city = $request->city;
                $data->save();

                DB::commit();
                $response = response()->json([
                    'status' => 'success',
                    'message' => 'Data saved !',
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
        ]);
        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $data = Club::find($id);
                $data->name = $request->name;
                $data->city = $request->city;
                $data->save();

                DB::commit();
                $response = response()->json([
                    'status' => 'success',
                    'message' => 'Data updated !',
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
            $club = Club::find($id);
            $club->delete();
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
