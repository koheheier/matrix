<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CreateCandidatesRequest;
use App\Models\Matrix;
use App\Models\Factor;
use App\Models\Candidate;
use App\Services\CandidatesService;

class createCandidatesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateCandidatesRequest $request, CandidatesService $candidatesService)
    {
        if (session('feedback.error')) {
            session()->forget("feedback.error");
        }
        // 候補編集画面で登録ボタン押下時の処理
        $candidate_info = $request->getCandidates();
        $candidate_names = $candidate_info["names"];
        $candidate_points = $candidate_info["points"];
        $candidate_group_ids = $candidate_info["group_ids"];
        $matrix_id = $request->getMatrixId();
        
        // データ不備確認
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
        
        // データの入力値誤りの対処
        $errorParameters = $candidatesService->getErrorParametersCandidatesData(
            $candidate_names,
            $candidate_points
        );
        if ($errorParameters) {
            return redirect()->route("make_candidates", ["matrixId" => $matrix_id])->with('errorParameters', $errorParameters);
        }
        // この時点で空配列なら削除処理なし
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
