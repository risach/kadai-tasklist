@if (count($microposts) > 0)
    <ul class="list-unstyled">
        @foreach ($microposts as $micropost)
            <li class="media mb-3">
                    <div>
                        {{-- 投稿内容 --}}
                        <p class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                    </div>
                     <div>
                        @if (Auth::id() == $tasklist->user_id)
                            {{-- 投稿削除ボタンのフォーム --}}
                            {!! Form::open(['route' => ['tasklits.destroy', $tasklist->id], 'method' => 'delete']) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
            </li>
        @endforeach
    </ul>
@endif