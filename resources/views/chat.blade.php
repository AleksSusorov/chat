@extends('layouts.app2')

@section('content')
<div class="container">

    <div id="chat">
        <div class="row" style="height: 800px">
            <div class="col col-4 col-dialogs">
                @foreach($dialogsList as $dialogRow)
                    <a class="row dialog" href="/dialogs/{{ $dialogRow['id'] }}" dialog-id="{{ $dialogRow['id'] }}">
                        <div class="col-3">
                            <img src="/storage/{{ $dialogRow['userTo']->image }}" alt="">
                        </div>
                        <div class="col-7 p-0">
                            <span class="user-name">{{ $dialogRow['userTo']->name }}</span><br>
                            @if(!empty($dialogRow['lastMessage']))
                                <span class="last-message">@php echo $dialogRow['lastMessage']->user_id == auth()->user()->id ? 'Вы: ' : '' @endphp
                                    {{ Str::limit($dialogRow['lastMessage']->message, 23) }}
                                </span>
                            @endif
                        </div>
                        @if(!empty($dialogRow['lastMessage']))
                            <div class="col-2 dialog-time">{{ $dialogRow['lastMessage']->created_at->diffForHumans(null, null, true) }}</div>
                        @endif
                    </a>
                @endforeach
            </div>
            <div class="col col-8 col-chat" style="height: 800px">
                <div class="row chat-head align-items-center">
                    <div class="col-1"><img src="/storage/{{ $userTo->image }}" alt=""></div>
                    <div class="col">
                        <span class="h5">{{ $userTo->name }}</span><br>
                        <span class="last-message">{{ $userTo->location }}</span>
                    </div>
                </div>
                <div class="row chat-body">
                    @php $i = 0; $j = 0; @endphp
                    @foreach($dialog->messages as $message)
                        @if($message->user_id == auth()->user()->id)
                            <div class="col-12 user-message mb-3">
                                <div class="row">
                                    <div class="col-2 message-img"><img src="/storage/{{ $userFrom->image }}" @if($i > 0) style="opacity: 0;" @endif></div>
                                    <div class="col-8 message-text">{{ $message->message }}</div>
                                    <div class="col-2 message-time">{{ str_replace('назад', '', $message->created_at->diffForHumans(null, null, true)) }}</div>
{{--                                    <div class="col-2 message-time">{{ $message->created_at->format('H:i') }}</div>--}}
                                </div>
                            </div>
                            @php $i++; $j = 0; @endphp
                        @else
                            <div class="col-12 user-message mb-3">
                                <div class="row" style="flex-direction: row-reverse">
                                    <div class="col-2 message-img" style="text-align: right;"><img src="/storage/{{ $userTo->image }}" @if($j > 0) style="opacity: 0;" @endif></div>
                                    <div class="col-8 message-text">{{ $message->message }}</div>
                                    <div class="col-2 message-time" style="text-align: right;">{{ str_replace('назад', '', $message->created_at->diffForHumans(null, null, true)) }}</div>
{{--                                    <div class="col-2 message-time" style="text-align: right;">{{ $message->created_at->format('H:i') }}</div>--}}
                                </div>
                            </div>
                            @php $j++; $i = 0; @endphp
                        @endif
                    @endforeach
                    @error('send-message')
                        <div class="alert alert-danger alert-dismissible fade show w-100" role="alert">
                            {{ $message }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @enderror
                </div>
                <form class="row send-message align-items-center" action="{{ route('message.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id-dialog" value="{{ $dialog->id }}">
                    <textarea name="send-message" id="send-message" class="col-11" placeholder="Введите ваше сообщение"></textarea>
                    <button type="submit"><i class="fas fa-paper-plane fa-1x" style="color: white;"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Scroll
        let chatBody = $('.chat-body');
        chatBody.scrollTop(chatBody.prop('scrollHeight'));

        let form = $('.send-message');
        let textarea = $('textarea');

        // Form submit
        textarea.on('keydown', function( e ) {
            if( e.keyCode === 13 ) {
                e.preventDefault();
                form.submit();
            }
        });

        // Dialog selected
        $('.dialog').each(function () {
           if ( $(this).attr('dialog-id') === (location.href).slice(-1)) {
               $(this).css({
                   'backgroundColor': '#4A576B',
               });
           }
        });

        // Adaptive
        if($(window).width() < 600) {
            $('main').removeClass('py-4');

            $('.col-dialogs').removeClass('col-4').addClass('col-12').hide();
            $('.col-chat').removeClass('col-8').addClass('col-12');


            $('body').prepend('<button  class="btn return-btn">Диалоги</button>');

            $('.return-btn').click(function () {
                $('.col-dialogs').show();
                $('.col-chat').hide();
                $('.return-btn').hide()
            });

        }
    });
</script>
@endsection
