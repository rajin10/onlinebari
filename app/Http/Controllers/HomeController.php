<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Collection;
use App\Models\DeviceId;
use App\Models\HomepageVideo;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShopInfo;
use App\Models\Slider;
use App\Models\Unproduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $users = Campaign::where([['is_flash', 1], ['end', '<', date('Y-m-d h-m-s')]])->get();
        foreach ($users as $user) {

            $user->is_flash = 0;
            $user->end = null;
            $user->update();
        }

        $sliders = Slider::where('status', true)->where('is_pop', false)->where('is_sub', false)->where('is_feature', false)->latest('id')->take(6)->get(['image', 'url']);
        $sliders_f = Slider::where('status', true)->where('is_sub', false)->where('is_pop', false)->where('is_feature', true)->latest('id')->take(4)->get(['image', 'url']);
        // slider one
        $new_sliders_one = Slider::where('status', true)->where('is_pop', false)->where('is_sub', false)->where('is_feature', false)->latest('id')->take(6)->get(['image', 'url']);

        // Fetch Banners
        $banners = Banner::where('status', true)->latest('id')->take(6)->get(['image', 'url']);

        // Fetch a single pop-up banner
        $popBanner = Banner::where('is_pop', true)->first();

        // Fetch a single pop-up slider
        $pop = Slider::where('is_pop', true)->first();

        // $categories     = Category::where('status', true)->latest('id')->get(['name', 'slug', 'cover_photo']);

        // $categories = Category::where('status', true)->orderBy('pos', 'asc')->get(['name', 'slug', 'cover_photo']);

        // Commented by Hridoy
        //     $categories = Category::where('status', true)
        // ->orderBy('pos', 'asc') // Ordering by pos
        // ->get(['name', 'slug', 'cover_photo', 'pos']);

        $shops = ShopInfo::latest('id')->take('6')->get();
        $campaigns_product = Campaign::where('status', 1)->where('is_flash', '1')->get();
        $i = 1;

        $productIds = DB::table('category_product')->where('category_id', '!=', ['13', '9'])->take(12)->get()->pluck('product_id');
        $products = Product::with(['reviews', 'brand', 'colors', 'categories'])->whereIn('id', $productIds)->where('status', true)->latest('id')->take(12)->get();
        $randomProducts = Product::with(['brand', 'reviews'])->where('status', true)->where('reach', '>', '0')->orderBy('reach', 'DESC')->take('6')->get();

        $unproducts = Unproduct::where('status', 1)->inRandomOrder()->take(6)->get();

        $collections = Collection::where('status', true)->latest('id')->get();

        // Hridoy
        $homepage_category_products = Category::with(['products.brand', 'products.reviews', 'products.colors'])->has('products')->where('is_shown_on_homepage', true)->where('status', true)->take(12)->get();
        $video = HomepageVideo::where('status', 1)->latest()->first();

        // var_dump($productIds); var_dump($products);var_dump($randomProducts);var_dump($unproducts); exit();
        return view('frontend.index', compact(
            'sliders',
            'unproducts',
            'sliders_f',
            // 'categories', # Commented by Hridoy
            'collections',
            'shops',
            'products',
            'video',
            'randomProducts',
            'campaigns_product',

            'homepage_category_products',
            'banners', // Pass banners
            'popBanner', // Pass popup banner
            'pop' // Pass popup slider

        ));
    }

    public function categories_all(Request $request)
    {

        $categories = Category::where('status', true)->latest('id')->get(['name', 'slug', 'cover_photo']);
        $collections = Collection::where('status', true)->latest('id')->get();

        return view('frontend.categories_all', compact(
            'categories',
            'collections',
        ));
    }

    public function superCat(Request $request)
    {

        $data = View::make('components.hero-category')->render();

        return json_encode($data);
    }

    public function subCat(Request $request)
    {
        $categories = Category::query()
            ->with(['sub_categories.miniCategory.extraCategory'])
            ->where('status', 1)
            ->orderBy('pos', 'asc')
            ->get();

        $data = View::make('components.superCategoryComponent', compact('categories'))->render();

        return json_encode($data);
    }

    public function sheba()
    {
        return view('frontend.sheba');
    }

    public function adminLogin()
    {
        return view('auth.admin-login');
    }

    public function track_form()
    {
        return view('frontend.track');
    }

    public function tracking(Request $request)
    {
        $this->validate($request, [
            'invoice' => 'required|string|max:255',
        ]);
        if ($request->pt) {
            $invoice = $request->invoice;
        } else {
            $invoice = '#'.$request->invoice;
        }

        $order = Order::where('user_id', auth()->id())->where('invoice', $invoice)->first();

        return view('frontend.track', compact('order'));
    }

    public function saveToken(Request $request)
    {
        $exits = DeviceId::where('device_id', $request->token)->where('user_id', auth()->id())->first();
        if (empty($exits)) {
            DeviceId::create([
                'user_id' => auth()->id(),
                'device_id' => $request->token,
            ]);
        }
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
        ]);

        $firebaseToken = DeviceId::pluck('device_id')->all();

        $SERVER_API_KEY = 'AAAA9Ek9F7U:APA91bEtCumEi8v_NmoBW6rQbm48iVNB4ctTguXS5G33Mj1FEmX48zlNYEHLWO3d6WfLkPD3ByKZQPrlJVl0swAd1ZxFWPMHWOdPWXD30sGCOvu_xIV7nTW9PC6cGiL6n3FOBHl1bavE';
        $data = [
            'registration_ids' => $firebaseToken,
            'notification' => [
                'title' => $request->title,
                'body' => $request->body,
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

        return back()->with('message', 'Notification sent.');
    }
}
