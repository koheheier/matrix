<?php
namespace App\Services;

use App\Models\Matrix;
use App\Models\Factor;
use App\Models\Candidate;


class GeneralService {

    // // post時エラー感知用
    // public static function hasFailed(){
    //     session(["hasFailed" => 1]);
    // }

    // // get時非エラー感知用
    // public static function releaceFailed(){
    //     session()->forget("hasFailed");
    // }

    // 編集matrixのuser_idと、編集しようとしているuserのid確認
    public function checkOwn(int $matrix_id, int $user_id): bool
    {
        $matrix = Matrix::where("id", $matrix_id)->first();
        if (!$matrix) {
            return false;
        }
        return $user_id === $matrix->user_id;
    }


    /**
     * マトリクスIDで、ランキング表示に必要なデータを集めます
     * matrix_name
     * factors_data
     * candidates_points
     * candidate_names
     * candidate_totals
     *  */ 
    public function getRankingData(int $matrixId): array
    {
        /**
         * 表示する内容
         * マトリクス名
         * 候補名：合計点数：順位
         * 要素名：そのポイント
         */
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
        
        /**
         * ここをなんとかして、1候補に対して複数要素という形にすべき。
         */
        $candidates = Candidate::whereIn("factor_id", $factor_ids)->get()->toArray();
        $candidates_data = [];
        foreach ($candidates as $candidate) {
            $candidates_data[$candidate['group_id']] = 
            [
                "name" => $candidate["name"],
                "point" => $candidate["point"],
            ];
            foreach ($factor_ids as $factor_id) {
                $candidates_data[$candidate['group_id']]["factor_ids"][] = $factor_id;
            }
        }

        $candidate_totals = [];
        foreach ($candidates_data as $group_id => $candidate_data) {
            foreach ($candidate_data["factor_ids"] as $factor_id) {
                if (array_key_exists($group_id, $candidate_totals)) {
                    $candidate_totals[$group_id] += $candidate_data["point"] * $factors_data[$factor_id]["weight"];
                } else {
                    $candidate_totals[$group_id] = $candidate_data["point"] * $factors_data[$factor_id]["weight"];
                }
            }
        }

        $candidates_points = [];
        $candidate_names = [];

        // 数字の大きい順にソートしておくwip
        arsort($candidate_totals);
        foreach ($candidate_totals as $group_id => $candidate_total) {
            foreach ($candidates_data[$candidate['group_id']]["factor_ids"] as $factor_id) {
                $candidates_points[$group_id][$factor_id]= $candidates_data[$group_id]["point"];
            }
            $candidate_names[$group_id] = $candidates_data[$group_id]["name"];
        }
        
        return 
        [
            $matrix_model->name,
            $factors_data,
            $candidates_points,
            $candidate_names,
            $candidate_totals
        ];
    }
}




