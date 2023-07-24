<?php
namespace App\Services;

class CandidatesService {

    // 候補保存前に、入力値と型が一致しているかを見る
    public function getErrorParametersCandidatesData(array $candidate_names, array $candidate_points) {
        $errorParameters = [];
        foreach ($candidate_names as $key => $name) {
            foreach ($candidate_points[$key] as $point) {
                $errorParameter = "入力値に誤りがあります。\n";
                if (gettype($name) != "string") {
                    $errorParameter = "名前：" . $name . "\n";
                }
                if (gettype($point) != "int") {
                    $errorParameter .= "ポイント：" . $point . "\n";
                }
                if ($errorParameter != "入力値に誤りがあります。\n") $errorParameters[$key] = $errorParameter;
            }
        }
        return $errorParameters;
    }
}