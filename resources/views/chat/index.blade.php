@extends('layouts.user')

@section('content')
<div id="chat-box" style="position:fixed; bottom:20px; right:20px; width:320px; border:1px solid #ddd; background:#fff; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.15); z-index:9999;">
    <div style="background:#5b86e5; color:white; padding:10px; border-radius:10px 10px 0 0; font-weight:600;">
        Chat với Admin
    </div>

    <div id="messages" style="height:260px; overflow-y:auto; padding:12px; background:#fafafa;"></div>

    <form id="chat-form" style="display:flex; border-top:1px solid #eee; padding:8px; gap:6px;">
        {{-- nếu admin id khác thì thay 1 bằng id admin thực tế --}}
        <input type="hidden" name="receiver_id" 
       value="{{ \App\Models\User::where('role','admin')->value('id') ?? 1 }}">


        <input type="text" id="message-input" name="message" class="form-control" placeholder="Nhập tin nhắn..." style="border:1px solid #e5e7eb; border-radius:8px; padding:8px; flex:1;">
        <button type="submit" id="send-btn" class="btn" style="background:#5b86e5; color:#fff; border-radius:8px; padding:8px 12px; border:none;">Gửi</button>
    </form>
</div>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const CURRENT_USER_ID = @json(Auth::id());
    const FETCH_URL = "{{ route('chat.fetch') }}";
    const SEND_URL = "{{ route('chat.send') }}";

    // CSRF cho POST
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Escape HTML để tránh XSS
    function escapeHtml(str) {
        if (!str && str !== 0) return '';
        return String(str).replace(/[&<>"'\/]/g, function (s) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
                '/': '&#x2F;'
            };
            return map[s];
        });
    }

    function renderMessages(data) {
        let html = '';
        data.forEach(function(msg) {
            // sender info có thể có msg.sender.name nếu backend trả relationship
            const senderName = (msg.sender && msg.sender.name) ? escapeHtml(msg.sender.name) : 'Người dùng';
            const messageText = escapeHtml(msg.message);

            if (msg.sender_id == CURRENT_USER_ID) {
                // Tin nhắn của chính user (bên phải)
                html += `
                    <div style="display:flex; justify-content:flex-end; margin-bottom:6px;">
                        <div style="max-width:78%; text-align:right;">
                            <div style="display:inline-block; background:#5b86e5; color:#fff; padding:8px 12px; border-radius:16px;">
                                ${messageText}
                            </div>
                            <div style="font-size:11px; color:#6b7280; margin-top:4px;">Bạn</div>
                        </div>
                    </div>
                `;
            } else {
                // Tin nhắn từ admin (bên trái)
                html += `
                    <div style="display:flex; justify-content:flex-start; margin-bottom:6px;">
                        <div style="max-width:78%; text-align:left;">
                            <div style="display:inline-block; background:#f1f1f1; color:#111827; padding:8px 12px; border-radius:16px;">
                                ${messageText}
                            </div>
                            <div style="font-size:11px; color:#6b7280; margin-top:4px;">${senderName}</div>
                        </div>
                    </div>
                `;
            }
        });

        $('#messages').html(html);
        const messagesEl = $('#messages')[0];
        if (messagesEl) messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function loadMessages() {
        $.get(FETCH_URL)
            .done(function(data) {
                renderMessages(data);
            })
            .fail(function(err) {
                console.error('Lỗi fetch messages:', err);
            });
    }

    // gửi tin nhắn
    $('#chat-form').on('submit', function(e) {
        e.preventDefault();
        const $input = $('#message-input');
        const text = $input.val().trim();
        if (!text) return;

        $('#send-btn').prop('disabled', true);

        $.post(SEND_URL, $(this).serialize())
    .done(function() {
        $input.val('');
        loadMessages();
    }) // <-- thêm dấu đóng ở đây
    .fail(function(err) {
        console.error('Lỗi gửi tin nhắn:', err);
        alert('Gửi tin nhắn thất bại. Vui lòng thử lại.');
    })
    .always(function() {
        $('#send-btn').prop('disabled', false);
    });

    });

    // Polling: load mỗi 3s (AJAX polling)
    $(document).ready(function() {
        loadMessages();
        setInterval(loadMessages, 3000);
    });
</script>
@endsection
