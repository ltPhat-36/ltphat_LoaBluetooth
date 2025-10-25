@extends('layouts.admin')

@section('content')
<div style="display:flex; height:100vh;">
    {{-- Danh sách user --}}
    <div style="width:250px; border-right:1px solid #ddd; overflow-y:auto;">
        <h5 style="padding:10px; background:#5b86e5; color:#fff;">Danh sách khách hàng</h5>
        <ul id="user-list" style="list-style:none; margin:0; padding:0;">
            @foreach(\App\Models\User::where('role','customer')->get() as $u)
                <li class="user-item" data-id="{{ $u->id }}" style="padding:10px; cursor:pointer; border-bottom:1px solid #eee;">
                    {{ $u->name }} <br>
                    <small style="color:#666;">{{ $u->email }}</small>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Khung chat --}}
    <div style="flex:1; display:flex; flex-direction:column;">
        <div id="chat-header" style="background:#5b86e5; color:white; padding:10px;">
            Chọn khách hàng để chat
        </div>

        <div id="messages" style="flex:1; overflow-y:auto; padding:12px; background:#fafafa;"></div>

        <form id="chat-form" style="display:flex; border-top:1px solid #eee; padding:8px; gap:6px;">
            <input type="hidden" name="receiver_id" id="receiver_id" value="">
            <input type="text" id="message-input" name="message" class="form-control" placeholder="Nhập tin nhắn..." disabled>
            <button type="submit" id="send-btn" class="btn btn-primary" disabled>Gửi</button>
        </form>
    </div>
</div>

<script>
    const CURRENT_USER_ID = @json(Auth::id());
    let ACTIVE_USER_ID = null;

    const FETCH_URL = "{{ route('chat.fetch') }}"; // cần truyền receiver_id
    const SEND_URL = "{{ route('chat.send') }}";

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Chọn user
    $(document).on('click', '.user-item', function() {
        $('.user-item').removeClass('active');
        $(this).addClass('active');

        ACTIVE_USER_ID = $(this).data('id'); // customer id
        $('#receiver_id').val(ACTIVE_USER_ID);
        $('#chat-header').text('Chat với ' + $(this).text().trim());
        $('#message-input, #send-btn').prop('disabled', false);

        // gọi fetch messages với đúng parameter
        $.get(FETCH_URL, { customer_id: ACTIVE_USER_ID })
            .done(renderMessages)
            .fail(err => console.error("Fetch lỗi:", err));
    });

    function loadMessages() {
        if (!ACTIVE_USER_ID) return;
        $.get(FETCH_URL, { customer_id: ACTIVE_USER_ID }) // đúng param
            .done(renderMessages)
            .fail(err => console.error(err));
    }

    function renderMessages(data) {
        let html = '';
        data.forEach(function(msg) {
            const messageText = msg.message;
            if (msg.sender_id == CURRENT_USER_ID) {
                html += `<div style="text-align:right;">
                            <div style="display:inline-block; background:#5b86e5; color:#fff; padding:8px 12px; border-radius:16px; margin:4px 0;">
                                ${messageText}
                            </div>
                         </div>`;
            } else {
                html += `<div style="text-align:left;">
                            <div style="display:inline-block; background:#f1f1f1; padding:8px 12px; border-radius:16px; margin:4px 0;">
                                ${messageText}
                            </div>
                         </div>`;
            }
        });
        $('#messages').html(html);
        $('#messages').scrollTop($('#messages')[0].scrollHeight);
    }

    // Gửi tin nhắn
    $('#chat-form').on('submit', function(e){
        e.preventDefault();
        const $input = $('#message-input');
        const text = $input.val().trim();
        if(!text) return;

        // Gửi tin nhắn user trước
        $.post("{{ route('chat.send') }}", { 
            message: text, 
            receiver_id: "{{ \App\Models\User::where('role','admin')->value('id') }}" 
        })
        .done(function(){
            $input.val('');
            loadMessages();

            // Gọi FAQ thay vì AI
                
        });
    });

    // Auto reload messages
    setInterval(loadMessages, 3000); // polling
</script>

@endsection
