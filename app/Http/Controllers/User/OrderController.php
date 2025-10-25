<?php

namespace App\Http\Controllers\User;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Voucher;
class OrderController extends Controller
{
    // ==========================
    // Cấu hình thông số MoMo test
    // ==========================
    private $endpoint    = 'https://test-payment.momo.vn/v2/gateway/api/create';
    private $partnerCode = 'MOMOBKUN20180529';
    private $accessKey   = 'klm05TvNBzhg7h7j';
    private $secretKey   = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

    /**
     * Trang checkout: nếu giỏ trống thì về cart, nếu có thì hiện form thanh toán
     */
    public function index()
{
    if (Auth::check()) {
        $cart = CartItem::with('product')->where('user_id', Auth::id())->get();
    } else {
        $cartArray = session('cart', []);
        $cart = collect($cartArray)->map(function($item){
            return (object)[
                'id'       => $item['id'] ?? null,
                'name'     => $item['name'] ?? '',
                'price'    => $item['price'] ?? 0,
                'quantity' => $item['quantity'] ?? 0,
            ];
        });
    }

    // luôn tạo biến $cart, tránh undefined
    if (!isset($cart)) {
        $cart = collect([]);
    }

    if ($cart->isEmpty()) {
        return redirect()->route('cart.index')
            ->with('error', 'Giỏ hàng của bạn đang trống.');
    }

    return view('user.payment.index', ['cartItems' => $cart]);

}


    /**
     * Xử lý đặt hàng (COD hoặc MoMo)
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'address'        => 'required|string|max:255',
            'email'          => 'required|email|max:150',
            'phone'          => 'required|string|max:20',
            'payment_method' => 'required|in:cod,momo',
        ]);

        // Lấy cart: DB nếu login, session nếu guest
        if (Auth::check()) {
            $cart = CartItem::with('product')->where('user_id', Auth::id())->get();
        } else {
            $cartArray = session('cart', []);
            $cart = collect($cartArray)->map(function($item){
                return (object)[
                    'id'       => $item['id'],
                    'name'     => $item['name'],
                    'price'    => $item['price'],
                    'quantity' => $item['quantity'],
                ];
            });
        }
        

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Tính tổng tiền
        $total = $cart->sum(function($item){
            if ($item instanceof CartItem) {
                return $item->product->price * $item->quantity;
            } else {
                return $item['price'] * $item['quantity'];
            }
        });

        // Tạo đơn hàng
        $order = Order::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'email'       => $request->email,
            'address'     => $request->address,
            'phone'       => $request->phone,
            'total_price' => $total,
            'status'      => 'chờ thanh toán',
        ]);

        // Lưu chi tiết đơn
        foreach ($cart as $item) {
            $productId = $item instanceof CartItem ? $item->product_id : $item['id'];
            $price     = $item instanceof CartItem ? $item->product->price : $item['price'];
            $quantity  = $item instanceof CartItem ? $item->quantity : $item['quantity'];
        
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $productId,
                'quantity'   => $quantity,
                'price'      => $price,
            ]);
        
            // 🔽 Cập nhật tồn kho sản phẩm
            $product = Product::find($productId);
            if ($product) {
                // Nếu muốn kiểm tra trước khi trừ:
                if ($product->quantity < $quantity) {
                    // rollback order hoặc báo lỗi, tùy bạn
                    throw new \Exception("Sản phẩm {$product->name} không đủ số lượng tồn kho");
                }
                $product->decrement('quantity', $quantity);
            }
        }

        // Xóa giỏ
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }
        if (Auth::check()) {
            $user = auth()->user();
            $user->save();
        
            // Giảm usage voucher
            if (session('applied_voucher')) {
                $voucher = Voucher::find(session('applied_voucher'));
                if ($voucher) {
                    $voucher->decrement('usage_limit');
                    session()->forget('applied_voucher');
                }
            }
        }

        // Thanh toán MoMo
        if ($request->payment_method === 'momo') {
            return $this->redirectToMoMo($order);
        }

        // COD
        $order->update(['status' => 'đã đặt (COD)']);

        return redirect()->route('orders.index')
            ->with('success', 'Đặt hàng thành công! Thanh toán khi nhận hàng.');
    }

    /**
     * Tạo giao dịch MoMo và chuyển hướng người dùng
     */
    protected function redirectToMoMo(Order $order)
    {
        $redirectUrl = route('payment.momo.callback');
        $ipnUrl      = route('payment.momo.ipn');
        $orderId     = time() . '_' . $order->id;
        $requestId   = uniqid();
        $orderInfo   = "Thanh toán đơn hàng #{$order->id}";
        $amount      = (string) max(1000, (int) $order->total_price);
        $extraData   = '';
        $requestType = 'payWithATM';

        $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}"
            . "&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}"
            . "&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";

        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        $payload = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => "YourStore",
            'storeId'     => "Store_01",
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature,
        ];

        Log::info('MoMo request payload:', $payload);

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json; charset=UTF-8'])
                ->withoutVerifying()
                ->post($this->endpoint, $payload);

            if (!$response->successful()) {
                Log::error('MoMo create payment failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return redirect()->route('orders.index')
                    ->with('error', 'Không thể kết nối MoMo (' . $response->status() . '). Vui lòng thử lại.');
            }

            $json = $response->json();
            Log::info('MoMo response:', $json);

            if (!empty($json['payUrl'])) {
                $order->update([
                    'momo_request_id' => $requestId,
                    'momo_order_id'   => $orderId,
                ]);
                return redirect()->away($json['payUrl']);
            }

            $msg = $json['message'] ?? 'MoMo không trả về payUrl.';
            Log::error('MoMo payUrl missing', ['response' => $json]);

            return redirect()->route('orders.index')
                ->with('error', 'Không tạo được link thanh toán MoMo: ' . $msg);
        } catch (\Exception $e) {
            Log::error('MoMo request exception', ['error' => $e->getMessage()]);

            return redirect()->route('orders.index')
                ->with('error', 'Lỗi khi tạo thanh toán MoMo: ' . $e->getMessage());
        }
    }

    /**
     * Callback: MoMo redirect user về
     */
    public function callback(Request $request)
    {
        $resultCode = $request->input('resultCode');
        $order = null;

        if ($request->filled('orderId')) {
            $parts = explode('_', $request->orderId);
            $orderId = end($parts);
            $order = Order::find($orderId);
        }

        if ($resultCode == '0' && $order) {
            $order->update(['status' => 'đã thanh toán (MoMo)']);
            return redirect()->route('orders.index')
                ->with('success', 'Thanh toán MoMo thành công!');
        }

        if ($order) {
            $order->update(['status' => 'thanh toán MoMo thất bại']);
        }

        return redirect()->route('user.payment.index')
            ->with('error', 'Thanh toán MoMo thất bại hoặc bị hủy.');
    }

    /**
     * IPN: MoMo server-to-server
     */
    public function ipn(Request $request)
    {
        Log::info('MoMo IPN payload:', $request->all());

        if ($request->filled('orderId')) {
            $parts = explode('_', $request->orderId);
            $orderId = end($parts);

            if ($order = Order::find($orderId)) {
                if ((string)$request->resultCode === '0') {
                    $order->update(['status' => 'đã thanh toán (MoMo)']);
                } else {
                    $order->update(['status' => 'thanh toán thất bại (MoMo)']);
                }
            }
        }

        return response()->json(['resultCode' => 0, 'message' => 'Received']);
    }

    /**
     * Thanh toán lại MoMo cho đơn chưa thanh toán
     */
    public function payAgain(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thanh toán lại đơn này.');
        }

        if ($order->status === 'đã thanh toán (MoMo)') {
            return redirect()->route('orders.index')
                ->with('info', 'Đơn này đã thanh toán.');
        }

        $order->update(['status' => 'chờ thanh toán']);

        return $this->redirectToMoMo($order);
    }

    /**
     * Lịch sử đơn hàng của user
     */
    public function orderHistory()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->orderByDesc('created_at')
            ->get();

        return view('user.payment.order', compact('orders'));
    }

    /**
     * Chi tiết một đơn hàng
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        $order->load('items.product');

        return view('user.payment.show', compact('order'));
    }
}
