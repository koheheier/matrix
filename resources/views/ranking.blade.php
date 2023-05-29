<x-layout.base title="マトリクス一覧">
    <div class="text-xl">
        {{ $matrix_name }}
    </div>
    @php
        $bg_color_classes = 
        [
            "bg-yellow-200",
            "bg-gray-500",
            "bg-amber-600"
        ];
        $bg_color_classes_json = json_encode($bg_color_classes);
    @endphp
    <div class="justify-items-center">
        @foreach($candidates_points as $group_id => $candidate_points)
            <div class="candidate border candidate_{{ $group_id }}">
                <h1 class="bg-black text-3xl text-white">
                    {{ $candidate_names[$group_id] }}
                    @if($loop->index < 3)
                        @if($loop->first)
                            👑
                        @endif
                        @php
                            $bg_class = $bg_color_classes[$loop->index];
                        @endphp
                    @else
                        @php
                            $bg_class = "bg-white";
                        @endphp
                    @endif
                </h1>
                @foreach($candidate_points as $factor_id => $candidate_point)
                    <div class="factor_{{ $factor_id }} text-black {{ $bg_class }} text-3xl" onClick="activeSwitch({{ $factor_id }})">
                        <input type="hidden" class="candidate_factor_points_{{ $factor_id }}" value="{{ $factors_data[$factor_id]['weight'] * $candidate_point }}">
                        <input type="hidden" class="group_id_{{ $factor_id }}" value="{{ $group_id }}">
                        <input type="hidden" value="1" class="calcBool_{{ $factor_id }}">
                        {{ $factors_data[$factor_id]["name"] }} : {{ $factors_data[$factor_id]["weight"] }} × ポイント：{{ $candidate_point }}
                    </div>
                @endforeach
                    <input type="hidden" id="{{ $group_id }}" value="{{ $candidate_totals[$group_id] }}">
                    <div class="bg-red-500 font-bold text-2xl text-white">
                        合計：<span id="total_{{ $group_id }}">{{ $candidate_totals[$group_id] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        var bg_color_classes = JSON.parse('<?php echo $bg_color_classes_json; ?>');

        /* 要素クリックによって非アクティブ状態にし、再計算 */
        function activeSwitch(factor_id){
            var selected_factors = document.getElementsByClassName("factor_" + factor_id);
            var calcBools = document.getElementsByClassName("calcBool_" + factor_id);
            var candidate_factor_points = document.getElementsByClassName("candidate_factor_points_" + factor_id);
            var group_ids = document.getElementsByClassName("group_id_" + factor_id);
            var totals = [];
            var bg_color_class = "";
            for (var i = 0; i < calcBools.length; i++) {
                if (i < 3) {
                    bg_color_class = bg_color_classes[i];
                } else {
                    bg_color_class = "bg-white";
                }
                var group_id = group_ids[i].value;
                if (calcBools[i].value > 0) {
                    calcBools[i].value = 0;
                    selected_factors[i].classList.replace(bg_color_class, 'bg-gray-200');
                    selected_factors[i].classList.replace('text-black', 'text-gray-400');
                    //  合計から引く
                    document.getElementById('total_' + group_id).textContent
                        = parseInt(document.getElementById('total_' + group_id).textContent, 10) - parseInt(candidate_factor_points[i].value);
                } else {
                    calcBools[i].value = 1;
                    selected_factors[i].classList.replace('bg-gray-200', bg_color_class);
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

</x-layout.base>

