<?php

namespace App\Http\Controllers\Frontend;

use App\Core\ShoppingCart\Facades\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\PlaceOrderRequest;
use App\Jobs\ProcessOrderIntelligence;
use App\Library\UddoktaPay;
use App\Models\DownloadProduct;
use App\Models\DownloadUserProduct;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Review;
use App\Support\BdPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // ─── Direct Buy ────────────────────────────────────────────────────────

    public function buyProduct(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'qty' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->id);
        $qty = $request->qty ?? 1;
        $price = $request->dynamic_price ?? $product->discount_price ?? $product->regular_price;

        Cart::add([
            'id' => $product->id,
            'name' => $product->title,
            'qty' => $qty,
            'price' => $price,
            'weight' => 0,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image,
                'color' => $request->color ?? 'blank',
            ],
        ]);

        return redirect()->route('checkout');
    }

    // ─── Order Store Methods ────────────────────────────────────────────────

    public function orderStore(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'phone' => 'required',
            'payment_method' => 'required|string',
        ]);

        $order = $this->createOrder($request, Auth::id(), 'cart');

        return $this->finishOrder($request, $order);
    }

    public function orderBuyNowStore(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'phone' => 'required',
            'payment_method' => 'required|string',
        ]);

        $order = $this->createOrder($request, Auth::id(), 'buy_now');

        return $this->finishOrder($request, $order);
    }

    public function orderStore_guest(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'phone' => 'required',
        ]);

        $order = $this->createOrder($request, null, 'cart');

        return $this->finishOrder($request, $order);
    }

    public function orderStore_minimal(PlaceOrderRequest $request)
    {
        $order = $this->createOrder($request, Auth::id() ?: null, 'cart');

        return $this->finishOrder($request, $order);
    }

    public function orderBuyNowStore_minimal(PlaceOrderRequest $request)
    {
        $order = $this->createOrder($request, Auth::id() ?: null, 'buy_now');

        return $this->finishOrder($request, $order);
    }

    /**
     * Premium order confirmation page (PRG target — safe to refresh).
     */
    public function orderSuccess(string $orderId)
    {
        $order = Order::with('orderDetails')
            ->where('order_id', $orderId)
            ->firstOrFail();

        // User-owned orders are private; guest (capability-URL) orders are open.
        if ($order->user_id && Auth::id() !== $order->user_id) {
            abort(403);
        }

        return view('frontend.order_success', ['order' => $order]);
    }

    /**
     * Returning-customer lookup by phone for checkout auto-fill.
     */
    public function customerLookup(Request $request)
    {
        $phone = BdPhone::normalize((string) $request->query('phone', ''));

        if (! $phone) {
            return response()->json(['found' => false]);
        }

        $order = Order::where('phone', 'like', '%'.substr($phone, -10))
            ->latest()
            ->first();

        if (! $order) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'name' => $order->first_name,
            'address' => $order->address,
            'town' => $order->town,
        ]);
    }

    // ─── Order List & Invoice ───────────────────────────────────────────────

    public function order()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderDetails')
            ->latest()
            ->get();

        return view('frontend.order', compact('orders'));
    }

    public function orderInvoice($id)
    {
        $order = Order::with('orderDetails.product')->findOrFail($id);

        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('frontend.invoice', compact('order'));
    }

    // ─── Order Actions ──────────────────────────────────────────────────────

    public function cancel($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status == 0) {
            $order->update(['status' => 2]);
            notify()->success('Order cancelled.', 'Cancelled');
        }

        return back();
    }

    public function return_req($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $order->update(['status' => 6]);
        notify()->success('Return request submitted.', 'Return');

        return back();
    }

    public function returns()
    {
        $orders = Order::where('user_id', Auth::id())
            ->whereIn('status', [6, 7, 8])
            ->with('orderDetails')
            ->latest()
            ->get();

        return view('frontend.returns_order', compact('orders'));
    }

    // ─── Review ─────────────────────────────────────────────────────────────

    public function review($order_id)
    {
        $order = Order::with('orderDetails.product')
            ->where('order_id', $order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('frontend.review', compact('order'));
    }

    public function storeReview(Request $request, $product_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        $files = [];
        foreach (['report', 'report2', 'report3', 'report4', 'report5'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $name = time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/reviews'), $name);
                $files[] = $name;
            } else {
                $files[] = null;
            }
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'body' => $request->review,
            'file' => $files[0] ?? '',
            'file2' => $files[1],
            'file3' => $files[2],
            'file4' => $files[3],
            'file5' => $files[4],
        ]);

        $avg = Review::where('product_id', $product_id)->avg('rating');
        Product::where('id', $product_id)->update(['review' => round($avg, 1)]);

        notify()->success('Review submitted.', 'Thanks');

        return back();
    }

    // ─── Downloads ──────────────────────────────────────────────────────────

    public function download()
    {
        $items = OrderDetails::whereHas('order', function ($q) {
            $q->where('user_id', Auth::id())->where('pay_staus', 1);
        })->with('product.downloads')->get();

        return view('frontend.download', compact('items'));
    }

    public function downloadProductFile($pro_id, $id)
    {
        $product = Product::with('downloads')->findOrFail($pro_id);
        $download = DownloadProduct::findOrFail($id);

        $purchased = OrderDetails::whereHas('order', function ($q) {
            $q->where('user_id', Auth::id())->where('pay_staus', 1);
        })->where('product_id', $pro_id)->exists();

        abort_unless($purchased, 403, 'You have not purchased this product.');

        if ($product->download_expire && now()->gt($product->download_expire)) {
            notify()->error('Download link has expired.', 'Expired');

            return back();
        }

        $used = DownloadUserProduct::where('user_id', Auth::id())
            ->where('product_id', $pro_id)
            ->count();
        $limit = ($product->download_limit ?? 0) * $product->downloads->count();

        if ($limit > 0 && $used >= $limit) {
            notify()->error('Download limit reached.', 'Limit Reached');

            return back();
        }

        DownloadUserProduct::create([
            'user_id' => Auth::id(),
            'product_id' => $pro_id,
            'download_id' => $id,
        ]);

        if ($download->url) {
            return redirect($download->url);
        }

        return response()->download(public_path('uploads/downloads/'.$download->file));
    }

    // ─── Payment ────────────────────────────────────────────────────────────

    public function payform($slug)
    {
        $order = Order::with('orderDetails')
            ->where('order_id', $slug)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('frontend.payform', compact('order'));
    }

    public function payCreate(Request $request, $slug)
    {
        $order = Order::where('order_id', $slug)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            $paymentUrl = UddoktaPay::init_payment([
                'full_name' => $order->first_name,
                'email' => $order->email ?: Auth::user()->email,
                'amount' => (string) $order->total,
                'metadata' => [
                    'order_id' => $order->order_id,
                    'invoice' => $order->invoice,
                ],
                'return_url' => route('order'),
                'cancel_url' => route('order'),
            ]);

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            notify()->error($e->getMessage(), 'Payment Error');

            return back();
        }
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    private function createOrder(Request $request, ?int $userId, string $cartType): Order
    {
        $cartItems = Cart::content();
        $stotal = 0;
        $sellerIds = [];

        foreach ($cartItems as $item) {
            $product = Product::find($item->id);

            if ($product && ! in_array($product->user_id, $sellerIds)) {
                $sellerIds[] = $product->user_id;
            }

            if ($item->qty >= 6 && $product && $product->whole_price > 0) {
                $stotal += $item->qty * $product->whole_price;
            } else {
                $stotal += $item->subtotal;
            }
        }

        $sellerCount = count($sellerIds);
        $city = $request->city ?? '';
        $freeAbove = (float) (setting('shipping_free_above') ?? 0);

        if ($freeAbove > 0 && $stotal >= $freeAbove) {
            $singleCharge = 0.0;
            $shippingCharge = 0.0;
        } else {
            $singleCharge = (float) ($city === 'Dhaka'
                ? setting('shipping_charge')
                : setting('shipping_charge_out_of_range'));
            $shippingCharge = $singleCharge * $sellerCount;
        }

        $discount = 0.0;
        $couponCode = null;

        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');
            $discount = (float) ($coupon['discount'] ?? 0);
            $couponCode = $coupon['code'] ?? null;
        }

        $total = max(0.0, $stotal + $shippingCharge - $discount);
        $orderId = 'ORD-'.strtoupper(Str::random(8));
        $invoice = 'INV-'.strtoupper(Str::random(6)).'-'.time();

        $order = Order::create([
            'user_id' => $userId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name ?? '',
            'company_name' => $request->company ?? '',
            'country' => $request->country ?? setting('COUNTRY_SERVE') ?? 'Bangladesh',
            'address' => $request->address ?? '',
            'town' => $city,
            'district' => $request->district ?? '',
            'thana' => $request->thana ?? '',
            'post_code' => $request->post_code ?? '',
            'phone' => $request->phone,
            'email' => $request->email ?? ($userId ? Auth::user()->email : ''),
            'payment_method' => $request->payment_method ?? 'Cash on Delivery',
            'mobile_number' => $request->mobile_number ?? '',
            'transaction_id' => $request->transaction_id ?? '',
            'coupon_code' => $couponCode,
            'subtotal' => $stotal,
            'discount' => $discount,
            'shipping_charge' => $shippingCharge,
            'single_charge' => $singleCharge,
            'total' => $total,
            'cart_type' => $cartType,
            'status' => 0,
            'pay_staus' => 0,
            'order_id' => $orderId,
            'invoice' => $invoice,
        ]);

        foreach ($cartItems as $item) {
            $product = Product::find($item->id);
            $lineTotal = ($item->qty >= 6 && $product && $product->whole_price > 0)
                ? $item->qty * $product->whole_price
                : $item->subtotal;

            OrderDetails::create([
                'order_id' => $order->id,
                'seller_id' => $product ? $product->user_id : null,
                'product_id' => $item->id,
                'title' => $item->name,
                'color' => $item->options->color ?? '',
                'size' => $item->options->size ?? '',
                'qty' => $item->qty,
                'price' => $item->price,
                'total_price' => $lineTotal,
                'g_total' => $total,
            ]);
        }

        return $order->load('orderDetails');
    }

    /**
     * Finalise an order: clear cart/coupon, kick off post-order intelligence
     * (fraud check + risk flagging + Google Sheets log) after the response,
     * and return an AJAX-friendly redirect to the confirmation page (PRG).
     */
    private function finishOrder(Request $request, Order $order)
    {
        Cart::destroy();
        Session::forget('coupon');

        ProcessOrderIntelligence::dispatchAfterResponse($order->id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('order.success', $order->order_id),
            ]);
        }

        return redirect()->route('order.success', $order->order_id);
    }
}
