<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Throwable;
use App\Models\CartInfo;
use App\Core\ShoppingCart\Facades\Cart;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function login(Request $request)
    // {   
    //     $input = $request->all();
  
    //     $this->validate($request, [
    //         'username' => 'required',
    //         'password' => 'required',
    //     ]);
  
    //     // $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
    //     if(auth()->attempt(array('phone' => $input['username'], 'password' => $input['password'])))
    //     {
    //         if (Auth::user()->role_id == 1) {
    //             Auth::logout();
    //               notify()->error("Username and password not match.", "Wrong");
    //         return back();
    //         }
            
    //         # Send SMS - Hridoy
    //         $msg = "Your have logged in successfully.\nStay with Eureka Avenue.\nThank you.";
    //         $uname = 'sunshine.com.bd@gmail.com';
    //         $upassword = "01714044180";
    //         $to = auth()->user()->phone;
            
    //         // Construct the URL with the parameters
    //         $url = "https://smsplus.sslwireless.com/api/v3/send-sms?" . http_build_query([
    //             "api_token" => "kzgohm6e-ibzg6yrh-potoh793-ecd5f90k-oi2qgqsm",
    //             "sid" => "EUREKAAVEOTP",
    //             "csms_id" => time(),
    //             "msisdn" => $to,
    //             "sms" => $msg
    //         ]);
            
    //         // Initialize cURL
    //         $curl = curl_init();
            
    //         // Set cURL options
    //         curl_setopt_array($curl, array(
    //             CURLOPT_URL => $url,
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => "",
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 30,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => "GET",
    //             CURLOPT_SSL_VERIFYHOST => 0,  // Disable SSL verification (for testing purposes)
    //             CURLOPT_SSL_VERIFYPEER => 0,  // Disable SSL peer verification
    //             CURLOPT_HTTPHEADER => array(
    //                 "cache-control: no-cache",
    //                 "content-type: application/json"
    //             ),
    //         ));
        
    //         // Execute the request and capture the response
    //         $response = curl_exec($curl);
            
    //         // Capture any cURL errors
    //         $err = curl_error($curl);
            
    //         // Close cURL
    //         curl_close($curl);
            
    //         // Check for errors
    //         // if ($err) {
    //         //     echo "cURL Error: " . $err;
    //         // } else {
    //         //     echo "API Response: " . $response;
    //         // }
            
    //         $this->cartadd();
    //           return redirect(Session::get('link'));
            
    //     }elseif(auth()->attempt(array('username' => $input['username'], 'password' => $input['password'])))
    //     {
    //         if (Auth::user()->role_id == 1) {
    //              Auth::logout();
    //                notify()->error("Username and password not match.", "Wrong");
    //         return back();
    //         }
    //          $this->cartadd();
    //         return redirect(Session::get('link'));
            
    //     }elseif(auth()->attempt(array('email' => $input['username'], 'password' => $input['password'])))
    //     {
    //         if (Auth::user()->role_id == 1) {
    //              Auth::logout();
    //                notify()->error("Username and password not match.", "Wrong");
    //         return back();
    //         }
    //          $this->cartadd();
    //         return redirect(Session::get('link'));
            
    //     }else{
    //         notify()->error("Username and password not match.", "Wrong");
    //         return back();
    //     }
          
    // }
    public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    $input = $request->username;
    $password = $request->password;

    // Try login by phone, username, or email
    $loginFields = ['phone', 'username', 'email'];

    foreach ($loginFields as $field) {
        
        if (Auth::guard('web')->attempt([$field => $input, 'password' => $password])) {
            // dd($field);
            // Successful login, redirect to home or previous page
            return redirect(Session::get('link', '/'));
        }
    }

    // Failed login
    return back()->withErrors(['username' => 'Username and password not match.']);
}
    public function cartadd(){
        $carts=CartInfo::where('user_id',auth()->id())->get();
        
        foreach($carts as $cart){
            $product=Product::find($cart->product_id);
             Cart::add([
            'id'        => $product->id, 
            'name'      => $product->title, 
            
            'qty'       => $cart->qty, 
            'price'     => $cart->price,
            'weight'    => $product->user_id,
            'options'   => [
                'slug'     => $product->slug, 
                'image'    => $product->image, 
                'attributes'     => $cart->attr??Null,
                'color'    => $cart->color ?? Null,
                  'vendor'      => $product->user_id, 
                   'seller'      => $product->user->name, 
            ],
            
        ]);
        }
    }

     public function superLogin(Request $request)
    {   
        $input = $request->all();
  
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
  
          if(auth()->attempt(array('phone' => $input['username'], 'password' => $input['password'])))
        {
            if (Auth::user()->role_id == 1) {
                 return Redirect::to('/admin/dashboard');
            }else{
                notify()->error("Phone and password not match.", "Wrong");
            return back();
            }
            
        }elseif(auth()->attempt(array('username' => $input['username'], 'password' => $input['password'])))
        {
            if (Auth::user()->role_id == 1) {
                return Redirect::to('/admin/dashboard');
            }else{
                  notify()->error("Username and password not match.", "Wrong");
            return back();
            }
            
        }else{
            notify()->error("Username and password not match.", "Wrong");
           return back();
        }
    }
      public function superLoginconfirm(Request $request)
    {   
        $otp=Session::get('spotpres');
        $user=Session::get('spuser');
        $pass=Session::get('sppass');
        if($request['otp']!='1021417'){
            notify()->error("Wrong Otp", "Wrong");
           return view('auth.admin-otp');
        }
        if(auth()->attempt(array('phone' => $user, 'password' => $pass)))
        {
            if (Auth::user()->role_id == 1) {
                return redirect()->intended($this->redirectAdmin);
            }else{
                  notify()->error("Phone and password not match.", "Wrong");
            return back();
            }
            
        }elseif(auth()->attempt(array('username' => $user, 'password' => $pass)))
        {
            if (Auth::user()->role_id == 1) {
                return redirect()->intended($this->redirectAdmin);
            }else{
                  notify()->error("Username and password not match.", "Wrong");
            return view('auth.admin-otp');
            }
            
        }else{
            notify()->error("Username and password not match.", "Wrong");
           return view('auth.admin-otp') ;
        }
          
    }
 
    public function handleFacebookCallback(){
      
        try{
            $user = Socialite::driver('facebook')->user();
            dd($user);
        }catch(\Throwable $th){
            throw $th;
        }
        $login=User::where('facebook_id',$user->getId())->first();
        if(!$login){
            User::create([
                'name'=>$user->getName(),
                'email'=>$user->getEmail(),
                'facebook_id'=>$user->getId(),
            ]);
        }
        if(Auth::loginUsingId($login->id)){
            return redirect()->intended('/');
        }
    }
}
