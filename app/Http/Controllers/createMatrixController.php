<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateMatrixRequest;
use App\Models\Matrix;
use App\Models\Factor;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\createMail;


class createMatrixController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateMatrixRequest $request)
    {
        // matrixのデータをsaveする
        $matrix = new Matrix;
        $matrix->name = $request->getMatrix();
        // TODO user_idをmatricesに追加する
        $matrix->user_id = $request->getUserId();
        $user = User::where("id", $matrix->user_id)->first();
        
        
        // 要素と重みをループさせてsave。その時、上記でsaveしたmatrixのidを使う
        $factors = $request->getFactors();
        $factorNames = $factors['factorNames'];
        $factorWeights = $factors['factorWeights'];
        // 空がないこと
        if (in_array("", $factorNames) || in_array("", $factorWeights)) {
            $request->session()->flash('deficiency', 'データに不備があります。');
            return redirect()->route('make_matrix');
        }
        
        $matrix->save();
        for($i = 0; $i < count($factorNames); $i++) {
            // 要素0：名前、要素1：重み
            $factor_model = new factor;
            $factor_model->name = $factorNames[$i];
            $factor_model->weight = $factorWeights[$i];
            $factor_model->matrix_id = $matrix->id;
            $factor_model->save();
        }

        // メール送信はここか
        Mail::to($user)->send(new createMail($user, $matrix));

        session()->forget("matrixName");
        session()->forget("factorNames");
        session()->forget("factorWeights");
        return redirect()->route('list')->with('feedback.success', "マトリクスを登録しました!!!!どうだ見たか！");
        
    }
}
