<x-layout.base title="マトリクス一覧">
    <div class="text-xl">
        {{ $matrix_name }}
    </div>

    <div class="justify-items-center rank_field" id="">
        @foreach($candidates_points as $group_id => $candidate_points)
            <div class="candidate bg-red-200 border candidate_{{ $group_id }} rank_{{ $loop->index }}" id="">
                <h1>{{ $candidate_names[$group_id] }}</h1>    
                @foreach($candidate_points as $factor_id => $candidate_point)
                    <div class="factor_{{ $factor_id }} text-black bg-red-200" onClick="activeSwitch({{ $factor_id }})">
                        <input type="hidden" class="candidate_factor_points_{{ $factor_id }}" value="{{ $factors_data[$factor_id]['weight'] * $candidate_point }}">
                        <input type="hidden" class="group_id_{{ $factor_id }}" value="{{ $group_id }}">
                        <input type="hidden" value="1" class="calcBool_{{ $factor_id }}">
                        {{ $factors_data[$factor_id]["name"] }} : {{ $factors_data[$factor_id]["weight"] }} × ポイント：{{ $candidate_point }}
                    </div>
                    @endforeach
                    <input type="hidden" id="{{ $group_id }}" value="{{ $candidate_totals[$group_id] }}">
                    合計：<span id="total_{{ $group_id }}">{{ $candidate_totals[$group_id] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        

        /* 要素クリックによって非アクティブ状態にし、再計算 */
        function activeSwitch(factor_id){
            var selected_factors = document.getElementsByClassName("factor_" + factor_id);
            var calcBools = document.getElementsByClassName("calcBool_" + factor_id);
            var candidate_factor_points = document.getElementsByClassName("candidate_factor_points_" + factor_id);
            var group_ids = document.getElementsByClassName("group_id_" + factor_id);
            var totals = [];
            for (var i = 0; i < calcBools.length; i++) {
                var group_id = group_ids[i].value;
                if (calcBools[i].value > 0) {
                    calcBools[i].value = 0;
                    selected_factors[i].classList.replace('bg-red-200', 'bg-gray-200');
                    selected_factors[i].classList.replace('text-black', 'text-gray-400');
                    //  合計から引く
                    document.getElementById('total_' + group_id).textContent
                        = parseInt(document.getElementById('total_' + group_id).textContent, 10) - parseInt(candidate_factor_points[i].value);
                } else {
                    calcBools[i].value = 1;
                    selected_factors[i].classList.replace('bg-gray-200', 'bg-red-200');
                    selected_factors[i].classList.replace('text-gray-400', 'text-black');
                    //  合計に足す
                    document.getElementById('total_' + group_id).textContent
                        = parseInt(document.getElementById('total_' + group_id).textContent, 10) + parseInt(candidate_factor_points[i].value);
                }
                totals[group_id] = parseInt(document.getElementById('total_' + group_id).textContent);
                // 数字の大きい順に入れ替える。group_idも入れ替わる
                
                // candidate_boxesをループし、totalsの一番大きい奴を取得して格納。消す必要がある？


            }

        }
    </script>

<div class="rank_field">
    <div class="rank_1">1</div>
    <div class="rank_2">2</div>
    <div class="rank_0">3</div>
</div>





<style>
    /* 候補ごとのポイントと要素重みを計算し、順番を返す */

    /* 受け取った順番で、候補ボックスを並べ替える */

    .rank_field {
        display: flex;
        flex-direction:column;
    }

    .rank_0 {
        order: 4;
    }
    .rank_1 {
        order: 1;
    }
    .rank_2 {
        order: 2;
    }
    .rank_3 {
        order: 3;
    }
    .rank_4 {
        order: 0;
    }
</style>
</x-layout.base>

