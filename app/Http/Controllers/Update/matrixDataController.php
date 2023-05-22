<?php

namespace App\Http\Controllers\Update;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Matrix;
use App\Models\Factor;
use App\Services\GeneralService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class matrixDataController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, GeneralService $generalService)
    {
        // マトリクスidを貰い、それの存在チェックとデータ取得
        
        $matrixId = (int)$request->route('matrixId');
        if (!$generalService->checkOwn($matrixId, $request->user()->id)) {
            throw new AccessDeniedHttpException();
        }

        $matrix = Matrix::where('id', $matrixId)->first();
        if(is_null($matrix)){
            throw new NotFoundHttpException('マトリクスがありません');
        }
        
        // マトリクスidを持つ候補群
        $factor_models = Factor::where('matrix_id', $matrixId)->get();
        $factors = [];
        foreach($factor_models as $factor_model){
            
            $factors[] = 
            [
                "name" => $factor_model->name,
                "weight" => $factor_model->weight
            ];
        }
        $facs = json_encode($factors, JSON_UNESCAPED_UNICODE);
        
        return view("update.update_matrix")
            ->with("facs", $facs)
            ->with("matrixId", $matrix->id)
            ->with("matrixName", $matrix->name)
            ->with('deficiency', session('deficiency'));
    }
}
