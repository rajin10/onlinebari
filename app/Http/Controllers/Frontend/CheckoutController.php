<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Core\ShoppingCart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 2 || Auth::user()->role_id == 3) {
                if (Cart::count() > 0) {
                    return view('frontend.checkout');
                }
                notify()->warning('Your cart is empty.', 'Empty');
                return back();
            }

            notify()->warning('You are not authorized for this action.', 'Unauthorized');
            return back();
        }

        if (setting('GUEST_CHECKOUT') == 0) {
            return redirect()->route('login');
        }

        if (Cart::count() > 0) {
            return view('frontend.checkout');
        }

        notify()->warning('Your cart is empty.', 'Empty');
        return back();
    }
}
