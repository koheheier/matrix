<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matrix;

class makeMatrixController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $matrices = Matrix::all();
        return view('make_matrix')
            ->with('matrices', $matrices)
            ->with('deficiency', session('deficiency'));
    }
}
