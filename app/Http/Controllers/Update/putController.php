<?php

namespace App\Http\Controllers\Update;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Update\UpdateRequest;
use App\Models\Matrix;
use App\Models\Factor;

class putController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateRequest $request)
    {
        $matrix_data = $request->getMatrix();
        $matrixId = $matrix_data["matrixId"];
        
        // 存在していることは確定なので、validationは省略
        $matrix = Matrix::where("id", $matrixId)->first();
        $matrix->name = $matrix_data["matrixName"];
        
        
        // 要素と重みをループさせてsave。その時、上記でsaveしたmatrixのidを使う
        $factors = $request->getFactors();
        
        $factorNames = $factors['factorNames'];
        $factorWeights = $factors['factorWeights'];
        $factorIds = $factors['factorIds'];
        
        // 空がないこと
        if (in_array("", $factorNames) || in_array("", $factorWeights)) {
            $request->session()->flash('deficiency', 'データに不備があります。');
            return redirect()->route('update_matrix_data', ["matrixId" => $matrixId]);
        }
        
        $matrix->update();
        
        // 全削除すると、候補と紐づいている奴があった時消せないので、ちゃんと更新してあげる
        for($i = 0; $i < count($factorNames); $i++) {
            $factor_model = Factor::where('id', $factorIds[$i])->first();
            $factor_model->name = $factorNames[$i];
            $factor_model->weight = $factorWeights[$i];
            $factor_model->matrix_id = $matrix->id;
            $factor_model->update();
        }
        
        return redirect()->route('list')->with('feedback.success', "マトリクスを編集しました");
    }
}
