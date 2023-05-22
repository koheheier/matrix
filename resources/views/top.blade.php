



@extends('layouts.base')

@section('title')
top画面
@endsection

@section('main')
<a href="list" method="get">一覧画面に移動します</a><br>
<a href="make" method="get">作成画面に移動します</a>
<form action="{{ route('create') }}" method="post" enctype="multipart/form-data">
    @csrf
    @foreach ($errors->all() as $error)
        <li style="color: red;">{{$error}}</li>
    @endforeach
    @if($deficiency ?? false)
        <li style="color: red;">{{ $deficiency }}</li>
    @endif

    <span>マトリクス名</span>
    <input type="text" name="matrixName" id="matrixName" placeholder="マトリクス名入力" value="{{ session('matrixName') }}"><br>
    <button type="button" onClick="appendFactor()">追加</button>
    

    <template id="factor_template">
        <dev class="factor">
            <span>マトリクス要素</span>
            <input type="text" name="factorNames[]" placeholder="要素名み入力" value="" class="factorName"><br>
            <span>要素重み</span>
            <input type="text" name="factorWeights[]" placeholder="要素重み入力" value="" class="factorWeight"><br>
            <button type="button" class="deleteButton" onClick="deleteBtn(this.id)">削除</button>
            <hr>
        </dev>
    </template>


    <div id="factors"></div>
    <button type="button" onClick="appendFactor('', '', 1)">追加</button>
    
    
    
    <button type='submit'>登録</button>
    <script>


        var factor = document.getElementById('factor_template');
        let factor_count = 0;

        function appendFactor(factorName, factorWeight){
            var clone = factor.content.cloneNode(true);
            // ここで、cloneの子要素のvalueに、セッションで保存した入力値を保存させてappendchildさせる
            clone.querySelector('.factorName').value = factorName ?? "";
            clone.querySelector('.factorWeight').value = factorWeight ?? "";
            clone.querySelector('.factor').setAttribute("id", "factor_" + factor_count);
            clone.querySelector('.factor button').setAttribute("id", factor_count);
            factor_count++;
            document.getElementById('factors').appendChild(clone);
        }

        function deleteBtn (button_id) {
            var factor = document.getElementById('factor_' + button_id);
            factor.remove();
        }

        document.addEventListener('DOMContentLoaded', function(){
            var factorLength = {{ session('factorLength') ?? 0 }};
            var hasFailed = {{ session('hasFailed') ?? 0 }};
            if (hasFailed || factorLength) {
                var json_factorNames = "{{ session('factorNames') }}";
                var json_factorWeights = "{{ session('factorWeights') }}";
                /** factorNames, factorWeightsを配列に */
                var factorNames = JSON.parse(json_factorNames.replace(/&quot;/g,'"'));
                var factorWeights = JSON.parse(json_factorWeights.replace(/&quot;/g,'"'));
                
                for (let i = 0; i < factorLength; i++) {
                    appendFactor(factorNames[i], factorWeights[i]);
                }
            }
        });
    </script>

</form>

リストですよ
@endsection

