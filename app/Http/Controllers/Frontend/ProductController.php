<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Helper\Sorting;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\CampaingProduct;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Comment;
use App\Models\ExtraMiniCategory;
use App\Models\miniCategory;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use View;

class ProductController extends Controller
{
    // show products by category - Updaed by Hridoy
    // public function showProductByCategory($slug, Request $request)
    // {
    //     $i=1;
    //     if ($request->ajax()) {
    //         $skip=$request->skip/2;
    //     }else{
    //         $skip=0;
    //     }

    //     $category = Category::with(['products' => function($query) use ($skip) {
    //         return $query->where('status', true)->latest('id')->take(15)->skip($skip);
    //     }])
    //     ->where('slug', $slug)
    //     ->where('status', true)
    //     ->firstOrFail();

    //         $products=$category;
    //         $data = '';
    //         $data2 = '';

    //     if ($request->ajax()) {
    //         if($category->products->count() > 0){
    //         foreach ($category->products as $product) {
    //             $data .= View::make("components.product-grid-view")
    //             ->with("product", $product)
    //             ->render();
    //             $data2 .= View::make("components.product-list-view")
    //             ->with("product", $product)
    //             ->render();
    //         }}
    //         return json_encode(array($data, $data2));;
    //     }
    //     return view('frontend.category-product', compact('category','slug'));
    // }

    public function showProductByCategory($slug, Request $request)
    {
        $category = Category::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $skip = $request->ajax() ? ($request->skip ?? 0) : 0;

        $products = $category->products()
            ->with(['reviews', 'brand'])
            ->where('status', true)
            ->latest('id')
            ->skip($skip)
            ->take(12)
            ->get();

        if ($request->ajax()) {
            $grid = '';
            $list = '';
            foreach ($products as $product) {
                $grid .= View::make('components.product-grid-view')->with('product', $product)->render();
                $list .= View::make('components.product-list-view')->with('product', $product)->render();
            }

            return response()->json([
                'grid' => $grid,
                'list' => $list,
                'count' => $products->count(),
            ]);
        }

        return view('frontend.category-product', compact('category', 'slug'));
    }

    public function showProductByBrand($slug, Request $request)
    {
        $i = 1;
        if ($request->ajax()) {
            $skip = $request->skip / 2;
        } else {
            $skip = 0;
        }
        $brand = Brand::where('slug', $slug)->first();
        $products = Product::where('status', '1')->where('brand_id', $brand->id)
            ->skip($skip)
            ->take(16)->get();

        $data = '';
        $data2 = '';

        if ($request->ajax()) {
            if ($products->count() > 0) {
                foreach ($products as $product) {
                    $data .= View::make('components.product-grid-view')
                        ->with('product', $product)
                        ->render();
                    $data2 .= View::make('components.product-list-view')
                        ->with('product', $product)
                        ->render();
                }
            }

            return json_encode([$data, $data2]);
        }

        return view('frontend.product', compact('slug', 'products'));
    }

    public function showProductByAuthor($slug, Request $request)
    {
        $i = 1;
        if ($request->ajax()) {
            $skip = $request->skip / 2;
        } else {
            $skip = 0;
        }
        $products = Product::where('status', '1')->where('author_id', $slug)
            ->skip($skip)
            ->take(16)->get();

        $data = '';
        $data2 = '';

        if ($request->ajax()) {
            if ($products->count() > 0) {
                foreach ($products as $product) {
                    $data .= View::make('components.product-grid-view')
                        ->with('product', $product)
                        ->render();
                    $data2 .= View::make('components.product-list-view')
                        ->with('product', $product)
                        ->render();
                }
            }

            return json_encode([$data, $data2]);
        }

        return view('frontend.product', compact('slug', 'products'));
    }

    // show products by sub category
    public function showProductBySubCategory($slug, Request $request)
    {
        $i = 1;
        if ($request->ajax()) {
            $skip = $request->skip / 2;
        } else {
            $skip = 0;
        }
        $data = '';
        $data2 = '';
        $subCategory = SubCategory::with(['products' => function ($query) use ($skip) {
            return $query->where('status', true)->latest('id')->take(16)->skip($skip);
        }])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
        if ($request->ajax()) {
            if ($subCategory->products->count() > 0) {
                foreach ($subCategory->products as $product) {
                    $data .= View::make('components.product-grid-view')
                        ->with('product', $product)
                        ->render();
                    $data2 .= View::make('components.product-list-view')
                        ->with('product', $product)
                        ->render();
                }
            }

            return json_encode([$data, $data2]);
        }
        $type = '0';

        return view('frontend.sub-category-product', compact('subCategory', 'slug', 'type'));
    }

    public function showProductByMiniCategory($slug, Request $request)
    {
        $type = '1';
        $i = 1;
        if ($request->ajax()) {
            $skip = $request->skip / 2;
        } else {
            $skip = 0;
        }
        $data = '';
        $data2 = '';
        $subCategory = miniCategory::with(['products' => function ($query) use ($skip) {
            return $query->where('status', true)->latest('id')->take(16)->skip($skip);
        }])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
        if ($request->ajax()) {
            if ($subCategory->products->count() > 0) {
                foreach ($subCategory->products as $product) {
                    $data .= View::make('components.product-grid-view')
                        ->with('product', $product)
                        ->render();
                    $data2 .= View::make('components.product-list-view')
                        ->with('product', $product)
                        ->render();
                }
            }

            return json_encode([$data, $data2]);
        }

        return view('frontend.sub-category-product', compact('subCategory', 'slug', 'type'));
    }

    public function showProductByextraCategory($slug, Request $request)
    {
        $type = '2';
        $i = 1;
        if ($request->ajax()) {
            $skip = $request->skip / 2;
        } else {
            $skip = 0;
        }
        $data = '';
        $data2 = '';
        $subCategory = ExtraMiniCategory::with(['products' => function ($query) use ($skip) {
            return $query->where('status', true)->latest('id')->take(16)->skip($skip);
        }])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
        if ($request->ajax()) {
            if ($subCategory->products->count() > 0) {
                foreach ($subCategory->products as $product) {
                    $data .= View::make('components.product-grid-view')
                        ->with('product', $product)
                        ->render();
                    $data2 .= View::make('components.product-list-view')
                        ->with('product', $product)
                        ->render();
                }
            }

            return json_encode([$data, $data2]);
        }

        return view('frontend.sub-category-product', compact('subCategory', 'type', 'slug'));
    }

    /**
     * show products by collection
     *
     * @param  mixed $slug
     * @return void
     */
    public function showProductByCollection($slug, Request $request)
    {
        $i = 1;
        if ($request->ajax()) {
            $skip = $request->skip / 2;
        } else {
            $skip = 0;
        }

        $collection = Collection::where('slug', $slug)->where('status', true)->firstOrFail();
        $categoryIds = $collection->categories->pluck('id');
        $productIds = DB::table('category_product')->whereIn('category_id', $categoryIds)->get()->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->where('status', true)->latest('id')->take(4)->skip($skip)->get();
        $data = '';
        $data2 = '';
        if ($request->ajax()) {
            if ($products->count() > 0) {
                foreach ($products as $product) {
                    $data .= View::make('components.product-grid-view')
                        ->with('product', $product)
                        ->render();
                    $data2 .= View::make('components.product-list-view')
                        ->with('product', $product)
                        ->render();
                }
            }

            return json_encode([$data, $data2]);
        }

        return view('frontend.collection-product', compact('products', 'collection'));
    }

    // Commented by Hridoy
    // public function showAllProduct(Request $request)
    // {
    //     $i=1;
    //     if ($request->ajax()) {
    //         $skip=$request->skip/2;
    //     }else{
    //         $skip=0;
    //     }
    //     $products =  Product::where('status','1')
    //     ->skip($skip)->orderBy('id', 'desc')
    //     ->take(16)->get();

    //     $data = '';
    //     $data2 = '';
    //     if ($request->ajax()) {
    //         if($products->count() > 0){
    //         foreach ($products as $product) {
    //             $data .= View::make("components.product-grid-view")
    //             ->with("product", $product)
    //             ->render();
    //             $data2 .= View::make("components.product-list-view")
    //             ->with("product", $product)
    //             ->render();
    //         }}
    //         return json_encode(array($data, $data2));;
    //     }
    //     return view('frontend.product', compact('products'));
    // }

    // Added by Hridoy
    public function showAllProduct(Request $request)
    {
        $i = 1;
        if ($request->ajax()) {
            $skip = $request->skip / 2;
        } else {
            $skip = 0;
        }

        // Initialize the query builder for products
        $query = Product::where('status', '1');

        // Check if there is a search term and modify the query accordingly
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%");
            });
        }

        // Retrieve products with pagination
        $products = $query->with(['reviews', 'brand'])->skip($skip)->orderBy('id', 'desc')->take(12)->get();

        $data = '';
        $data2 = '';

        if ($request->ajax()) {
            if ($products->count() > 0) {
                foreach ($products as $product) {
                    $data .= View::make('components.product-grid-view')
                        ->with('product', $product)
                        ->render();
                    $data2 .= View::make('components.product-list-view')
                        ->with('product', $product)
                        ->render();
                }
            }

            return json_encode([$data, $data2]);
        }

        return view('frontend.product', compact('products'));
    }

    public function productSearch(Request $request)
    {
        $i = 1;

        if ($request->ajax()) {
            $skip = $request->skip / 2;
        } else {
            $skip = 0;
        }

        $data = '';
        $data2 = '';

        // Use Scout
        $products = Product::search($request->search)
            ->when(true, function ($query) {
                $query->where('status', true);
            })
            ->take(16)
            ->get();

        if ($request->ajax()) {
            if ($products->count() > 0) {
                foreach ($products as $product) {
                    $data .= View::make('components.product-grid-view')
                        ->with('product', $product)
                        ->render();
                    $data2 .= View::make('components.product-list-view')
                        ->with('product', $product)
                        ->render();
                }
            }

            return response()->json([$data, $data2]);
        }

        $key = $request->keyword;

        return view('frontend.search-product', compact('products', 'key'));
    }

    // Updated by Hridoy
    public function advanceSearch(Request $request)
    {
        // dd($request->all());
        // $products = Product::whereLike(['title', 'full_description', 'tags.name'], $request->key) ->filter('status', true)->latest('id')->paginate(20);

        // $data = '';
        // if ($products->count() > 0) {
        //     foreach ($products as $product) {
        //         $data .= '<style>.pin:hover { background: gainsboro !important; }</style>
        //                   <div class="product col-lg-12" style="height: initial;">
        //                       <div class="product-wrapper list-comp">
        //                           <a href="' . route('product.details', $product->slug) . '">
        //                               <div class="pin" style="display: flex; margin-bottom: 0; background: white; padding: 5px; border-bottom: 1px solid gainsboro;">
        //                                   <div class="thumbnail">
        //                                       <img style="object-fit: fill; width: 60px; height: 60px; max-width: 100px;" src="' . asset('uploads/product/' . $product->image) . '" alt="Product Image">
        //                                   </div>
        //                                   <div class="details">
        //                                       <h5 style="font-size: 15px">' . $product->title . '</h5>
        //                                       <h5 style="font-size: 15px">Price : ' . ($product->discount_price ? '<del style="color: gray;">Tk.' . $product->regular_price . '</del>' . ' Tk.' . $product->discount_price : $product->regular_price) . '</h5>
        //                                   </div>
        //                               </div>
        //                           </a>
        //                       </div>
        //                   </div>';
        //     }
        // }
        // return json_encode($data);

        // if $request->key is empty then return empty array
        if (empty($request->key)) {
            return response()->json([
                'products' => [],
            ]);
        }

        // instead of pagination i will only get 20 products
        // $products = Product::whereLike(['title', 'full_description', 'tags.name'], $request->key)->filter('status', true)->latest('id')->take(20)->get();
        $products = Product::search($request->key)
            ->where('status', true)
            ->take(20)
            ->get();

        // return json response
        return response()->json([
            'products' => $products,
        ]);
    }

    // show product details
    public function productDetails($slug)
    {
        $product = Product::query()
            ->with('comments', 'reviews', 'attributes_values.attributes')
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $product->increment('reach');

        $colors_product = DB::table('color_product')
            ->select('*')
            ->join('colors', 'colors.id', '=', 'color_product.color_id')
            ->where('color_product.product_id', $product->id)
            ->get();

        $attributes = Attribute::all();

        $attributeGroups = $product->attributes_values
            ->groupBy('attribute.id');

        $relatedProducts = Product::query()
            ->whereHas('categories', function ($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->whereNot('id', $product->id)
            ->where('status', true)
            ->inRandomOrder()
            ->with('reviews')
            ->take(5)
            ->get();

        $categoryBestProducts = Product::query()
            ->whereHas('categories', function ($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->whereNot('id', $product->id)
            ->where('status', true)
            ->inRandomOrder()
            ->with('reviews')
            ->take(18)
            ->get();

        return view('frontend.single-product', compact('product', 'colors_product', 'attributes', 'attributeGroups', 'relatedProducts', 'categoryBestProducts'));
    }

    // show product details
    public function productDetails1($slug)
    {
        $campaigns_product = CampaingProduct::find($slug);
        $product = Product::with('comments', 'reviews')->where('id', $campaigns_product->product_id)->where('status', true)->firstOrFail();
        $product->reach += 1;
        $product->update();
        $colors_product = DB::table('color_product')
            ->select('*')
            ->join('colors', 'colors.id', '=', 'color_product.color_id')
            ->where('color_product.product_id', $product->id)
            ->get();
        $attributes = Attribute::all();

        return view('frontend.single-product', compact('product', 'colors_product', 'attributes', 'campaigns_product'));
    }

    // add to cart product
    public function addToCart(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'qty' => 'required|integer',
            'color' => 'required|string|max:20',
            'size' => 'required|string|max:20',
        ]);

        $user = auth()->user();
        if ($user->role_id === 2 || $user->role_id === 3) {

            return response()->json([
                'alert' => 'success',
                'message' => 'Product add to cart successfully',
            ]);
        }

        return response()->json([
            'alert' => 'error',
            'message' => 'Please login your account!!',
        ]);

    }

    /**
     * comment by product
     *
     * @param  mixed $slug
     * @return void
     */
    public function comment(Request $request, $slug)
    {
        $this->validate($request, [
            'comment' => 'required|string',
        ]);

        $product = Product::where('slug', $slug)->first();

        Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'body' => $request->comment,
        ]);

        notify()->success('Your comment successful', 'Success');

        return back();
    }

    /**
     * product comment reply
     *
     * @param  mixed $request
     * @param  mixed $slug
     * @param  mixed $id
     * @return void
     */
    public function reply(Request $request, $slug, $id)
    {
        $this->validate($request, [
            'reply' => 'required|string',
        ]);

        $product = Product::where('slug', $slug)->first();

        Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'parent_id' => $id,
            'body' => $request->reply,
        ]);

        notify()->success('Your reply successful', 'Success');

        return back();
    }

    /**
     * product filtering by requested data
     *
     * @param  mixed $request
     * @return void
     */
    public function productFilter(Request $request)
    {
        $unr = $request->unr;
        $min = 0;
        $max = 9999999999999999999999999;

        $products = Product::query()
            ->where('status', '1');

        if (isset($request->amount)) {
            $currency_code = setting('CURRENCY_CODE_MIN') ?: 'Tk';
            $amounts = explode('-', str_replace($currency_code, '', (string) $request->amount));
            $products = $products->whereBetween('regular_price', [
                (int) trim($amounts[0]),
                (int) trim($amounts[1]),
            ]);
        }

        if (! empty($request->extra_category)) {
            $sub_category = ExtraMiniCategory::where('slug', $request->extra_category)->pluck('id');
            $sub_category_product_ids = DB::table('extra_mini_category_products')->where('extra_mini_category_id', $sub_category)->get()->pluck('product_id');
            $products = $products->whereIn('id', $sub_category_product_ids);
        } elseif (! empty($request->mini_category)) {
            $sub_category = miniCategory::where('slug', $request->mini_category)->pluck('id');
            $sub_category_product_ids = DB::table('mini_category_product')->where('mini_category_id', $sub_category)->get()->pluck('product_id');
            $products = $products->whereIn('id', $sub_category_product_ids);
        } elseif (! empty($request->sub_category)) {
            $sub_category = SubCategory::where('slug', $request->sub_category)->pluck('id');
            $sub_category_product_ids = DB::table('product_sub_category')->whereIn('sub_category_id', $sub_category)->get()->pluck('product_id');
            $products = $products->whereIn('id', $sub_category_product_ids);
        } elseif (! empty($request->category)) {
            $category = Category::where('slug', $request->category)->pluck('id');
            $category_product_ids = DB::table('category_product')->where('category_id', $category)->get()->pluck('product_id');
            $products = $products->whereIn('id', $category_product_ids);
        }

        // if ($request->collection != '') {
        //     $collection  = Collection::where('slug', $request->collection)->first();
        //     $categoryIds = $collection->categories->pluck('id');
        //     $collection_product_ids  = DB::table('category_product')->whereIn('category_id', $categoryIds)->get()->pluck('product_id');
        //     $products = $products->whereIn('id', $collection_product_ids);
        // }

        // if ($request->rating != '') {
        //     $rating_product_ids = DB::table('reviews')->where('rating', $request->rating)->get()->pluck('product_id');
        //     $products = $products->whereIn('id', $rating_product_ids);
        // }

        // if ($request->colors != '') {
        //     $colors = Color::whereIn('slug', $request->colors)->pluck('id');
        //     $color_product_ids = DB::table('color_product')->whereIn('color_id', $colors)->get()->pluck('product_id');
        //     $products = $products->whereIn('id', $color_product_ids);
        // }

        // $s_attri = $request->input('attri');
        // if (!empty($s_attri)) {
        //     $brands = AttributeValue::whereIn('slug', $s_attri)->pluck('id');
        //     $brandProductIds = DB::table('attribute_product')->whereIn('attribute_value_id', $brands)->pluck('product_id');
        //     if ($brandProductIds->count() > 0) $products = $products->whereIn('id', $brandProductIds->toArray());
        //     else $products = $products->where('id', 0);
        // }

        $brands = $request->input('brands');
        if (! empty($brands)) {
            $brandIds = Brand::whereIn('slug', $brands)->pluck('id');
            if ($brandIds->count() > 0) {
                $products = $products->whereIn('brand_id', $brandIds);
            }
        }

        $sort = new Sorting();
        $value = $sort->getValue($request->sort);
        if ($value === $sort->oldToNew) {
            $products = $products->orderBy('id', 'asc')->get();
        } elseif ($value === $sort->best) {
            $products = $products->orderBy('reach', 'desc')->get();
        } elseif ($value === $sort->highToLow) {
            $products = $products->orderByRaw('CONVERT(regular_price, SIGNED) desc')->get();
        } elseif ($value === $sort->lowToHigh) {
            $products = $products->orderByRaw('CONVERT(regular_price, SIGNED) asc')->get();
        } elseif ($value === $sort->dhighToLow) {
            $products = $products->orderByRaw('CONVERT(discount_price, SIGNED) desc')->get();
        } elseif ($value === $sort->dlowToHigh) {
            $products = $products->orderByRaw('CONVERT(discount_price, SIGNED) asc')->get();
        } else {
            $products = $products->orderBy('id', 'desc')->get();
        }

        return view('frontend.filter-product', compact('products', 'request', 'min', 'max', 'unr'));
    }

    /**
     * show product details to cart
     *
     * @param  mixed $slug
     * @return void
     */
    public function productInfo($slug)
    {
        $product = Product::with([
            'colors' => function ($query) {
                $query->select('code', 'name', 'slug');
            },
            'sizes' => function ($query) {
                $query->select('name');
            },
        ])->where('slug', $slug)->firstOrFail(['id', 'slug', 'regular_price', 'discount_price', 'image']);
        $attributes = Attribute::all();
        $attrs = '';
        $values = '';
        foreach ($attributes as $attribute) {
            $attribute_prouct = DB::table('attribute_product')
                ->select('*')
                ->join('attribute_values', 'attribute_values.id', '=', 'attribute_product.attribute_value_id')
                ->addselect('attribute_values.name as vName')
                ->addselect('attribute_values.id as vid')
                ->join('attributes', 'attributes.id', '=', 'attribute_values.attributes_id')
                ->where('attribute_product.product_id', $product->id)
                ->where('attributes.id', $attribute->id)
                ->get();
            if ($attribute_prouct->count() > 0) {

                $attrs .= '<div class="col-12 pl-0 mb-2">
                                <p><strong>Select '.$attribute->name.':</strong></p>
                            </div>';
                foreach ($attribute_prouct as $attr) {
                    $attrs .= '<div class="form-check col-2 col-sm-2"><input id="'.$attr->vName.'" class="form-check-input get_attri_price pp'.$attribute->slug.'" type="radio" name="'.$attribute->slug.'" value="'.$attr->vid.'"><label class="form-check-label" for="'.$attr->vName.'">'.$attr->vName.'</label></div>';
                    $attrs .=
                        "<script>
                        $(document).on('click', '.pp".$attribute->slug."', function(e) {
                            $('input#".$attribute->slug."').val(this.value);
                        })
                    </script>";
                }
                foreach ($attribute_prouct as $vattr) {
                    $vid = $vattr->vid;
                }
                $values .= '<input type="hidden" name="'.$attribute->slug.'" id="'.$attribute->slug.'" value="blank">';
            }
        }

        return response()->json([$product, $attrs, $values]);
    }

    public function productInfo1($id)
    {
        $camp = CampaingProduct::find($id);
        $slug = $camp->product_id;
        $product = Product::with([
            'colors' => function ($query) {
                $query->select('code', 'name', 'slug');
            },
            'sizes' => function ($query) {
                $query->select('name');
            },
        ])->where('id', $slug)->firstOrFail(['id', 'slug', 'regular_price', 'discount_price', 'image']);
        $attributes = Attribute::all();
        $attrs = '';
        $values = '';
        foreach ($attributes as $attribute) {
            $attribute_prouct = DB::table('attribute_product')
                ->select('*')
                ->join('attribute_values', 'attribute_values.id', '=', 'attribute_product.attribute_value_id')
                ->addselect('attribute_values.name as vName')
                ->addselect('attribute_values.id as vid')
                ->join('attributes', 'attributes.id', '=', 'attribute_values.attributes_id')
                ->where('attribute_product.product_id', $product->id)
                ->where('attributes.id', $attribute->id)
                ->get();
            if ($attribute_prouct->count() > 0) {

                $attrs .= '<div class="col-12 pl-0 mb-2">
                                <p><strong>Select '.$attribute->name.':</strong></p>
                            </div>';
                foreach ($attribute_prouct as $attr) {
                    $attrs .= '<div class="form-check col-2 col-sm-2"><input id="'.$attr->vName.'" class="form-check-input get_attri_price" type="radio" name="'.$attribute->slug.'" value="'.$attr->vid.'"><label class="form-check-label" for="'.$attr->vName.'">'.$attr->vName.'</label></div>';
                    $attrs .=
                        "<script>
                        $(document).on('click', '.pp".$attribute->slug."', function(e) {
                            $('input#".$attribute->slug."').val(this.value);
                        })
                    </script>";
                }
                foreach ($attribute_prouct as $vattr) {
                    $vid = $vattr->vid;
                }
                $values .= '<input type="hidden" name="'.$attribute->slug.'" id="'.$attribute->slug.'" value="blank">';
            }
        }

        return response()->json([$product, $attrs, $values, $camp]);
    }

    public function getAttrPrice(Request $request)
    {
        $attributes = Attribute::all();
        $price = 0;
        foreach ($attributes as $attribute) {
            $attribute_prouct = DB::table('attribute_product')
                ->select('*')
                ->join('attribute_values', 'attribute_values.id', '=', 'attribute_product.attribute_value_id')
                ->addselect('attribute_values.name as vName')
                ->addselect('attribute_product.id as vid')
                ->join('attributes', 'attributes.id', '=', 'attribute_values.attributes_id')
                ->where('attribute_product.product_id', $request->id)
                ->where('attributes.id', $attribute->id)
                ->get();
            if ($attribute_prouct->count() > 0) {
                $slug = $attribute->slug;
                $id = $request->$slug;
                if ($id > 0) {
                    $attr_pro = DB::table('attribute_product')->where('product_id', $request->id)->where('attribute_value_id', $id)->first();
                    $price += $attr_pro->price;
                }
            }
        }

        $c = Color::where('slug', $request->color)->first();
        if (! empty($c)) {
            $color = DB::table('color_product')->where('product_id', $request->id)->where('color_id', $c->id)->first();
        }
        $product = Product::find($request->id);
        if (isset($request->camp)) {
            $camp = CampaingProduct::find($request->camp);
            $op = $camp->price;
        } elseif (empty($product->discount_price)) {
            $op = $product->regular_price;
        } else {
            $op = $product->discount_price;
        }
        if (! empty($color)) {
            $price += $op + $color->price;
        } else {
            $price += $op;
        }

        return response()->json($price);
    }

    public function allBrand()
    {
        $brands = Brand::where('status', '1')->get();

        return view('frontend.brands', compact('brands'));
    }
    
    

        public function homeAsCategory(Request $request)
        {
            // blade compatibility
            $request->merge([
                'category' => null,
            ]);
        

            $products = Product::where('status', 1)
                ->latest('id')
                ->paginate(20); 
        
            $min = 0;
            $max = 999999999;
            $unr = url('/');
        
            return view('frontend.filter-product', compact(
                'products',
                'request',
                'min',
                'max',
                'unr'
            ));
        }

// <div class="mt-4">
//     {{ $products->withQueryString()->links() }}
// </div>

}
