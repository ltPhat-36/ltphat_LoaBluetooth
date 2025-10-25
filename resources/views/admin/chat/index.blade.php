@extends('layouts.admin')

@section('title', 'Quản lý Chat')

@section('content')
<div style="display:flex; height:100vh;">
    {{-- Danh sách khách hàng --}}
    <div style="width:250px; border-right:1px solid #ddd; overflow-y:auto;">
        <h5 style="padding:10px; background:#5b86e5; color:#fff;">Danh sách khách hàng</h5>
        <ul id="user-list" style="list-style:none; margin:0; padding:0;">
            @foreach(\App\Models\User::where('role','customer')->get() as $customer)
                <li class="user-item" data-id="{{ $customer->id }}" style="padding:10px; cursor:pointer; border-bottom:1px solid #eee;">
                    {{ $customer->name }} <br>
                    <small style="color:#666;">{{ $customer->email }}</small>
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

        <form id="chat-form" style="display:flex; border-top:1px solid #eee; padding:8px; gap:6px; display:none;">
            <input type="hidden" name="receiver_id" id="receiver_id" value="">
            <input type="text" id="message-input" name="message" class="form-control" placeholder="Nhập tin nhắn...">
            <button type="submit" id="send-btn" class="btn btn-primary">Gửi</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
const CURRENT_USER_ID = @json(Auth::id());
let ACTIVE_USER_ID = null;

// Admin routes
const FETCH_URL = "{{ route('admin.chat.fetch') }}";
const SEND_URL  = "{{ route('admin.chat.send') }}";
const DELETE_URL = "{{ url('admin/chat') }}/"; // append message_id

// CSRF setup
$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
});

// Chọn khách hàng để chat
$(document).on('click', '.user-item', function() {
    $('.user-item').removeClass('active');
    $(this).addClass('active');

    ACTIVE_USER_ID = $(this).data('id');
    $('#receiver_id').val(ACTIVE_USER_ID);
    $('#chat-header').text('Chat với ' + $(this).text().trim());
    $('#chat-form').show();

    loadMessages();
});

// Load tin nhắn
function loadMessages() {
    if (!ACTIVE_USER_ID) return;

    $.get(FETCH_URL, { customer_id: ACTIVE_USER_ID })
        .done(renderMessages)
        .fail(err => console.error("Lỗi fetch messages:", err));
}

// Render tin nhắn, thêm nút xóa cho admin
function renderMessages(data) {
    let html = '';
    data.forEach(msg => {
        const text = msg.message;
        const deleteBtn = `<button class="btn-delete" data-id="${msg.id}" style="margin-left:6px;font-size:10px;">❌</button>`;
        if(msg.sender_id == CURRENT_USER_ID) {
            html += `<div style="text-align:right;">
                        <div style="display:inline-block; background:#5b86e5; color:#fff; padding:8px 12px; border-radius:16px; margin:4px 0;">
                            ${text} ${deleteBtn}
                        </div>
                     </div>`;
        } else if(msg.sender_id == 0){
            html += `<div style="text-align:left;">
                        <div style="display:inline-block; background:#f0ad4e; color:white; padding:8px 12px; border-radius:16px; margin:4px 0;">
                            ${text} ${deleteBtn}
                        </div>
                        <div style="font-size:12px; color:gray;">AI Assistant</div>
                     </div>`;
        } else {
            html += `<div style="text-align:left;">
                        <div style="display:inline-block; background:#f1f1f1; padding:8px 12px; border-radius:16px; margin:4px 0;">
                            ${text} ${deleteBtn}
                        </div>
                     </div>`;
        }
    });

    $('#messages').html(html);
    $('#messages').scrollTop($('#messages')[0].scrollHeight);
}

// Gửi tin nhắn admin -> customer
$('#chat-form').on('submit', function(e){
    e.preventDefault();
    const text = $('#message-input').val().trim();
    if(!text || !ACTIVE_USER_ID) return;

    $.post(SEND_URL, { message: text, receiver_id: ACTIVE_USER_ID })
        .done(() => {
            $('#message-input').val('');
            loadMessages();
        })
        .fail(() => alert('Gửi tin nhắn thất bại'));
});

// Xóa tin nhắn bất kỳ
$(document).on('click', '.btn-delete', function(){
    const msgId = $(this).data('id');
    if(!confirm('Bạn có chắc muốn xóa tin nhắn này?')) return;

    $.ajax({
        url: DELETE_URL + msgId,
        type: 'DELETE',
        success: () => loadMessages(),
        error: () => alert('Xóa thất bại')
    });
});

// Auto reload tin nhắn mỗi 3s
setInterval(() => {
    if(ACTIVE_USER_ID){
        loadMessages();
    }
}, 3000);
</script>
@endsection
