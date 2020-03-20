<?php

namespace App\Http\Controllers;

use App\Type;

class TypesController extends Controller
{
    public function index()
    {
        $types = Type::all();

        return response()->json([
            'data' => $types,
            'message' => 'Successfully',
            'pagination' => null
        ], 200);
    }
}
