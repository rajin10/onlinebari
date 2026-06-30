<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\DeviceId;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\PartialPayment;
use App\Models\Product;
use App\Models\User;
use App\Models\VendorAccount;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * show customer order list
     *
     * @return void
     */
    public function index(Request $request)
    {
        $orders = Order::latest();

        if ($request->filled('keyword')) {
            $orders->where(function ($q) use ($request) {
                $q->where('invoice', 'like', '%'.$request->keyword.'%')
                    ->orWhere('phone', 'like', '%'.$request->keyword.'%');
            });
        }

        if ($request->filled('status')) {
            $orders->where('status', $request->status);
        }

        if ($request->filled('is_pre')) {
            $orders->where('is_pre', $request->is_pre);
        }

        if ($request->filled('risk')) {
            if ($request->risk === 'flagged') {
                $orders->where('is_flagged', true);
            } else {
                $orders->where('fraud_risk_level', $request->risk);
            }
        }

        $orders = $orders->paginate(10);

        return view('admin.e-commerce.order.index', compact('orders'));
    }

    public function comission()
    {
        $comissions = Commission::latest('id')->where('status', '1')->get();

        return view('admin.e-commerce.order.comission', compact('comissions'));
    }

    /**
     * show customer pending order list
     *
     * @return void
     */
    public function pending()
    {
        $orders = Order::where('status', 0)->where('is_pre', '0')->latest('id')->get();

        return view('admin.e-commerce.order.pending', compact('orders'));
    }

    public function pre()
    {
        $orders = Order::where('status', 0)->where('is_pre', '1')->latest('id')->get();

        return view('admin.e-commerce.order.pending', compact('orders'));
    }

    /**
     * show customer processing order list
     *
     * @return void
     */
    public function processing()
    {
        $orders = Order::where('status', 1)->latest('id')->get();

        return view('admin.e-commerce.order.processing', compact('orders'));
    }

    /**
     * show customer cancel order list
     *
     * @return void
     */
    public function cancel()
    {
        $orders = Order::where('status', 2)->latest('id')->get();

        return view('admin.e-commerce.order.cancel', compact('orders'));
    }

    /**
     * show customer delivered order list
     *
     * @return void
     */
    public function delivered()
    {
        $orders = Order::where('status', 3)->latest('id')->get();

        return view('admin.e-commerce.order.delivered', compact('orders'));
    }

    /**
     * show order product
     *
     * @param  mixed  $id
     * @return void
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);

        return view('admin.e-commerce.order.show', compact('order'));
    }

    /**
     * order print
     *
     * @param  mixed  $id
     * @return void
     */
    public function print($id)
    {
        $order = Order::findOrFail($id);

        return view('admin.e-commerce.order.invoice', compact('order'));
    }

    /**
     * change order status pending to processing
     *
     * @param  mixed  $id
     * @return void
     */
    public function statusProcessing($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status == 3) {
            $this->return_helper($order);
            foreach ($order->orderDetails as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $vendor = User::find($product->user_id);
                    if ($vendor->role_id == 1) {
                        $amount = $vendor->vendorAccount->amount;
                        $vendor->vendorAccount()->update([
                            'amount' => $amount - $item->g_total,
                        ]);
                    } else {
                        $grand_total = $item->g_total;
                        $admin_amount = Commission::where('order_id', $order->id)->first();
                        $admin_amount->status = 0;
                        $admin_amount->update();
                        $adminAccount = VendorAccount::where('vendor_id', 1)->first();
                        $vendor_amount = $grand_total;
                        $amount = $adminAccount->amount;

                        $vendor->vendorAccount()->update([
                            'amount' => $vendor->vendorAccount->amount - $vendor_amount,
                        ]);
                    }

                    $product->quantity = $product->quantity - $item->qty;
                    $product->save();
                }
            }
            if ($vendor->role_id != 1) {
                $adminAccount->update([
                    'amount' => $amount - $admin_amount->amount,
                ]);
            }
            $order->status = 1;
            DB::table('multi_order')->where('order_id', $id)->update(['status' => 1]);
            $order->save();
            $user = User::find($order->user_id);
            if ($user !== null) {
                $user->point -= $order->point;
                $user->update();
            }
            notify()->success('Order status processing successfully', 'Congratulations');

            return back();
        } elseif ($order->status != 2) {
            $order->status = 1;
            DB::table('multi_order')->where('order_id', $id)->update(['status' => 1]);
            $order->save();
            $this->sendNotification('pross', $order->invoice, $order->user_id);
            notify()->success('Order status processing successfully', 'Congratulations');

            return back();
        } elseif ($order->status == 2) {
            $this->return_helper($order);
            $this->sendNotification('cancel', $order->invoice, $order->user_id);
            notify()->success('Order status processing successfully', 'Congratulations');

            return back();
        }
        notify()->warning('This order status not pending', 'Something Wrong');

        return back();
    }

    public function return_helper($order)
    {
        foreach ($order->orderDetails as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $vendor = User::find($product->user_id);
                if ($vendor->role_id == 1) {
                    $amount = $vendor->vendorAccount->pending_amount;
                    $vendor->vendorAccount()->update([
                        'pending_amount' => $amount + $item->g_total,
                    ]);
                } else {
                    $grand_total = $item->g_total;
                    $admin_amount = Commission::where('order_id', $order->id)->first();
                    $adminAccount = VendorAccount::where('vendor_id', 1)->first();
                    $vendor_amount = $grand_total;
                    $amount = $adminAccount->pending_amount;

                    $vendor->vendorAccount()->update([
                        'pending_amount' => $vendor->vendorAccount->pending_amount + $vendor_amount,
                    ]);
                }

                $product->quantity = $product->quantity - $item->qty;
                $product->save();
            }
        }
        if ($vendor->role_id != 1) {
            $adminAccount->update([
                'pending_amount' => $amount + $admin_amount->amount,
            ]);
        }
        $order->status = 1;
        DB::table('multi_order')->where('order_id', $order->id)->update(['status' => 1]);
        $order->save();
        $user = User::find($order->user_id);
        if ($user !== null) {
            $user->pen_point += $order->point;
            if ($order->payment_method == 'wallate') {
                $user->wallate = $user->wallate - $order->total;
            }
            $user->update();
        }

    }

    public function refund_helper($order)
    {
        foreach ($order->orderDetails as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $vendor = User::find($product->user_id);
                if ($vendor->role_id == 1) {
                    $amount = $vendor->vendorAccount->pending_amount;
                    $vendor->vendorAccount()->update([
                        'pending_amount' => $amount + 0,
                    ]);
                } else {
                    $admin_amount = Commission::where('order_id', $order->id)->first();
                    $adminAccount = VendorAccount::where('vendor_id', 1)->first();

                    $amount = $adminAccount->pending_amount;

                    $vendor->vendorAccount()->update([
                        'pending_amount' => $vendor->vendorAccount->pending_amount + 0,
                    ]);
                }

                $product->quantity = $product->quantity + $item->qty;
                $product->save();
            }
        }
        if ($vendor->role_id != 1) {
            $adminAccount->update([
                'pending_amount' => $amount + 0,
            ]);
        }
        $order->status = 1;
        DB::table('multi_order')->where('order_id', $order->id)->update(['status' => 1]);
        $order->save();

    }

    /**
     * change order status pending to processing
     *
     * @param  mixed  $id
     * @return void
     */
    public function statusShipping($id)
    {
        $order = Order::findOrFail($id);

        $order->status = 4;
        DB::table('multi_order')->where('order_id', $id)->update(['status' => 4]);
        $order->save();

        DB::table('multi_order')->where('order_id', $id)->where('vendor_id', auth()->id())->update(['status' => 4]);
        $this->sendNotification('shipping', $order->invoice, $order->user_id);
        notify()->success('Order status Shipping successfully', 'Congratulations');

        return back();

    }

    /**
     * change order status pending/processing to cancel
     *
     * @param  mixed  $id
     * @return void
     */
    public function statusCancel($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status == 0 || $order->status == 1) {
            $this->cancel_helper($order);
            $this->sendNotification('cancel', $order->invoice, $order->user_id);
            notify()->success('Order cancel successfully', 'Congratulations');

            return back();
        }
        notify()->warning('This order status not pending/processing', 'Something Wrong');

        return back();

    }

    public function cancel_helper($order)
    {
        foreach ($order->orderDetails as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $vendor = User::find($product->user_id);
                if ($vendor->role_id == 1) {
                    $amount = $vendor->vendorAccount->pending_amount;
                    $vendor->vendorAccount()->update([
                        'pending_amount' => $amount - $item->g_total,
                    ]);
                } else {
                    $grand_total = $item->g_total;
                    $admin_amount = Commission::where('order_id', $order->id)->first();
                    $adminAccount = VendorAccount::where('vendor_id', 1)->first();
                    $vendor_amount = $grand_total;
                    $amount = $adminAccount->pending_amount;

                    $vendor->vendorAccount()->update([
                        'pending_amount' => $vendor->vendorAccount->pending_amount - $vendor_amount,
                    ]);
                }

                $product->quantity = $product->quantity + $item->qty;
                $product->save();
            }
        }
        if ($vendor->role_id != 1) {
            $adminAccount->update([
                'pending_amount' => $amount - $admin_amount->amount,
            ]);
        }
        $order->status = 2;
        DB::table('multi_order')->where('order_id', $order->id)->update(['status' => 2]);
        $order->save();
        $user = User::find($order->user_id);
        if ($user !== null) {
            $user->pen_point -= $order->point;
            if ($order->payment_method == 'wallate') {
                $user->wallate = $user->wallate + $order->total;
            }
            $user->update();
        }
    }

    /**
     * change order status pending/processing to delivered
     *
     * @param  mixed  $id
     * @return void
     */
    public function statusDelivered($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status == 0 || $order->status == 1 || $order->status == 4) {

            $this->cancel_helper($order);
            foreach ($order->orderDetails as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $vendor = User::find($product->user_id);
                    if ($vendor->role_id == 1) {
                        $amount = $vendor->vendorAccount->amount;
                        $vendor->vendorAccount()->update([
                            'amount' => $amount + $item->g_total,
                        ]);
                    } else {
                        $grand_total = $item->g_total;
                        $admin_amount = Commission::where('order_id', $order->id)->first();
                        $admin_amount->status = 1;
                        $admin_amount->update();
                        $adminAccount = VendorAccount::where('vendor_id', 1)->first();
                        $vendor_amount = $grand_total;
                        $amount = $adminAccount->amount;

                        $vendor->vendorAccount()->update([
                            'amount' => $vendor->vendorAccount->amount + $vendor_amount,
                        ]);
                    }

                    $product->quantity = $product->quantity - $item->qty;
                    $product->save();
                }
            }
            if ($vendor->role_id != 1) {
                $adminAccount->update([
                    'amount' => $amount + $admin_amount->amount,
                ]);
            }
            $order->status = 3;
            DB::table('multi_order')->where('order_id', $id)->update(['status' => 3]);
            $order->save();
            $user = User::find($order->user_id);
            if ($user !== null) {
                $user->point += $order->point;
                if ($order->payment_method == 'wallate') {
                    $user->wallate = $user->wallate - $order->total;
                }
                $user->save();
            }

            // affaliate bonuse

            // if($order->refer_id != 2){
            //     if($order->refer_bonus){
            //          $ref=User::find($order->refer_id);
            //          if($ref){
            //               $ref->wallate+=$order->refer_bonus;
            //       $ref->save();
            //          }

            //     }

            // }

            if (setting('mail_config') == 1) {
                $data = [
                    'order_id' => $order->order_id,
                    'invoice' => $order->invoice,
                    'name' => $order->first_name,
                    'email' => $order->email,
                    'address' => $order->address,
                    'coupon_code' => $order->coupon_code,
                    'subtotal' => $order->subtotal,
                    'shipping_charge' => $order->shipping_charge,
                    'discount' => $order->discount,
                    'total' => $order->total,
                    'date' => $order->created_at,
                    'payment_method' => $order->payment_method,
                    'pay_status' => $order->pay_staus,
                    'pay_date' => $order->pay_date,
                    'orderDetails' => $order->orderDetails,
                    'phone' => $order->phone,
                ];
                Mail::send('frontend.invoice-mail', $data, function ($mail) use ($data) {
                    $mail->from(config('mail.from.address'), config('app.name'))
                        ->to($data['email'], $data['name'])
                        ->subject('Order Invoice');
                });
            }
            notify()->success('Order delivered successfully', 'Congratulations');

            return back();
            $this->sendNotification('delevery', $order->invoice, $order->user_id);
        }
        notify()->warning('This order status not pending/processing', 'Something Wrong');

        return back();
    }

    // Return Accept by Admin
    public function returnAccept($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status == 6) {
            $order->status = 7; // return accept status
            DB::table('multi_order')->where('order_id', $id)->update(['status' => 7]);
            $order->save();

            notify()->success('Order return accepted successfully', 'Congratulations');

            return back();
            $this->sendNotification('delevery', $order->invoice, $order->user_id);
        }
        notify()->warning('This order status not return accepted', 'Something Wrong');

        return back();
    }

    // Complete return by admin as got the product from customer
    public function returnComplete($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status == 7) {

            $order->status = 8; // return completed status
            DB::table('multi_order')->where('order_id', $id)->update(['status' => 8]);
            $order->save();

            notify()->success('Order Returned back successfully', 'Congratulations');

            return back();
            $this->sendNotification('delevery', $order->invoice, $order->user_id);
        }
        notify()->warning('This order retrun system not completed yet', 'Something Wrong');

        return back();
    }

    /**
     * order invoice print
     *
     * @param  mixed  $id
     * @return void
     */
    public function invoice($id)
    {
        $order = Order::findOrFail($id);

        return view('admin.e-commerce.order.invoice', compact('order'));
    }

    public function partials()
    {
        $partials = PartialPayment::orderBy('id', 'desc')->get();

        return view('admin.e-commerce.order.partials', compact('partials'));
    }

    public function partialStatus($id, $st)
    {
        $partials = PartialPayment::find($id);
        $order = Order::findOrFail($partials->order_id);
        $partials->update(['status' => $st]);

        if ($st == 1) {
            $parts = DB::table('multi_order')->where('order_id', $partials->order_id)->get();
            $amount = $partials->amount;
            foreach ($parts as $part) {
                if ($amount > 0) {
                    if ($part->partial_pay != $part->total) {
                        $total_requested = $part->partial_pay + $amount;

                        if ($total_requested > $part->total) {
                            $new_balance = $total_requested - $part->total;
                            $slice = $amount - $new_balance;
                            $amount -= $slice;
                        } else {
                            $slice = $amount;
                            $amount -= $slice;
                        }

                        DB::table('multi_order')->where('id', $part->id)->update(['partial_pay' => $part->partial_pay + $slice]);

                    }
                }
            }
        }

        $this->sendNotification('pertials', $order->invoice, $order->user_id);
        notify()->success('Successfully', 'Congratulations');

        return back();
    }

    public function delete($id)
    {
        $order = Order::findOrFail($id);
        DB::table('order_details')->where('order_id', $id)->delete();
        PartialPayment::where('order_id', $id)->delete();
        OrderDetails::where('order_id', $id)->delete();
        $order->delete();
        notify()->success('Order Delete successfully', 'Congratulations');

        return redirect()->route('admin.order.index');
    }

    public function sendNotification($status, $invoice, $id)
    {
        $firebaseToken = DeviceId::where('user_id', $id)->pluck('device_id')->all();
        if ($status == 'cancel') {
            $body = 'Your Order is Cancel By Admin';
        } elseif ($status == 'pross') {
            $body = 'Your order is in processing';
        } elseif ($status == 'delevery') {
            $body = 'Order Arrived and Picked Up! Thank you for your Purchase';
        } elseif ($status == 'shipping') {
            $body = 'Your order has been delivered by courier';
        } elseif ($status == 'pertials') {
            $body = 'Your partials payment success';
        }
        $SERVER_API_KEY = 'AAAA9Ek9F7U:APA91bEtCumEi8v_NmoBW6rQbm48iVNB4ctTguXS5G33Mj1FEmX48zlNYEHLWO3d6WfLkPD3ByKZQPrlJVl0swAd1ZxFWPMHWOdPWXD30sGCOvu_xIV7nTW9PC6cGiL6n3FOBHl1bavE';
        $data = [
            'registration_ids' => $firebaseToken,
            'notification' => [
                'title' => 'Invoice Is '.$invoice.' From '.'shopasiabd.com',
                'body' => $body,
                'content_available' => true,
                'priority' => 'high',
            ],
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key='.$SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        curl_close($ch);
    }

    public function refund(Request $request)
    {
        $order = Order::find($request->order);
        if ($order->refund_method == null) {
            $this->refund_helper($order);
            foreach ($order->orderDetails as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $vendor = User::find($product->user_id);
                    if ($vendor->role_id == 1) {
                        $amount = $vendor->vendorAccount->amount;
                        $vendor->vendorAccount()->update([
                            'amount' => $amount - $item->g_total,
                        ]);
                    } else {
                        $grand_total = $item->g_total;
                        $admin_amount = Commission::where('order_id', $order->id)->first();
                        $admin_amount->status = 0;
                        $admin_amount->update();
                        $adminAccount = VendorAccount::where('vendor_id', 1)->first();
                        $vendor_amount = $grand_total;
                        $amount = $adminAccount->amount;

                        $vendor->vendorAccount()->update([
                            'amount' => $vendor->vendorAccount->amount - $vendor_amount,
                        ]);
                    }

                    $product->quantity = $product->quantity - $item->qty;
                    $product->save();
                }
            }
            if ($vendor->role_id != 1) {
                $adminAccount->update([
                    'amount' => $amount - $admin_amount->amount,
                ]);
            }
            $order->status = 5;
            DB::table('multi_order')->where('order_id', $order->id)->update(['status' => 5]);
            $order->refund_amount = $request->amount;
            $order->refund_method = $request->method;
            $order->save();
            $user = User::find($order->user_id);
            if ($user !== null) {
                if ($order->refund_method == 'wallate') {
                    $user->wallate = $request->amount + $user->wallate;
                }
                $user->point -= $order->point;
                $user->update();
            }
        } else {
            $order->status = 5;
            DB::table('multi_order')->where('order_id', $id)->update(['status' => 5]);
            $order->refund_amount = $request->amount;
            $order->refund_method = $request->method;
            $order->save();
            $user = User::find($order->user_id);
            if ($order->refund_method == 'wallate') {
                $user->wallate = $user->wallate + $request->amount;
                $user->update();
            }
        }
        notify()->success('Order Refund successfully', 'Congratulations');

        return back();

    }

    public function refund_two(Request $request)
    {
        $order = Order::find($request->order);
        $order->status = 5;
        DB::table('multi_order')->where('order_id', $order->id)->update(['status' => 5]);
        $order->refund_amount = $request->amount;
        $order->refund_method = $request->method;
        $order->save();
        $user = User::find($order->user_id);
        if ($order->refund_method == 'wallate') {
            $user->wallate = $user->wallate + $request->amount;
            $user->update();
        }
        notify()->success('Order Refund successfully', 'Congratulations');

        return back();
    }

    // sub_order
    public function sub_status($id, $status, $vendor)
    {
        DB::table('multi_order')->where('order_id', $id)->where('vendor_id', $vendor)->update(['status' => $status]);

        notify()->success('Order status  successfully changed', 'Congratulations');

        return back();
    }

    public function fraud_checker(Request $request)
    {
        try {
            \Log::info('Fraud checker request', $request->all());
            // Get order details
            $order = Order::findOrFail($request->id);
            \Log::info('Order found', $order->toArray());
            $date = $order->created_at->format('d M Y, h:i A');
            $phone = $order->phone;
            $ip = $order->ip_address;

            // Initialize default values
            $name = $order->first_name ?? 'N/A';
            $status = 'N/A';

            $steadfast_total = 0;
            $steadfast_success = 0;
            $steadfast_cancel = 0;

            $pathao_total = 0;
            $pathao_success = 0;
            $pathao_cancel = 0;

            $redx_total = 0;
            $redx_success = 0;
            $redx_cancel = 0;

            $paperfly_total = 0;
            $paperfly_success = 0;
            $paperfly_cancel = 0;

            $total_parcel = 0;
            $total_success = 0;
            $total_cancel = 0;

            // Make API call to BD Courier
            $apiKey = setting('BDCOURIER_API_KEY') ?? 'ZkEEfBAEBRxVkgcLpR3Z5e3sPHQ6dy0XViGTqYyg4clRjj06rRKmAs41Smp2';
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://bdcourier.com/api/courier-check?phone='.urlencode($phone),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer '.$apiKey,
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ]);

            $response = curl_exec($curl);
            $curl_error = curl_error($curl);
            curl_close($curl);

            $api_error_message = null;

            // Check if API call was successful
            if ($response && empty($curl_error)) {
                $data = json_decode($response, true);

                if (isset($data['courierData'])) {
                    $result = $data;

                    // Extract courier data
                    $name = $result['name'] ?? $name;
                    $status = $result['status'] ?? 'info';

                    // Steadfast data
                    $steadfast_total = $result['courierData']['steadfast']['total_parcel'] ?? 0;
                    $steadfast_success = $result['courierData']['steadfast']['success_parcel'] ?? 0;
                    $steadfast_cancel = $result['courierData']['steadfast']['cancelled_parcel'] ?? 0;

                    // Pathao data
                    $pathao_total = $result['courierData']['pathao']['total_parcel'] ?? 0;
                    $pathao_success = $result['courierData']['pathao']['success_parcel'] ?? 0;
                    $pathao_cancel = $result['courierData']['pathao']['cancelled_parcel'] ?? 0;

                    // RedX data
                    $redx_total = $result['courierData']['redx']['total_parcel'] ?? 0;
                    $redx_success = $result['courierData']['redx']['success_parcel'] ?? 0;
                    $redx_cancel = $result['courierData']['redx']['cancelled_parcel'] ?? 0;

                    // Paperfly data
                    $paperfly_total = $result['courierData']['paperfly']['total_parcel'] ?? 0;
                    $paperfly_success = $result['courierData']['paperfly']['success_parcel'] ?? 0;
                    $paperfly_cancel = $result['courierData']['paperfly']['cancelled_parcel'] ?? 0;

                    // Summary data
                    $total_parcel = $result['courierData']['summary']['total_parcel'] ?? 0;
                    $total_success = $result['courierData']['summary']['success_parcel'] ?? 0;
                    $total_cancel = $result['courierData']['summary']['cancelled_parcel'] ?? 0;

                    // Determine status based on success ratio
                    if ($total_parcel > 0) {
                        $success_ratio = ($total_success / $total_parcel) * 100;

                        if ($success_ratio >= 70) {
                            $status = 'success';
                        } elseif ($success_ratio >= 50) {
                            $status = 'warning';
                        } else {
                            $status = 'danger';
                        }
                    }
                } else {
                    // Handle API specific errors (e.g., limit reached)
                    if (isset($data['error'])) {
                        $api_error_message = $data['error'];
                        $status = 'danger'; // Show visual warning
                    } elseif (isset($data['message'])) {
                        $api_error_message = $data['message'];
                        $status = 'danger';
                    } else {
                        $api_error_message = 'Invalid API Response Structure';
                    }

                    Log::warning('Fraud Checker API returned invalid data', [
                        'phone' => $phone,
                        'response' => $response,
                    ]);
                }
                // Fetch Local Order History Stats
                // Use last 10 digits for robust matching (matches 017... and +88017...)
                $phone_suffix = substr($phone, -10);
                $local_orders = Order::where('phone', 'like', '%'.$phone_suffix)->get();

                $local_total = $local_orders->count();
                $local_success = $local_orders->where('status', 3)->count(); // Verified: 3 is Delivered
                $local_cancel = $local_orders->where('status', 2)->count();  // Verified: 2 is Canceled

                // If API failed or returned empty data, use Local Data as primary source (or merge them if desired)
                // Here we simply ADD local data to the "Total" summary if API fails,
                // OR we can display them separately.
                // The user request implies they want to see "2 completed orders" which are likely checking against local history.

                // Let's create a "Local Shop" variable to pass to the view to avoid confusion with Courier data
                $local_shop_total = $local_total;
                $local_shop_success = $local_success;
                $local_shop_cancel = $local_cancel;

                // Update Summary Totals to include Local Data
                $total_parcel += $local_shop_total;
                $total_success += $local_shop_success;
                $total_cancel += $local_shop_cancel;

            } else {
                $api_error_message = 'API Connection Error: '.$curl_error; // Keep error message but don't stop execution
                // Even on API error, we MUST show local data
                $phone_suffix = substr($phone, -10);
                $local_orders = Order::where('phone', 'like', '%'.$phone_suffix)->get();

                $local_shop_total = $local_orders->count();
                $local_shop_success = $local_orders->where('status', 3)->count();
                $local_shop_cancel = $local_orders->where('status', 2)->count();

                $total_parcel += $local_shop_total;
                $total_success += $local_shop_success;
                $total_cancel += $local_shop_cancel;

                Log::error('Fraud Checker API Error', [
                    'phone' => $phone,
                    'error' => $curl_error,
                ]);
            }

            // Recalculate Status based on NEW totals (Local + API)
            if ($total_parcel > 0) {
                $success_ratio = ($total_success / $total_parcel) * 100;

                if ($success_ratio >= 70) {
                    $status = 'success';
                } elseif ($success_ratio >= 50) {
                    $status = 'warning';
                } else {
                    $status = 'danger';
                }
            } else {
                // Reset status if truly 0 data found anywhere
                $status = 'info';
            }

            // Return view with data
            return view('admin.e-commerce.order.fraud_checker', compact(
                'status',
                'name',
                'phone',
                'ip',
                'steadfast_total',
                'steadfast_success',
                'steadfast_cancel',
                'pathao_total',
                'pathao_success',
                'pathao_cancel',
                'local_shop_total',
                'local_shop_success',
                'local_shop_cancel',
                'api_error_message',
                'total_parcel',
                'total_success',
                'total_cancel',
                'redx_total',
                'redx_success',
                'redx_cancel',
                'paperfly_total',
                'paperfly_success',
                'paperfly_cancel',
                'total_parcel',
                'total_success',
                'total_cancel',
                'api_error_message'
            ));

        } catch (\Exception $e) {
            Log::error('Fraud Checker Error:', [
                'error' => $e->getMessage(),
                'order_id' => $request->id,
            ]);

            // Return error view
            return view('admin.e-commerce.order.fraud_checker', [
                'status' => 'error',
                'name' => 'N/A',
                'phone' => 'N/A',
                'steadfast_total' => 0,
                'steadfast_success' => 0,
                'steadfast_cancel' => 0,
                'pathao_total' => 0,
                'pathao_success' => 0,
                'pathao_cancel' => 0,
                'redx_total' => 0,
                'redx_success' => 0,
                'redx_cancel' => 0,
                'paperfly_total' => 0,
                'paperfly_success' => 0,
                'paperfly_cancel' => 0,
                'total_parcel' => 0,
                'total_success' => 0,
                'total_cancel' => 0,
            ]);
        }
    }
}
