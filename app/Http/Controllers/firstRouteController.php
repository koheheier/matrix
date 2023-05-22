<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class firstRouteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $target = $request->route('target');
        $arg = $request->route('arg');

        // このためのcontroller
        session()->forget('hasFailed');

        // targetによってargを判断し渡す
        // matrixIdと判断
        switch ($target) {
            // 投げるパラメータ同じなので
            case "make_candidates":
            case "update_matrix_data":
            case "update_candidates":
            case "ranking":
                return redirect()->route($target, ["matrixId" => $arg]);
            default:
                $request->session()->flash('feedback.error', '遷移先が誤りです');
                return redirect()->route('list');
        }
    }
}
