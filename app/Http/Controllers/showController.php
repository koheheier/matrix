<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matrix;
use App\Models\Factor;
use App\Models\Candidate;

class showController extends Controller
{
    public function list()
    {
        $matrices = Matrix::all();
        $matrixDataList = [];
        foreach ($matrices as $matrix) {
            // マトリクスに紐づく要素を取得。
            $factor = Factor::where("matrix_id", $matrix->id)->first();
            // 要素に紐づく候補を取得
            $candidates = Candidate::where("factor_id", $factor->id)->get();
            $hasCandidates = true;
            if (count($candidates) < 1) {
                $hasCandidates = false;
            }
            $matrixDataList[] = [
                "name" => $matrix->name,
                "id" => $matrix->id,
                "user_id" => $matrix->user_id,
                "hasCandidates" => $hasCandidates
            ];
        }

        return view('list', ["matrixDataList" => $matrixDataList]);
    }
}
