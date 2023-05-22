
@extends('layouts.base')

@section('title')
候補作成画面
@endsection

@section('main')

<form action="{{ route('update.candidates') }}" method="post" enctype="multipart/form-data">
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
                    <!-- jsで -->
                    <input type="hidden" value='{{ $factor["id"] }}' id="factor_id">
                    <div>
                        {{ $factor["name"] }} : {{ $factor["weight"] }}
                    </div>
                    <input type="text" placeholder="候補ポイントを入力" class="candidate_points">
                </div>
                @endforeach
            </div>
        </div>
    </template>
    <input type="hidden" value="0" name="candidateNumber" id="candidateNumber">
    <!-- redirect時に必要 -->
    <input type="hidden" value="{{ $matrixId }}" name="matrixId">
    <div id="candidates"></div>

    <button type="button" onClick="appendCandidate('', [])">追加</button>
        
    <button type='submit'>登録</button>
    <script>

        var candidates_tmp = document.getElementById('candidate_tmp');
        var candidateNumber = 0;
        var candidate_number = document.getElementById("candidateNumber");

        var group_id = {{ $group_id }};

        function appendCandidate(candidate_name, candidate_points){
            var clone = candidates_tmp.content.cloneNode(true);
            
            var candidate_name_elmt = clone.querySelector(".candidate_names");
            candidate_name_elmt.name = "candidate_names[" + candidateNumber + "]";
            candidate_name_elmt.value = candidate_name ?? "";
            
            var group_id_elmt = clone.querySelector(".group_id");
            group_id_elmt.name = "candidate_group_ids[" + candidateNumber + "]";
            group_id_elmt.value = group_id;

            var candidate_factors = clone.querySelectorAll(".candidate_factor");
            candidate_factors.forEach(function(candidate_factor) {
                var factor_id = candidate_factor.querySelector("#factor_id").value;
                candidate_factor.querySelector(".candidate_points").name = "candidate_points[" + candidateNumber + "][" + factor_id + "]";
                if ((candidate_points ?? null) || (candidate_points[factor_id] ?? null)) {
                    candidate_factor.querySelector('input[name="candidate_points[' + candidateNumber + '][' + factor_id + ']').value = candidate_points[factor_id] ?? "";
                }
            });

            group_id++;
            candidateNumber++;
            candidate_number.value = candidateNumber;
            document.getElementById('candidates').appendChild(clone);
        }



        document.addEventListener('DOMContentLoaded', function(){
            candidate_number.value = candidateNumber;
            var sessioncandidateNumber = {{ session('candidateNumber') ?? 0 }};
            var hasFailed = {{ session('hasFailed') ?? 0 }};
            if (hasFailed || sessioncandidateNumber) {
                var json_candidate_names = "{{ session('candidate_names') }}";
                var json_candidate_points = "{{ session('candidate_points') }}";
                var candidate_names = JSON.parse(json_candidate_names.replace(/&quot;/g,'"'));
                var candidate_points = JSON.parse(json_candidate_points.replace(/&quot;/g,'"'));
                for (key in candidate_names) {
                    appendCandidate(candidate_names[key], candidate_points[key]);
                }
            } else {
                var updateCandidates = JSON.parse("{{ $cans }}".replace(/&quot;/g,'"'));

            }
        });


    </script>

</form>

候補編集しようぜ
@endsection

