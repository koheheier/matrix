<x-layout.base title="マトリクス一覧">
    <div class="text-xl">
        {{ $matrix_name }}
    </div>

    <div class="justify-items-center" id="rank_field">
        @foreach($candidates_data_list as $group_id => $candidates)
            <div class="candidate bg-red-200 border candidate_{{ $group_id }}">
                <h1>{{ $candidate_names[$group_id] }}</h1>    
                @foreach($candidates as $factor_id => $candidate_point)
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
        @endforeach
    </div>

    <script>
        // 最初に合計を計算して表示する
        document.addEventListener('DOMContentLoaded', function(){
            
        });


        /* 要素クリックによって非アクティブ状態にし、再計算 */
        function activeSwitch(factor_id){
            var selected_factors = document.getElementsByClassName("factor_" + factor_id);
            var calcBools = document.getElementsByClassName("calcBool_" + factor_id);
            var candidate_factor_points = document.getElementsByClassName("candidate_factor_points_" + factor_id);
            var group_ids = document.getElementsByClassName("group_id_" + factor_id);
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
            }
        }

        
        /* 候補ごとのポイントと要素重みを計算し、順番を返す */

        /* 受け取った順番で、候補ボックスを並べ替える */

    </script>
</x-layout.base>

