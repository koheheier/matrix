<x-layout.base title="マトリクス一覧">

@if (session('feedback.success'))
    <p style="color: green;">{{ session('feedback.success') }}</p>
@endif
@if (session('feedback.error'))
    <p style="color: red;">{{ session('feedback.error') }}</p>
@endif

@foreach($matrixDataList as $matrix)
    <details>
        <summary>{{ $matrix["name"] }}</summary>
        @if(\Illuminate\Support\Facades\Auth::id() === $matrix["user_id"])
            <div>
                <li class="list"><a href="{{ route('first_routing', [
                    'target' => 'update_matrix_data',
                    'arg' => $matrix['id']
                    ]) }}">要素・マトリクス名編集</a></li>
                    @if($matrix["hasCandidates"])
                    <li class="list"><a href="{{ route('first_routing', [
                        'target' => 'update_candidates',
                        'arg' => $matrix['id']
                        ]) }}">候補編集</a></li>
                    @else
                    <li class="list"><a href="{{ route('first_routing', [
                        'target' => 'make_candidates',
                        'arg' => $matrix['id']
                        ]) }}">候補作成</a></li>
                    @endif
                <li class="list"><a href="{{ route('first_routing', [
                    'target' => 'ranking',
                    'arg' => $matrix['id']
                    ]) }}">候補ランキング</a></li>
            </div>
        @else
            編集できませんよ、お体に触りますよ
        @endif
    </details>
@endforeach
</x-layout.base>