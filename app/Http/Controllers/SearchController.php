<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function clubs(Request $request)
    {
        $response = Club::query();
        if ($request->has('q')) {
            $response = $response->where('name', 'LIKE', '%' . $request->q . '%');
        } elseif ($request->has('term')) {
            $response = $response->where('name', 'LIKE', '%' . $request->term . '%');
        }
        $response = $response->paginate(25);
        return response()->json($response);
    }
}
