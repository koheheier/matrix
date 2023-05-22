<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CreateCandidatesRequest;
use App\Models\Matrix;
use App\Models\Factor;
use App\Models\Candidate;

class createCandidatesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateCandidatesRequest $request)
    {
        // 候補編集画面で登録ボタン押下時の処理
        $candidate_info = $request->getCandidates();
        $candidate_names = $candidate_info["names"];
        $candidate_points = $candidate_info["points"];
        $candidate_group_ids = $candidate_info["group_ids"];
        $matrix_id = $request->getMatrixId();
        
        if (is_null($candidate_names) ||
            is_null($candidate_points) ||
            in_array("", ($candidate_names ?? []))) {
                $request->session()->flash('deficiency', 'データに不備があります。');
                return redirect()->route('make_candidates', ["matrixId" => $matrix_id]);
            }
            // candidate_pointsに空欄があるか
            foreach ($candidate_points as $candidate_point) {
                if (in_array("", $candidate_point)) {
                    $request->session()->flash('deficiency', 'データに不備があります。');
                    return redirect()->route('make_candidates', ["matrixId" => $matrix_id]);
                }
            }
            
        // 一旦ここで全削除。matrix_idから要素を複数取って、そのidで候補群を取得。それを一旦全削除
        $factors = Factor::where("matrix_id", $matrix_id)->get()->toArray();
        $factor_ids = array_column($factors, "id");
        $delete_candidates = Candidate::whereIn("factor_id", $factor_ids)->get();
        foreach ($delete_candidates as $delete_candidate) {
            $delete_candidate->delete();
        }

        foreach ($candidate_names as $key => $name) {
            $group_id = $candidate_group_ids[$key];
            foreach ($candidate_points[$key] as $factor_id => $point) {
                $candidate = new Candidate;
                $candidate->name = $name;
                $candidate->group_id = $group_id;
                $candidate->point = $point;
                $candidate->factor_id = $factor_id;
                $candidate->save();
            }
        }
        session()->forget("candidate_names");
        session()->forget("candidate_points");
        session()->forget("group_ids");
        return redirect()->route('list')->with('feedback.success', "候補登録しました");
    }
}
