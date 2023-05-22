<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matrix;
use App\Models\Factor;
use App\Models\Candidate;
use App\Services\GeneralService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class makeCandidatesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, GeneralService $generalService)
    {
        $matrixId = (int)$request->route('matrixId');

        if (!$generalService->checkOwn($matrixId, $request->user()->id)) {
            throw new AccessDeniedHttpException();
        }
        
        // 候補画面を表示する
        $matrix = Matrix::where('id', $matrixId)->first();

        if (is_null($matrix)) {
            throw new NotFoundHttpException("ありませんっ！！");
        }

        $factor_models = Factor::where('matrix_id', $matrixId)->get();
        if (count($factor_models) < 1) {
            return redirect("list")->with('feedback.error', '選択したマトリクスには、要素が設定されていません');    
        }
        $factors = [];
        
        foreach($factor_models as $factor_model){
           $factors[] = 
           [
            "id" => $factor_model->id,
            "name" => $factor_model->name,
            "weight" => $factor_model->weight
           ];
        }
        $candidate = Candidate::latest('id')->first();
        
        $group_id = $candidate ? $candidate->group_id : 1;
        
        return view("make_candidates")
            ->with("factors", $factors)
            ->with("matrixId", $matrix->id)
            ->with("matrixName", $matrix->name)
            ->with("group_id", $group_id)
            ->with('deficiency', session('deficiency'));
    }
}
