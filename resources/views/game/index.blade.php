@extends('layouts.user')

@section('title', 'Mini Game')

@section('content')
<div class="container py-4 text-center">
    <h2 class="mb-4">🐥 Đua Vịt - Nhận Voucher</h2>

    <!-- Chọn con vịt -->
    <div class="mb-3">
        <label for="duckChoice" class="form-label">Chọn con vịt của bạn (1-10):</label>
        <select id="duckChoice" class="form-select w-auto d-inline-block">
            @for($i=1; $i<=10; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div>

    <canvas id="gameCanvas" width="800" height="400" style="border:1px solid #ccc;"></canvas>
    <br>
    <button id="startBtn" class="btn btn-primary mt-3">Bắt đầu</button>
    <div id="rewardMsg" class="alert mt-3 d-none"></div>
</div>
@endsection

@push('scripts')
<script>
const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

let ducks = [];
let finishLine = 750;
let running = false;
let winner = null;

// Tạo 10 con vịt
for (let i = 0; i < 10; i++) {
    ducks.push({ 
        x: 0, 
        y: 30 + i * 35, // vịt mỗi lane cách nhau 35px
        number: i + 1 
    });
}

// Vẽ các con vịt
function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Vẽ vạch đích
    ctx.fillStyle = "red";
    ctx.fillRect(finishLine, 0, 10, canvas.height);

    // Vẽ từng con vịt
    ducks.forEach(d => {
        ctx.fillStyle = "yellow";
        ctx.beginPath();
        ctx.arc(d.x + 20, d.y, 15, 0, Math.PI * 2); // thân vịt
        ctx.fill();

        // Vẽ số thứ tự
        ctx.fillStyle = "black";
        ctx.font = "12px Arial";
        ctx.fillText(d.number, d.x + 15, d.y + 4);
    });
}

// Chạy đua
function race() {
    if (!running) return;

    ducks.forEach(d => {
        d.x += Math.random() * 3; // tốc độ random
        if (d.x >= finishLine && winner === null) {
            winner = d.number;
            running = false;
            announceWinner();
        }
    });

    draw();
    if (running) requestAnimationFrame(race);
}

// Thông báo kết quả
function announceWinner() {
    let chosen = parseInt(document.getElementById("duckChoice").value);
    let msg = document.getElementById("rewardMsg");
    msg.classList.remove("d-none");

    if (winner === chosen) {
        // Gửi request nhận thưởng
        fetch("{{ route('game.reward') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({})
        }).then(res => res.json())
        .then(data => {
            msg.classList.add("alert-success");
            msg.innerText = "🎉 Vịt số " + winner + " thắng! Bạn đoán đúng! " + data.message + " | Voucher: " + data.voucher;
        });
    } else {
        msg.classList.add("alert-danger");
        msg.innerText = "😢 Vịt số " + winner + " thắng! Bạn chọn sai rồi.";
    }
}

document.getElementById("startBtn").addEventListener("click", () => {
    ducks.forEach(d => d.x = 0);
    running = true;
    winner = null;
    document.getElementById("rewardMsg").className = "alert mt-3 d-none";
    race();
});

draw();
</script>
@endpush
