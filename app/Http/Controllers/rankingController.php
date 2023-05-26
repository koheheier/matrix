<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matrix;
use App\Models\Factor;
use App\Models\Candidate;
use App\Services\GeneralService;

class rankingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, GeneralService $generalService)
    {

        list($matrix_name, $factors_data, $candidates_points, $candidate_names, $candidate_totals)
            = $generalService->getRankingData($request->route("matrixId"));

        return view("ranking")
        ->with("matrix_name", $matrix_name)
        ->with("factors_data", $factors_data)
        ->with("candidates_points", $candidates_points)
        ->with("candidate_names", $candidate_names)
        ->with("candidate_totals", $candidate_totals)
        ;
    }
}
