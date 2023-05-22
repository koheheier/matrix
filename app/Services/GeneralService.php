<?php
namespace App\Services;

use App\Models\Matrix;

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
}




