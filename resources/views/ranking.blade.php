<x-layout.base title="マトリクス一覧">
    <div class="text-xl">
        {{ $matrix_name }}
    </div>

    <div>
        @foreach($candidates_data_list as $group_id => $candidates)
            <div class="bg-red-200 border candidate_{{ $group_id }}">
                <h1>{{ $candidate_names[$group_id] }}</h1>    
                @foreach($candidates as $factor_id => $candidate_point)
                    <div class="factor_{{ $factor_id }} text-black bg-red-200" onClick="activeSwitch({{ $factor_id }})">
                        <input type="hidden" value="1" class="calcBool_{{ $factor_id }}">
                        {{ $factors_data[$factor_id]["name"] }} : {{ $factors_data[$factor_id]["weight"] }} × ポイント：{{ $candidate_point }}
                    </div>
                @endforeach
                <div>合計：</div>
            </div>
        @endforeach
    </div>

    <script>
        /* 要素クリックによって非アクティブ状態にし、再計算 */
        function activeSwitch(factor_id){
            var selected_factors = document.getElementsByClassName("factor_" + factor_id);
            var calcBools = document.getElementsByClassName("calcBool_" + factor_id);
            for (var i = 0; i < calcBools.length; i++) {
                if (calcBools[i].value > 0) {
                    calcBools[i].value = 0;
                    selected_factors[i].classList.replace('bg-red-200', 'bg-gray-200');
                    selected_factors[i].classList.replace('text-black', 'text-gray-400');
                } else {
                    calcBools[i].value = 1;
                    selected_factors[i].classList.replace('bg-gray-200', 'bg-red-200');
                    selected_factors[i].classList.replace('text-gray-400', 'text-black');
                }
            }
        }
        
        /* 候補ごとのポイントと要素重みを計算し、順番を返す */

        /* 受け取った順番で、候補ボックスを並べ替える */

    </script>
</x-layout.base>

