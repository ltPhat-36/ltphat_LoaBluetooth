<?php
use App\Http\Controllers\User\NewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\CartController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\OrderController as OrderController; 
use App\Http\Controllers\User\ProductControllerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\User\UserVoucherController;
use App\Http\Controllers\Admin\AdminVoucherController;

// -------------------
// Trang chủ hiển thị sản phẩm
// -------------------
Route::get('/', [ProductController::class, 'welcome'])->name('home');

// -------------------
// Auth routes
// -------------------
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -------------------
// Email verification routes
// -------------------
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function ($id) {
    $user = User::findOrFail($id);

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    return redirect()->route('login')->with('success', 'Email đã được xác thực!');
})->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Đã gửi lại link xác thực!');
})->middleware(['auth','throttle:6,1'])->name('verification.send');

// -------------------
// Public routes (customer) - chỉ index + show
// -------------------
Route::resource('products', ProductController::class)->only(['index','show']);
Route::resource('categories', CategoryController::class)->only(['index','show']);
Route::post('/user/payment', [OrderController::class, 'processPayment'])->name('payment.process');

// Thanh toán (COD & MoMo)
// Thanh toán (COD & MoMo) - chỉ cho user đã login và verify email
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/payment', [OrderController::class, 'index'])->name('user.payment.index');
    Route::post('/payment/process', [OrderController::class, 'processPayment'])->name('payment.process');
    Route::get('/orders/{order}/pay/momo', [OrderController::class, 'payAgain'])->name('orders.momo.pay');
    Route::post('/payment/momo', [OrderController::class, 'momo_payment'])->name('payment.momo');
    Route::get('/payment/momo/callback', [OrderController::class, 'callback'])->name('payment.momo.callback');
    Route::post('/payment/momo/ipn', [OrderController::class, 'ipn'])->name('payment.momo.ipn');

    // Lịch sử đơn hàng & xem chi tiết
    Route::get('/orders', [OrderController::class, 'orderHistory'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});
Route::post('/chat/faq', [\App\Http\Controllers\ChatController::class, 'chatFaq'])->name('chat.faq');


// Lịch sử đơn hàng & xem chi tiết
Route::get('/orders', [OrderController::class, 'orderHistory'])->name('orders.index'); 
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

Route::prefix('cart')->middleware('auth')->group(function() {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('update/{id}', [CartController::class, 'update']);
    Route::delete('remove/{id}', [CartController::class, 'remove']);
    Route::delete('clear', [CartController::class, 'clear']);
});

Route::get('/san-pham/{id}', [App\Http\Controllers\User\ProductController::class, 'show'])->name('frontend.products.show');

// -------------------
// Admin routes: auth + admin middleware
// -------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Categories CRUD
    Route::resource('categories', CategoryController::class);

    // Products CRUD
    Route::resource('products', ProductController::class);

    // Users CRUD
    Route::resource('users', UserController::class);

    // Orders admin
    Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('orders/{order}/shipping-status', [App\Http\Controllers\Admin\OrderController::class, 'updateShippingStatus'])->name('orders.updateShippingStatus');

    // Báo cáo
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/charts', [ReportController::class, 'charts'])->name('reports.charts');
    Route::get('reports/charts', [ReportController::class, 'chartView'])->name('reports.charts');

    // Reviews admin (duy nhất 1 khai báo)
    Route::resource('reviews', AdminReviewController::class)->only(['index','destroy','edit','update']);

    Route::resource('vouchers', AdminVoucherController::class);
});

// -------------------
// User categories
// -------------------
Route::get('/user/categories/{category}', [App\Http\Controllers\User\CategoryController::class, 'show'])
    ->name('user.categories.show');

Route::post('/products/{product}/reviews', [App\Http\Controllers\User\ReviewController::class, 'store'])
    ->middleware('auth')->name('reviews.store');

// Review routes - User
Route::prefix('user/review')->middleware('auth')->group(function () {
    // Trang đánh giá sản phẩm
    Route::get('/create/{product}', [ReviewController::class, 'create'])->name('reviews.create');

    // Xử lý submit đánh giá
    Route::post('/store/{product}', [ReviewController::class, 'store'])->name('reviews.store');
});

// News routes
Route::prefix('news')->group(function () {
    Route::get('/', [NewController::class, 'index'])->name('news.index');
    Route::get('/{slug}', [NewController::class, 'show'])->name('news.show');
});

// -------------------
// Chat routes
// -------------------
// Customer chat
Route::middleware(['auth','customer'])->group(function(){
    Route::get('/chat',[ChatController::class,'index'])->name('chat.index');
    Route::get('/chat/messages',[ChatController::class,'fetchMessages'])->name('chat.fetch');
    Route::post('/chat/send',[ChatController::class,'send'])->name('chat.send');
    Route::post('/chat/read',[ChatController::class,'markAsRead'])->name('chat.read');
});

// Admin chat
Route::prefix('admin')->middleware(['auth','admin'])->group(function(){
    Route::get('/chat',[AdminChatController::class,'index'])->name('admin.chat.index');
    Route::get('/chat/messages',[AdminChatController::class,'fetch'])->name('admin.chat.fetch');
    Route::post('/chat/send',[AdminChatController::class,'send'])->name('admin.chat.send');
    Route::delete('/chat/{id}', [AdminChatController::class, 'destroy'])->name('admin.chat.destroy');
});

Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('reports/chart-view', [ReportController::class, 'chartView'])->name('reports.chart-view');
});

// AI chat (customer only)
Route::post('/chat/ai',[ChatController::class,'chatWithAI'])->name('chat.ai')->middleware(['auth','customer']);

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('auth')->group(function() {
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
});
Route::prefix('user')->group(function() {
    // Áp dụng voucher (cả guest và user)
    Route::post('/voucher/apply', [UserVoucherController::class, 'apply'])
        ->name('user.voucher.apply');

    // Xóa voucher
    Route::post('/voucher/remove', [UserVoucherController::class, 'remove'])
        ->name('user.voucher.remove');
});
Route::get('/san-pham', [App\Http\Controllers\User\ProductController::class, 'index'])
    ->name('frontend.products.index');
// Game routes
Route::middleware(['auth','customer'])->group(function () {
    Route::get('/game', [\App\Http\Controllers\User\GameController::class, 'index'])->name('game.index');
    Route::post('/game/reward', [\App\Http\Controllers\User\GameController::class, 'reward'])->name('game.reward');
});
