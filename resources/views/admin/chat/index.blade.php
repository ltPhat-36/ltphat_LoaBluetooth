@extends('layouts.admin')

@section('title', 'Quản lý Chat')

@section('content')
<div class="container">
    <h3 class="mb-4">💬 Tin nhắn từ khách hàng</h3>

    <div id="messages" style="height:400px; overflow-y:auto; border:1px solid #ddd; padding:15px; border-radius:8px; background:#fff;"></div>

    <form id="chat-form" class="mt-3 d-flex">
    <select name="receiver_id" id="receiver_id" class="form-select me-2" required>
        @foreach(\App\Models\User::where('role','user')->get() as $user)
            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
        @endforeach
    </select>

    <input type="text" id="message-input" name="message" class="form-control me-2" placeholder="Nhập tin nhắn...">
    <button type="submit" id="send-btn" class="btn btn-primary">Gửi</button>
</form>

</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const CURRENT_USER_ID = @json(Auth::id());
    const FETCH_URL = "{{ route('admin.chat.fetch') }}";
    const SEND_URL = "{{ route('admin.chat.send') }}";

    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    });

    function renderMessages(data) {
        let html = '';
        data.forEach(function(msg) {
            const senderName = (msg.sender && msg.sender.name) ? msg.sender.name : 'Người dùng';
            if (msg.sender_id == CURRENT_USER_ID) {
                html += `<div class="text-end mb-2">
                    <div class="d-inline-block p-2 bg-primary text-white rounded">${msg.message}</div>
                    <div style="font-size:12px; color:gray;">Bạn</div>
                </div>`;
            } else {
                html += `<div class="text-start mb-2">
                    <div class="d-inline-block p-2 bg-light rounded">${msg.message}</div>
                    <div style="font-size:12px; color:gray;">${senderName}</div>
                </div>`;
            }
        });
        $('#messages').html(html);
        $('#messages').scrollTop($('#messages')[0].scrollHeight);
    }

    function loadMessages() {
        $.get(FETCH_URL)
            .done(renderMessages)
            .fail(err => console.error("Fetch lỗi:", err));
    }

    $('#chat-form').on('submit', function(e){
        e.preventDefault();
        const text = $('#message-input').val().trim();
        if (!text) return;

        // Mặc định gửi cho user cuối cùng (ví dụ khách hàng gửi gần nhất)
        const lastMsg = $('#messages div').last();
        const receiverId = $('#receiver_id').val();


        $.post(SEND_URL, {message: text, receiver_id: receiverId})
            .done(() => {
                $('#message-input').val('');
                loadMessages();
            })
            .fail(() => alert('Gửi tin nhắn thất bại.'));
    });

    $(document).ready(function(){
        loadMessages();
        setInterval(loadMessages, 3000);
    });
</script>
@endsection
