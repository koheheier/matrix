<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matrix;
use App\Models\Factor;
use App\Models\Candidate;

class rankingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        /**
         * 表示する内容
         * マトリクス名
         * 候補名：合計点数：順位
         * 要素名：そのポイント
         */
        $matrixId = $request->route("matrixId");
        $matrix_model = Matrix::where("id", $matrixId)->first();
        
        
        $factors = Factor::where("matrix_id", $matrixId)->get()->toArray();
        $factor_ids = array_column($factors, "id");
        $factors_data = [];
        foreach ($factors as $factor) {
            $factors_data[$factor["id"]] = 
            [
                "name" => $factor["name"],
                "weight" => $factor["weight"]
            ];
        }

        $candidates = Candidate::whereIn("factor_id", $factor_ids)->get()->toArray();
        
        $candidates_data_list = [];
        $candidate_names = [];
        $candidate_totals = [];
        foreach ($candidates as $candidate) {
            $candidates_data_list[$candidate["group_id"]][$candidate["factor_id"]] = $candidate["point"];
            $candidate_names[$candidate["group_id"]] = $candidate["name"];
            if (array_key_exists($candidate["group_id"], $candidate_totals)) {
                $candidate_totals[$candidate["group_id"]] += $candidate["point"] * $factors_data[$candidate["factor_id"]]["weight"];
            } else {
                $candidate_totals[$candidate["group_id"]] = $candidate["point"] * $factors_data[$candidate["factor_id"]]["weight"];
            }
        }



        return view("ranking")
        ->with("matrix_name", $matrix_model->name)
        ->with("factors_data", $factors_data)
        ->with("candidates_data_list", $candidates_data_list)
        ->with("candidate_names", $candidate_names)
        ->with("candidate_totals", $candidate_totals)
        ;
    }
}
