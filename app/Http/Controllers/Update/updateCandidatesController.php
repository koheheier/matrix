<?php

namespace App\Http\Controllers\Update;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Matrix;
use App\Models\Factor;

class updateCandidatesController extends Controller
{
    // 候補更新画面を表示
    public function update(Request $request) {
        
        $matrixId = (int)$request->route("matrixId");
        $matrix = Matrix::where("id", $matrixId)->first();
        
        $factor_models = Factor::where("matrix_id", $matrixId)->get();
        $factors = [];
        $factor_ids = [];
        foreach ($factor_models as $factor_model) {
            $factors[] = 
            [
                "id" => $factor_model->id,
                "name" => $factor_model->name,
                "weight" => $factor_model->weight,
            ];
            $factor_ids[] = $factor_model->id;
        }
        $points = [];
        $names = [];

        foreach ($factor_ids as $factor_id) {
            $candidates = Candidate::where("factor_id", $factor_id)->get();
            foreach ($candidates as $candidate) {
                $names[$candidate->group_id] = $candidate->name;
                $points[$candidate->group_id][$factor_id] = $candidate->point;
            }
        }
        session(
            [
                'candidate_names' => json_encode($names, JSON_UNESCAPED_UNICODE),
                'candidate_points' => json_encode($points, JSON_UNESCAPED_UNICODE),
                'hasFailed' => true
            ]
        );
        $candidate = Candidate::latest('id')->first();
        $group_id = $candidate ? $candidate->group_id : 1;
        
        return view("make_candidates")
            ->with("factors", $factors)
            ->with("matrixId", $matrixId)
            ->with("matrixName", $matrix->name)
            ->with("group_id", $group_id)
            ->with('deficiency', session('deficiency'));
    }

}
