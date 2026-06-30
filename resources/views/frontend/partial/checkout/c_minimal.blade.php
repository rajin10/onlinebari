@extends('layouts.frontend.app')
@push('meta')
@use('App\Core\ShoppingCart\Facades\Cart')
<meta name='description' content="Checkout cart product"/>
<meta name='keywords' content="" />
@endpush
@section('title', 'Minimal - Checkout cart product')
@section('content')

<style id="checkout-payment-visible-fix">
#checkout #collapseTwo {
    display:block !important;
    height:auto !important;
    visibility:visible !important;
}

#checkout #collapseTwo .card-body {
    display:flex !important;
    flex-wrap:wrap !important;
    gap:14px !important;
    min-height:0 !important;
}

#checkout .pa label {
    display:flex !important;
    flex-direction:column !important;
    align-items:center !important;
    justify-content:center !important;
    min-height:110px !important;
}

#checkout #appended:empty {
    display:none !important;
}
</style>


<style id="checkout-payment-gap-fix">
#checkout #accordion .card:empty,
#checkout #accordion .collapse:not(.show),
#checkout #collapseThree,
#checkout #appended:empty {
    display:none !important;
}

#checkout .pa .card {
    min-height:auto !important;
}

#checkout #accordion .card-body {
    min-height:auto !important;
}
</style>


<style id="client-checkout-final-css">
#checkout {
    background:#fdfdfd !important;
    padding:40px 20px 70px !important;
    font-family:-apple-system, "SF Pro Text", sans-serif !important;
}

#checkout .container {
    max-width:1200px !important;
}

#checkout form > .row.mt-3 {
    display:grid !important;
    grid-template-columns:1.15fr .85fr !important;
    gap:60px !important;
    margin:0 !important;
}

#checkout .alert-message {
    grid-column:1 / -1 !important;
    max-width:100% !important;
    margin:0 !important;
}

#checkout .widget3.col-md-7,
#checkout .widget3.col-md-5 {
    width:100% !important;
    max-width:100% !important;
    flex:unset !important;
    padding:0 !important;
}

#checkout .form-title {
    background:transparent !important;
    color:#1a1a1a !important;
    font-size:1.25rem !important;
    font-weight:700 !important;
    margin:0 0 30px !important;
    padding:0 !important;
    border:0 !important;
    letter-spacing:-.5px !important;
}

#checkout .form-title span {
    display:none !important;
}

#checkout .widget3.col-md-7 .form-title {
    font-size:0 !important;
}

#checkout .widget3.col-md-7 .form-title:after {
    content:"Billing Details";
    font-size:1.25rem;
}

#checkout .card {
    background:#fff !important;
    border:1px solid #9a9a9a !important;
    border-radius:32px !important;
    padding:40px !important;
    box-shadow:0 25px 70px rgba(0,0,0,.06) !important;
    margin-bottom:30px !important;
}

#checkout label {
    display:block !important;
    font-size:.85rem !important;
    font-weight:600 !important;
    margin-bottom:8px !important;
    color:#444 !important;
    background:transparent !important;
}

#checkout input.form-control,
#checkout select.form-control,
#checkout textarea.form-control {
    width:100% !important;
    padding:15px 20px !important;
    background:#fff !important;
    border:1px solid #9a9a9a !important;
    border-radius:14px !important;
    font-size:.95rem !important;
    box-shadow:none !important;
    min-height:52px !important;
}

#checkout textarea.form-control {
    min-height:100px !important;
}

#checkout input.form-control:focus,
#checkout textarea.form-control:focus {
    border-color:#e9d180 !important;
    box-shadow:0 0 0 4px rgba(233,209,128,.1) !important;
}

#checkout .alert-warning {
    background:#fff8dc !important;
    color:#6b5a22 !important;
    border:0 !important;
    border-radius:16px !important;
    padding:16px !important;
}

#checkout .widget3.col-md-5 > .row {
    margin:0 !important;
}

#checkout .widget-3.col-md-12 {
    padding:0 !important;
}

#checkout .pa label {
    flex:1 !important;
    min-width:120px !important;
    border:2px solid #f0f0f0 !important;
    border-radius:20px !important;
    box-shadow:none !important;
    background:#fff !important;
    padding:12px !important;
    color:#1a1a1a !important;
}

#checkout .pa label img {
    height:35px !important;
    width:auto !important;
    object-fit:contain !important;
    margin:8px auto !important;
    display:block !important;
}

#checkout #accordion .card,
#checkout #accordion .card-body {
    padding:0 !important;
    border:0 !important;
    box-shadow:none !important;
    margin:0 !important;
}

#checkout #accordion .card-body {
    display:flex !important;
    gap:15px !important;
    flex-wrap:wrap !important;
}

#checkout .product {
    display:flex !important;
    align-items:center !important;
    padding:25px 0 !important;
    border-bottom:1px solid #f3f3f3 !important;
    margin:0 !important;
}

#checkout .product img {
    width:100px !important;
    height:120px !important;
    border-radius:20px !important;
    object-fit:cover !important;
    margin-right:25px !important;
    background:#f1f1f1 !important;
}

#checkout .product a {
    flex:1 !important;
    color:#1a1a1a !important;
    font-size:1.05rem !important;
    font-weight:700 !important;
    text-decoration:none !important;
}

#checkout .rvinfo {
    display:flex !important;
    justify-content:space-between !important;
    margin-bottom:12px !important;
    font-size:.95rem !important;
    color:#666 !important;
}

#checkout .rvinfo h4 {
    margin:0 !important;
}

#checkout .rvinfo:last-child {
    margin-top:20px !important;
    padding:18px !important;
    background:linear-gradient(90deg,#fffcf0,transparent) !important;
    border-radius:16px !important;
    border-top:2px dashed #e9d180 !important;
    font-weight:800 !important;
    font-size:1.5rem !important;
    color:#b89c4a !important;
}

#checkout input[type="submit"] {
    width:100% !important;
    background:linear-gradient(135deg,#000,#222) !important;
    color:#fff !important;
    border:0 !important;
    padding:24px !important;
    border-radius:50px !important;
    font-weight:800 !important;
    font-size:1.1rem !important;
    text-transform:uppercase !important;
    letter-spacing:2px !important;
    cursor:pointer !important;
    margin-top:20px !important;
    box-shadow:0 15px 35px rgba(0,0,0,.15) !important;
}

#checkout input[type="submit"]:hover {
    background:linear-gradient(135deg,#ffcc00,#ff9900) !important;
    color:#000 !important;
    transform:translateY(-5px) !important;
}

@media(max-width:950px) {
    #checkout {
        padding:25px 14px 55px !important;
    }

    #checkout form > .row.mt-3 {
        display:block !important;
    }

    #checkout .card {
        padding:24px !important;
        border-radius:24px !important;
    }

    #checkout .product img {
        width:82px !important;
        height:98px !important;
        margin-right:15px !important;
    }
}
</style>

@php
    $order=App\Models\Order::where('user_id',auth()->id())->select('address','shipping_charge','town','district','thana')->first();
    if(empty($order)){
        $town=''; $address=''; $district='';$thana='';
    }
@endphp
<style>
    .arrow-in {
        top: 5px;
    }
    .arrow2 .icofont-simple-down {
        display: block;
    }
    .arrow2 .icofont-simple-right {
        display: none;
    }
    .collapsed .arrow2 .icofont-simple-down {
        display: none !important;
    }
    .collapsed .arrow2 .icofont-simple-right {
        display: block !important;
    }
</style>
<div id="checkout">
    <div class="container">
        <form action="{{route('order.store_minimal')}}" method="POST">
            @csrf
            <input type="hidden" name="checkout_user_type" value="guest">
            <div class="row mt-3">
                <div class="col-md-8 offset-md-2 alert-message">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @else
                        <div class="alert"></div>
                    @endif
                </div>
                <div class="widget3 col-md-7">
                    <h4 class="form-title"><span>১</span> আপনার তথ্য </h4>
                    <div class="card">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="first_name">আপনার নাম <sup class="text-[red]"></sup>*</label>
                                <input required @if (auth()->user())
                                value="{{auth()->user()->name}}"
                                @endif name="first_name" id="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror" type="text" />
                                @error('first_name')<small class="form-text text-danger">{{$message}}</small>@enderror
                            </div>
                    
                            <div class="form-group ">
                                <!-- <label for="country">Country/Region <sup style="color: red;">*</sup></label> -->
                                <input name="country" id="country" value="{{ setting('COUNTRY_SERVE') ?? 'Bangladesh' }}"
                                    class="form-control @error('country') is-invalid @enderror" type="hidden" />
                                @error('country')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            {{-- <div class="form-group col-md-6">
                                <label for="city">Division <sup style="color: red;">*</sup></label>
                                <select name="city" id="divisions"
                                    class="form-control @error('city') is-invalid @enderror"
                                    onchange="divisionsList();">
                                    <option disabled>Select Division</option>
                                    <option @isset($order->town) @if($order->town =='Barishal')selected @endif @endisset
                                        value="Barishal">Barishal</option>
                                    <option @isset($order->town) @if($order->town=='Chattogram')selected @endif
                                        @endisset value="Chattogram">Chattogram</option>
                                    <option @isset($order->town) @if($order->town =='Dhaka')selected @endif @endisset
                                        value="Dhaka">Dhaka</option>
                                    <option @isset($order->town) @if($order->town =='Khulna')selected @endif @endisset
                                        value="Khulna">Khulna</option>
                                    <option @isset($order->town) @if($order->town =='Mymensingh')selected @endif
                                        @endisset value="Mymensingh">Mymensingh</option>
                                    <option @isset($order->town) @if($order->town =='Rajshahi')selected @endif @endisset
                                        value="Rajshahi">Rajshahi</option>
                                    <option @isset($order->town) @if($order->town =='Rangpur')selected @endif @endisset
                                        value="Rangpur">Rangpur</option>
                                    <option @isset($order->town) @if($order->town =='Sylhet')selected @endif @endisset
                                        value="Sylhet">Sylhet</option>
                                </select>
                                <!--/ Division Section-->

                                @error('city')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror

                            </div> --}}
                            {{-- <div class="form-group col-md-6">
                                <label for="district">District <sup style="color: red;">*</sup></label>
                                <select name="district" class="form-control @error('district') is-invalid @enderror"
                                    id="distr" onchange="thanaList();">
                                    <option disabled>Select District</option>
                                    @isset($order->district)
                                    <option selected value="{{$order->district}}">{{$order->district}}</option>
                                    @endisset
                                </select>
                                <!--/ Districts Section-->
                                @error('district')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div> --}}
                            {{-- <div class="form-group col-md-6">
                                <label for="district">Thana <sup style="color: red;">*</sup></label>
                                <select name="thana" class="form-control @error('district') is-invalid @enderror"
                                    id="polic_sta">
                                    <option disabled>Select Thana</option>
                                    @isset($order->thana)
                                    <option selected value="{{$order->thana}}">{{$order->thana}}</option>
                                    @endisset
                                </select>
                            </div> --}}
                            <div class="form-group col-md-12">
                                <label for="phone">মোবাইল নম্বর <sup class="text-[red]">*</sup></label>
                                <input @if (auth()->user())
                                value="{{auth()->user()->phone}}"
                                @endif required name="phone" id="phone"
                                    class="form-control @error('phone') is-invalid @enderror" type="number" />
                                @error('phone')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>


                            <div class="form-group col-md-12 d-none" id="email_wrap">
                                <label for="email">Email Address <sup class="text-[red]">*</sup></label>
                                <input name="email" id="email" class="form-control @error('email') is-invalid @enderror" type="text"  />
                                @error('email')
                                    <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-12">
                                    <label for="address">সম্পূর্ণ ঠিকানা</label>
                                    <textarea name="address" id="address" rows="4"
                                        class="form-control "></textarea>
                                    @error('address')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                            </div>
                            
                            <!--<div class="form-group col-md-12">-->
                            <!--    <select name="shipping_range" id="shipping_range" class="form-control">-->
                            <!--        <option value="1">Inside {{ setting('shipping_range_inside') }} ({{ setting('shipping_charge') }})</option>-->
                            <!--        <option value="0">Outside of ({{ setting('shipping_charge_out_of_range') }})</option>-->
                            <!--    </select>-->
                            <!--</div>-->
                            
                            <div class="alert alert-warning mt-2 text-center" role="alert">
                                ⚠️ শিপিং চার্জ প্রতি কেজি ঢাকার ভিতরে ১১০ পরবর্তী প্রতি কেজি ১৫ টাকা করে যোগ হবে। ঢাকার বাহিরে প্রতি কেজি ১৩০ টাকা পরবর্তী প্রতি কেজি ২০ টাকা যোগ হবে।
                            </div>
                            

                            {{-- <div class="form-group">
                                <label for="postcode">Postcode / ZIP(optional)</label>
                                <input  name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" type="text"  />
                                @error('email')
                                    <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div> --}}

                            

                            {{-- <div class="form-group col-md-6">
                                <label for="email">Email Address <sup style="color: red;">*</sup></label>
                                <input required name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" type="text" />
                                @error('email')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div> --}}
                            {{-- <div class="form-group col-md-12">
                                <label for="company">Company (optional)</label>
                                <input name="company" id="company"
                                    class="form-control @error('company') is-invalid @enderror" type="text" />
                                @error('email')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div> --}}
                        </div>
                    </div>
                </div>




                <div class="widget3 col-md-5">
                    <div class="row">
                        {{-- <div class="widget3 col-md-12">
                            <h4 class="form-title"><span>২</span>পেমেন্ট পদ্ধতি </h4>
                            <div class="card">
                                <div class="form-group ofl">
                                    <input name="shipping_method" value="Free"  style="margin-right: 5px;position: relative;top: 0px;" type="radio">
                                    <label for="free">Free</label>
                                </div>
                                <div style="margin:0" class="form-group ofl">
                                    <input name="shipping_method" value="Prime"  style="margin-right: 5px;position: relative;top: 0px;" type="radio"><label for="bank">Prime</label>
                                </div>
                                @error('shipping_method')
                                    <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div> --}}
                        <style>
                            #accordion .card {
                                padding: 0 !important;
                            }

                            #accordion .card-body {
                                padding: 0;
                                margin-top: 10px;
                            }

                            #accordion .card-header {
                                padding: 0;
                            }

                            #accordion .card-header h5 div {
                                font-size: 15px;
                                padding: 10px;
                                background: var(--primary_color);
                                color: white;
                                cursor: pointer;
                            }

                            label img {
                                width: 100px;
                                height: 50px;
                                object-fit: contain;
                            }

                            .pa label {
                                text-align: center;
                                box-shadow: 0px 0px 6px gainsboro;
                                padding: 10px 20px;
                                border-radius: 5px;
                                position: relative;
                                cursor: pointer;
                            }

                            .payment_method {
                                position: absolute;
                                z-index: -9;
                            }
                        </style>
                        <div class="widget-3 col-md-12">
                            <div class="widget3">
                                <h4 class="form-title"><span>২</span>পেমেন্ট পদ্ধতি</h4>
                                <div class="card pa">
                                    <div class="form-row">

                                        <div id="accordion" class="col-12">
                                            {{-- <div class="card">
                                                
                                                <div class="card-header" id="headingOne">
                                                    <h5 class="mb-0">
                                                        <div class="" data-toggle="collapse" data-target="#collapseOne"
                                                            aria-expanded="true" aria-controls="collapseOne">
                                                            Online Pay
                                                        </div>
                                                    </h5>
                                                </div>

                                                <div id="collapseOne" class="collapse " aria-labelledby="headingOne"
                                                    data-parent="#accordion">
                                                    <div class="card-body">
                                                        @if(setting('g_aamar')=='true')
                                                        <label for="aamarpay">
                                                            <input type="radio" name="payment_method"
                                                                class="payment_method" value="aamarpay" id="aamarpay">
                                                            <img src="{{asset('/')}}icon/aamarpay_logo.png">
                                                            Aamarpay
                                                        </label>
                                                        @endif

                                                        @if(setting('g_uddok')=='true')
                                                        <label for="uddoktapay">
                                                            <input type="radio" name="payment_method"
                                                                class="payment_method" value="uddoktapay"
                                                                id="uddoktapay">
                                                            <img src="{{asset('/')}}icon/uddoktapay.png">
                                                            Uddoktapay
                                                        </label>
                                                        @endif


                                                        @if(setting('g_wallate')=='true')
                                                        <label for="wallate">
                                                            <input type="radio" name="payment_method"
                                                                class="payment_method" value="wallate" id="wallate">
                                                            <img src="{{asset('/')}}icon/wallet.png">
                                                            Wallate
                                                            <p>{{auth()->user()->wallate}}</p>
                                                        </label>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div> --}}

                                            <div class="card">
                                                {{-- <div class="card-header" id="headingTwo">
                                                    <h5 class="mb-0">
                                                        <div class=" collapsed" data-toggle="collapse"
                                                            data-target="#collapseTwo" aria-expanded="false"
                                                            aria-controls="collapseTwo">
                                                            Offline Pay
                                                        </div>
                                                    </h5>
                                                </div> --}}
                                                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo"
                                                    data-parent="#accordion">
                                                    <div class="card-body">
                                                        
                                                        @if(setting('g_cod')=='true')
                                                        <label for="cod">
                                                            <input type="radio" name="payment_method"
                                                                class="payment_method" value="Cash on Delivery"
                                                                id="cod" checked>
                                                            <img src="{{asset('/')}}icon/delivery-man.png">
                                                            ক্যাশ অন<br>ডেলিভারি
                                                        </label>
                                                        @endif

                                                        @if(setting('g_aamar')=='true')
                                                        <label for="aamarpay">
                                                            <input type="radio" name="payment_method"
                                                                class="payment_method" value="aamarpay" id="aamarpay">
                                                            <img src="{{asset('/')}}icon/aamarpay_logo.png">
                                                            <small>Aamarpay<br>Online</small>
                                                        </label>
                                                        @endif
                                                        
                                                        @if(setting('g_uddok')=='true')
                                                        <label for="uddoktapay">
                                                            <input type="radio" name="payment_method"
                                                                class="payment_method" value="uddoktapay"
                                                                id="uddoktapay">
                                                            <img src="{{asset('/')}}icon/uddoktapay.png">
                                                            <small>Uddoktapay<br>Online</small>
                                                        </label>
                                                        @endif

                                                        @auth                                                            
                                                        @if(setting('g_wallate')=='true')
                                                        <label for="wallate">
                                                            <input type="radio" name="payment_method"
                                                                class="payment_method" value="wallate" id="wallate">
                                                            <img src="{{asset('/')}}icon/wallet.png">
                                                            Wallate
                                                            <p>{{auth()->user()->wallate}}</p>
                                                        </label>
                                                        @endif
                                                        @endauth

                                                        @if(setting('g_bkash')=='true')
                                                        <!--<label for="Bkash">-->
                                                        <!--    <input type="radio" name="payment_method"-->
                                                        <!--        class="payment_method" value="Bkash" id="Bkash">-->
                                                        <!--    <img src="{{asset('/')}}icon/bkash.png">-->
                                                        <!--    Bkash-->
                                                        <!--</label>-->
                                                        @endif
                                                        @if(setting('g_nagad')=='true')
                                                        <!--<label for="Nagad">-->
                                                        <!--    <input type="radio" name="payment_method"-->
                                                        <!--        class="payment_method" value="Nagad" id="Nagad">-->
                                                        <!--    <img src="{{asset('/')}}icon/nagad.png">-->
                                                        <!--    Nagad-->
                                                        <!--</label>-->
                                                        @endif
                                                        @if(setting('g_rocket')=='true')
                                                        <!--<label for="Rocket">-->
                                                        <!--    <input type="radio" name="payment_method"-->
                                                        <!--        class="payment_method" value="Rocket" id="Rocket">-->
                                                        <!--    <img src="{{asset('/')}}icon/rocket.png">-->
                                                        <!--    Rocket-->
                                                        <!--</label>-->
                                                        @endif
                                                        @if(setting('g_bank')=='true')
                                                        <!--<label for="Bank">-->
                                                        <!--    <input type="radio" name="payment_method"-->
                                                        <!--        class="payment_method" value="Bank" id="Bank">-->
                                                        <!--    <img src="{{asset('/')}}icon/bank.png">-->
                                                        <!--    Bank-->
                                                        <!--</label>-->
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                                    data-parent="#accordion">
                                                </div>
                                            </div>
                                        </div>
                                        @error('payment_method')
                                        <small class="form-text text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <p class="mt-2 bg-[#dcdcdc80] p-[10px] rounded-[5px] mb-[10px]" id="appended">
                                    </p>
                                    <!--<div id="payment-details">পণ্য হাতে পেয়ে টাকা দিন।{{-- for COD auto Select --}}</div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="form-title"><span>৩</span>অর্ডারের তথ্য</h4>
                    <div class="card">
                        <?php
use App\Models\Product;

$stotal = 0;
                        $ids = [];
                        $cartCollection = Cart::content();
                        $data = $cartCollection->sortBy('weight');
                        ?>
                        @foreach ($data as $item)
                            <div class="product mb-[10px] flex">
                                <img class="w-[50px]" src="{{asset('uploads/product/'.$item->options->image)}}" alt="">
                                <a class="ml-[10px]"
                                    href="{{route('product.details', $item->options->slug)}}">{{$item->name}}</a>
                                <?php
                                    $whole = Product::find($item->id);
                        if (! in_array("$whole->user_id", $ids)) {
                            $ids[] = $whole->user_id;
                        }
                        if ($item->qty >= 6 && $whole->whole_price > 0) {
                            $istotal = $item->qty * $whole->whole_price;
                            $stotal += $item->qty * $whole->whole_price;
                        } else {
                            $istotal = $item->subtotal;
                            $stotal += $item->subtotal;
                        }?>
                                <span class="flex-[1_auto] text-right">{{$istotal.'.00'}}</span>
                            </div>
                        @endforeach
                        <?php
                            $seller_count = count($ids);
                        ?>
                        <input type="hidden" name="stotal" value="{{$stotal}}">
                        <input type="hidden" name="seller_count" id="seller_count" value="{{$seller_count}}">
                        <div class="form-group">
                            <div class="form-group instruction">
                                <a class="collapsed" data-toggle="collapse" href="#collapseExample" role="button"
                                    aria-expanded="false" aria-controls="collapseExample">
                                    <span><label for="">কুপন</label> </span><span class="arrow"></span>
                                </a>
                                <div class="collapse" id="collapseExample">
                                    <input type="text" id="coupon" class="form-control" placeholder="কুপন কোড" />
                                    <button type="button" class="btn btn-primary btn-block py-2"
                                        id="apply-coupon">ব্যবহার করুন</button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="0" id="partial_paid" name="partial_paid" class="form-control"
                            placeholder="Partial Amount" />
                        <!-- <div class="form-group">-->
                        <!--    <div class="form-group instruction">-->
                        <!--        <a data-toggle="collapse" href="#collapseWall" role="button" aria-expanded="false" aria-controls="collapseWall">-->
                        <!--            <span><label for="">Use Wallate</label> </span> =={{-- {{auth()->user()->wallate}} --}}<span class="arrow"></span>-->
                        <!--        </a>-->
                        <!--        <div class="collapse" id="collapseWall">-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!--<hr>-->

                        <div class="rvinfo">
                            <span>মোট</span>
                            <span><span id="sub-total">{{$stotal}}</span><strong> {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></span>
                        </div>
                        <!--<div class="rvinfo">-->
                        <!--    <span>-->
                        <!--        Shipping Charge @if ($stotal > setting('shipping_free_above'))(Free)@endif-->
                        <!--    </span>-->
                        <!--    <span>-->
                        <!--        +-->
                        <!--        @if ($stotal > setting('shipping_free_above'))-->
                        <!--            0.00-->
                        <!--        @else-->
                        <!--            <span id="ship-charge">-->
                        <!--                @if(isset($order->shipping_charge))-->

                        <!--                {{$order->single_charge*$seller_count}}-->

                        <!--                @else 0.00 @endif-->
                        <!--            </span>-->
                        <!--        @endif-->
                        <!--        <strong> {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong>-->
                        <!--    </span>-->
                        <!--</div>-->
                        <div class="rvinfo coupon">
                            <span>কুপন <span class="coupon-name"></span></span>
                            <span>- <span
                                    id="coupon">{{Session::has('coupon') ? number_format(Session::get('coupon')['discount'], 2, '.', ',') : '0.00'}}</span><strong>
                                        {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></span>
                        </div>

                        <hr>
                        <div class="rvinfo">
                            <span>সর্বমোট</span>
                            <h4>
                                @if (Session::has('coupon'))
                                @php
                                $sub_total = $stotal;
                                $discount = Session::get('coupon')['discount'];
                                $rep_sub = str_replace(',', '', $sub_total);
                                $total = number_format($rep_sub - $discount, 2, '.', ',');
                                @endphp
                                @endif
                                <strong>
                                    @if ($stotal > setting('shipping_free_above'))
                                        {{ $stotal }}
                                    @else
                                        <span id="total">{{$total ?? $stotal}}</span> {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}
                                    @endif
                                </strong>
                            </h4>
                        </div>
                    </div>
                    <input value="অর্ডার করুন" type="submit">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="{{asset('/')}}assets/frontend/js/city.js"></script>

<!-- Hidden input for lead URL -->
<input type="hidden" id="lead_store_url" value="{{ route('incomplete.lead.store') }}">
<script>
    $(document).ready(function () {
        $(document).on('click', '#apply-coupon', function (e) {
            e.preventDefault();
            $('#coupon').removeClass('is-invalid');
            let stotal = "{!! $stotal !!}";
            let code = $('input#coupon').val();
            let seller_count = $('#seller_count').val();
            let shipping_charge = 0;

            if ($("select[name='shipping_range']").val() == 1) {
                let charge = "{!! setting('shipping_charge') !!}";
                shipping_charge += parseInt(charge);
            } else {
                let charge = "{!! setting('shipping_charge_out_of_range') !!}";
                shipping_charge += parseInt(charge);
            }
            shipping_charge = parseInt(shipping_charge) * parseInt(seller_count);
        
            if (code != '') {
                $.ajax({
                    type: 'GET',
                    url: '/apply/coupon/' + code + '/' + stotal,
                    dataType: "JSON",
                    success: function (response) {
                        console.log(response);
                        $('.alert-message').removeClass('d-none');
                        if (response.alert == 'success') {
                            $('.alert-message .alert').removeClass('alert-danger').addClass('alert-success').text(response.message);

                            $('span#ship-charge').text(number_format(shipping_charge, 2, '.', ','));
                            $('span#coupon').text(number_format(response.discount, 2, '.', ','));
                            let total = response.total + shipping_charge;
                            $('span#total').text(number_format(total, 2, '.', ','));
                            $('span.coupon-name').text('(' + code + ')');

                            $('#coupon').val('')
                        } else {
                            $('.alert-message .alert').removeClass('alert-success').addClass('alert-danger').text(response.message);

                        }
                    },
                    error: function (xhr) {
                        console.log(xhr);
                    }
                });
            } else {
                $('#coupon').addClass('is-invalid');
            }
            setTimeout(() => {
                $('.alert-message .alert').removeClass('alert-danger alert-success').text('');
            }, 10000);
        });

        // default COD - Payment Method
        $('#cod').parent("label").css("background", "#22385A");
        $('#cod').parent("label").css("color", "white");
        $('.payment_method').change(function(e) {
            // Check if the selected payment method is not "Cash on Delivery"
            if ($(this).val() !== 'Cash on Delivery') {
                // If not, automatically select the "Cash on Delivery" option
                $('#cod').parent("label").css("background", "white");

            }
        });

        // Change - Payment Method
        $(document).on('click', '.payment_method', function (e) {
            $("label").css("background", "white");
            $(this).parent("label").css("background", "#22385A");
            $(this).parent("label").css("color", "white");
            let method = $(this).val();
            let html = '';
            var bkash = "{!! setting('bkash') !!}";
            var nogod = "{!! setting('nagad') !!}"
            var rocket = "{!! setting('rocket') !!}"
            var bank = "{!! setting('bank_name') !!}"
            var branch = "{!! setting('branch_name') !!}"
            var holder = "{!! setting('holder_name') !!}"
            var account = "{!! setting('bank_account') !!}"
            var routing = "{!! setting('routing') !!}"
            var appended = $('#appended');
            if (method == 'Bkash') {
                off_email();
                appended.html(bkash + ' - এই নাম্বারে টাকা পাঠিয়ে নিচের ফিল্ডে  Transaction ID টি দিন');
            } else if (method == 'Nagad') {
                off_email();
                appended.html(nogod + ' - এই নাম্বারে টাকা পাঠিয়ে নিচের ফিল্ডে  Transaction ID টি দিন');
            } else if (method == 'Rocket') {
                off_email();
                appended.html(rocket + ' - এই নাম্বারে টাকা পাঠিয়ে নিচের ফিল্ডে  Transaction ID টি দিন');
            } else if (method == 'Bank') {
                off_email();
                appended.html('নিচে দেয়া ব্যাংকে টাকা পাঠিয়ে নিচের ফিল্ডগুলো পূরণ করুন <br> ' + 'Bank Name: ' + bank + '<br>Branch: ' + branch + '<br>holder: ' + holder + '<br>Account: ' + account + '<br>Routing: ' + routing);
            } else if (method == 'Cash on Delivery') {
                off_email();
                appended.html('পণ্য হাতে পেয়ে টাকা দিন। ');
            } else if (method == 'uddoktapay') {

                // Email On
                $('#email_wrap').removeClass('d-none');
                $('#email').prop('required', true);

            } else {
                off_email();
                appended.html('');
            }


            if (method == 'Bkash' || method == 'Nagad' || method == 'Rocket') {
                off_email();

                html += '<div class="form-group">'
                html += '<label for="mobile_number">Mobile Number</label>'
                html += '<input required type="text" name="mobile_number" id="mobile_number" class="form-control" placeholder="Enter your mobile number"/>'
                html += '</div>'
                html += '<div class="form-group">'
                html += '<label for="transaction_id">Transaction ID</label>'
                html += '<input required type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="Enter transaction ID"/>'
                html += '</div>'
            } else if (method == 'Bank') {
                off_email();

                html += '<div class="form-group">'
                html += '<label for="bank_name">Bank Name</label>'
                html += '<input required type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Enter bank name"/>'
                html += '</div>'
                html += '<div class="form-group">'
                html += '<label for="account_number">Account Number</label>'
                html += '<input required type="text" name="account_number" id="account_number" class="form-control" placeholder="Enter account number"/>'
                html += '</div>'
                html += '<div class="form-group">'
                html += '<label for="holder_name">Holder Name</label>'
                html += '<input required type="text" name="holder_name" id="holder_name" class="form-control" placeholder="Enter holder name"/>'
                html += '</div>'
                html += '<div class="form-group">'
                html += '<label for="branch">Branch Name</label>'
                html += '<input required type="text" name="branch" id="branch" class="form-control" placeholder="Enter branch name"/>'
                html += '</div>'
                html += '<div class="form-group">'
                html += '<label for="routing">Routing Number</label>'
                html += '<input required type="text" name="routing" id="routing" class="form-control" placeholder="Enter routing number"/>'
                html += '</div>'
            } else {
                
                html = 'Onlne Payment Selectd, Place order and pay online';
            }
            $('#payment-details').html(html);
        })


        // Email Off
        function off_email(){
            $('#email_wrap').addClass('d-none');
            $('#email').removeAttr('required');
        }
            

        $(document).on('change', '#shipping_range', function (e) {
            div();
        });

        function div() {
            let shipping_charge = 0;
            let seller_count = $('#seller_count').val();
            if ($("select[name='shipping_range']").val() == 1) {
                let charge = "{!! setting('shipping_charge') !!}";
                shipping_charge += parseInt(charge);
            } else {
                let charge = "{!! setting('shipping_charge_out_of_range') !!}";
                shipping_charge += parseInt(charge);
            }

            shipping_charge = parseInt(shipping_charge) * parseInt(seller_count);

            let subtotal = $('span#sub-total').text();
            let coupon = $('span#coupon').text();

            let rep_subtotal = subtotal.replace(',', '');
            let rep_coupon = coupon.replace(',', '');

            let total = (parseInt(rep_subtotal) + shipping_charge) - parseInt(rep_coupon);
            $('span#ship-charge').text(number_format(shipping_charge, 2, '.', ','));
            $('span#total').text(number_format(total, 2, '.', ','));
        }
        div()

        function number_format(number, decimals, dec_point, thousands_sep) {
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                toFixedFix = function (n, prec) {
                    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                    var k = Math.pow(10, prec);
                    return Math.round(n * k) / k;
                },
                s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }
    });
</script>
<script>
    $(document).ready(function() {
        // GA4 begin_checkout event
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            "event": "begin_checkout",
            "ecommerce": {
                "currency": "BDT",
                "value": {{ $stotal }},
                "items": [
                    @foreach ($data as $item)
                    {
                        "item_id": "{{ $item->id }}",
                        "item_name": "{{ $item->name }}",
                        "price": {{ $item->price }},
                        "quantity": {{ $item->qty }}
                    },
                    @endforeach
                ]
            }
        });

        // GA4 add_payment_info event (Triggered when name and phone are filled)
        let paymentInfoSent = false;
        function checkPaymentInfo() {
            let name = $('#first_name').val();
            let phone = $('#phone').val();
            if (name && phone && !paymentInfoSent) {
                window.dataLayer.push({
                    "event": "add_payment_info",
                    "ecommerce": {
                        "currency": "BDT",
                        "value": {{ $stotal }},
                        "items": [
                            @foreach ($data as $item)
                            {
                                "item_id": "{{ $item->id }}",
                                "item_name": "{{ $item->name }}",
                                "price": {{ $item->price }},
                                "quantity": {{ $item->qty }}
                            },
                            @endforeach
                        ],
                        "customer_info": {
                            "first_name": name,
                            "phone": phone
                        }
                    }
                });
                paymentInfoSent = true;
            }
        }

        $('#first_name, #phone').on('blur', checkPaymentInfo);
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Cart incomplete lead tracking initialized');

    const nameInput = document.getElementById('first_name');
    const phoneInput = document.getElementById('phone');
    const emailInput = document.getElementById('email');
    const addressInput = document.getElementById('address');
    const leadUrlInput = document.getElementById('lead_store_url');

    if (!nameInput || !phoneInput || !leadUrlInput) {
        console.error('Required elements not found');
        return;
    }

    const leadUrl = leadUrlInput.value;
    let typingTimer;
    const delay = 1000;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    function collectCartData() {
        const cartItems = [];
        let subtotal = 0;

        document.querySelectorAll('.product').forEach(productDiv => {
            try {
                const img = productDiv.querySelector('img');
                const link = productDiv.querySelector('a');
                const priceSpan = productDiv.querySelector('span[style*="text-align: right"]');
                
                if (link && priceSpan) {
                    const productUrl = link.getAttribute('href');
                    const productName = link.textContent.trim();
                    const productImage = img ? img.getAttribute('src') : '';
                    const itemTotal = parseFloat(priceSpan.textContent.replace(/[^0-9.]/g, '')) || 0;
                    
                    const urlParts = productUrl.split('/');
                    const slug = urlParts[urlParts.length - 1];
                    
                    cartItems.push({
                        product_name: productName,
                        product_slug: slug,
                        product_url: productUrl,
                        image: productImage,
                        total: itemTotal,
                        added_at: new Date().toISOString()
                    });
                    
                    subtotal += itemTotal;
                }
            } catch (e) {
                console.error('Error collecting cart item:', e);
            }
        });

        return { items: cartItems, subtotal: subtotal, total_items: cartItems.length };
    }

    function collectFormData() {
        const cartData = collectCartData();
        const stotal = document.querySelector('input[name="stotal"]')?.value || '0';
        const sellerCount = document.querySelector('input[name="seller_count"]')?.value || '0';

        return {
            name: nameInput.value.trim(),
            phone: phoneInput.value.trim(),
            email: emailInput?.value.trim() || '',
            address: addressInput?.value.trim() || '',
            cart_items: cartData.items,
            cart_subtotal: parseFloat(stotal) || cartData.subtotal,
            cart_total_items: cartData.total_items,
            seller_count: sellerCount,
            page_type: 'cart_checkout'
        };
    }

    function saveIncompleteLead() {
        const formData = collectFormData();

        if (!formData.name && !formData.phone) {
            console.log('Name and phone both empty, skipping save');
            return;
        }

        console.log('Saving cart lead:', formData);

        fetch(leadUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            console.log('Cart lead saved:', data);
        })
        .catch(error => {
            console.error('Error saving cart lead:', error);
        });
    }

    function handleTyping() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(saveIncompleteLead, delay);
    }

    nameInput.addEventListener('input', handleTyping);
    phoneInput.addEventListener('input', handleTyping);
    
    if (emailInput) emailInput.addEventListener('input', handleTyping);
    if (addressInput) addressInput.addEventListener('change', saveIncompleteLead);

    const submitButton = document.querySelector('input[type="submit"]');
    if (submitButton) {
        submitButton.addEventListener('click', saveIncompleteLead);
    }

    window.addEventListener('beforeunload', function() {
        const formData = collectFormData();
        if (formData.name || formData.phone) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', leadUrl, false);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            try {
                xhr.send(JSON.stringify(formData));
            } catch (err) {
                console.error('Error in beforeunload:', err);
            }
        }
    });

    setInterval(function() {
        const formData = collectFormData();
        if (formData.name || formData.phone) saveIncompleteLead();
    }, 30000);

    console.log('Cart tracking setup complete');
});
</script>
@endpush