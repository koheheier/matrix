
@extends('layouts.base')

@section('title')
候補作成画面
@endsection

@section('main')

<form action="{{ route('create_candidates') }}" method="post" enctype="multipart/form-data">
    @csrf
    @foreach ($errors->all() as $error)
        <li style="color: red;">{{ $error }}</li>
    @endforeach
    
    @if($deficiency ?? false)
        <li style="color: red;">{{ $deficiency }}</li>
    @endif

    <h2>{{ $matrixName }}</h2>
    <button type="button" onClick="appendCandidate('', [])">追加</button>
    <template id="candidate_tmp">
        <div class="first_container">
            <input type="hidden" class="group_id">
            <div class="candidate_name">
                候補名：<input type="text" placeholder="候補名を入力" class="candidate_names">
            </div>
            <div class="second_container">
                @foreach($factors as $factor)
                <div class="candidate_factor">
                    <input type="hidden" value='{{ $factor["id"] }}' id="factor_id">
                    <div>
                        {{ $factor["name"] }} : {{ $factor["weight"] }}
                    </div>
                    <input type="text" placeholder="候補ポイントを入力" class="candidate_points">
                </div>
                @endforeach
            </div>
            <button type="button" class="deleteButton" onClick="deleteCandidate(this.id)">削除</button>
        </div>
    </template>
    <!-- redirect時に必要 -->
    <input type="hidden" value="{{ $matrixId }}" name="matrixId">
    <div id="candidates"></div>

    <button type="button" onClick="appendCandidate('', [])">追加</button>
        
    <button type='submit'>登録</button>
    <script>

        var candidates_tmp = document.getElementById('candidate_tmp');
        

        var group_id = {{ $group_id ?? 0 }};

        function appendCandidate(candidate_name, candidate_points){
            var clone = candidates_tmp.content.cloneNode(true);

            var candidate_name_elmt = clone.querySelector(".candidate_names");
            candidate_name_elmt.name = "candidate_names[" + group_id + "]";
            candidate_name_elmt.value = candidate_name ?? "";
            
            var group_id_elmt = clone.querySelector(".group_id");
            group_id_elmt.name = "candidate_group_ids[" + group_id + "]";
            group_id_elmt.value = group_id;

            /* ボタン削除用にid付与 */
            clone.querySelector('.first_container').setAttribute("id", "candidate_" + group_id);
            clone.querySelector('.first_container button').setAttribute("id", group_id);

            var candidate_factors = clone.querySelectorAll(".candidate_factor");
            candidate_factors.forEach(function(candidate_factor) {
                var factor_id = candidate_factor.querySelector("#factor_id").value;
                candidate_factor.querySelector(".candidate_points").name = "candidate_points[" + group_id + "][" + factor_id + "]";
                if ((candidate_points ?? null) || (candidate_points[factor_id] ?? null)) {
                    candidate_factor.querySelector('input[name="candidate_points[' + group_id + '][' + factor_id + ']').value = candidate_points[factor_id] ?? "";
                }
            });

            group_id++;
            document.getElementById('candidates').appendChild(clone);
        }

        /* 削除用 */
        function deleteCandidate(group_id){
            var candidate = document.getElementById('candidate_' + group_id);
            candidate.remove();
        }




        document.addEventListener('DOMContentLoaded', function(){
            if ( {{ session("hasFailed") ?? 0 }}) {
                var json_candidate_names = "{{ session('candidate_names') }}";
                var json_candidate_points = "{{ session('candidate_points') }}";
                if (json_candidate_names != "" & json_candidate_points != "") {
                    var candidate_names = JSON.parse(json_candidate_names.replace(/&quot;/g,'"'));
                    var candidate_points = JSON.parse(json_candidate_points.replace(/&quot;/g,'"'));
                    for (key in candidate_names) {
                        appendCandidate(candidate_names[key], candidate_points[key]);
                    }
                }
            }
        });


    </script>

</form>

@endsection

