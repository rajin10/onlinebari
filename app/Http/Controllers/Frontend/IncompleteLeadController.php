<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IncompleteLead;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class IncompleteLeadController extends Controller
{
    


 /**
     * Display a listing of incomplete leads
     */
    public function index(Request $request)
    {
        $query = IncompleteLead::query()->with('user');

        // Search by keyword (name or phone)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        // Filter by conversion status
        if ($request->filled('converted')) {
            $query->where('converted', $request->converted);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Order by last activity (most recent first)
        $query->orderBy('last_activity', 'desc');

        // Paginate results
        $leads = $query->paginate(50);

        // Calculate statistics
        $stats = [
            'total' => IncompleteLead::count(),
            'active' => IncompleteLead::where('converted', false)->count(),
            'converted' => IncompleteLead::where('converted', true)->count(),
            'conversion_rate' => 0
        ];

        if ($stats['total'] > 0) {
            $stats['conversion_rate'] = ($stats['converted'] / $stats['total']) * 100;
        }
         return view('admin.e-commerce.order.incomplete_order', compact('leads', 'stats'));
      
    }

    /**
     * Export incomplete leads to CSV
     */
    public function export(Request $request)
    {
        $query = IncompleteLead::query();

        // Apply same filters as index
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('converted')) {
            $query->where('converted', $request->converted);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $leads = $query->orderBy('created_at', 'desc')->get();

        $filename = 'incomplete_leads_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($leads) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Name',
                'Phone',
                'Email',
                'Address',
                'Total Items',
                'Subtotal',
                'Status',
                'IP Address',
                'Page URL',
                'Created At',
                'Last Activity'
            ]);

            // CSV Data
            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->id,
                    $lead->name ?? 'N/A',
                    $lead->phone ?? 'N/A',
                    $lead->email ?? 'N/A',
                    $lead->address ?? 'N/A',
                    $lead->total_items,
                    number_format($lead->subtotal, 2),
                    $lead->converted ? 'Converted' : 'Pending',
                    $lead->ip_address ?? 'N/A',
                    $lead->page_url ?? 'N/A',
                    $lead->created_at ? $lead->created_at->format('Y-m-d H:i:s') : 'N/A',
                    $lead->last_activity ? $lead->last_activity->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Delete an incomplete lead
     */
    public function destroy($id)
    {
        try {
            $lead = IncompleteLead::findOrFail($id);
            $lead->delete();

            notify()->success('Lead deleted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Error deleting lead: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $lastWeek = now()->subWeek()->startOfWeek();

        return [
            'today' => [
                'total' => IncompleteLead::whereDate('created_at', $today)->count(),
                'converted' => IncompleteLead::whereDate('created_at', $today)->where('converted', true)->count(),
            ],
            'yesterday' => [
                'total' => IncompleteLead::whereDate('created_at', $yesterday)->count(),
                'converted' => IncompleteLead::whereDate('created_at', $yesterday)->where('converted', true)->count(),
            ],
            'this_week' => [
                'total' => IncompleteLead::where('created_at', '>=', $thisWeek)->count(),
                'converted' => IncompleteLead::where('created_at', '>=', $thisWeek)->where('converted', true)->count(),
            ],
            'last_week' => [
                'total' => IncompleteLead::whereBetween('created_at', [$lastWeek, $lastWeek->copy()->endOfWeek()])->count(),
                'converted' => IncompleteLead::whereBetween('created_at', [$lastWeek, $lastWeek->copy()->endOfWeek()])
                    ->where('converted', true)->count(),
            ],
            'active_now' => IncompleteLead::where('converted', false)
                ->where('last_activity', '>', now()->subMinutes(30))
                ->count(),
        ];
    }
    
    public function store(Request $request)
    {
        try {
           
            Log::info('Incomplete Lead Store Request:', [
                'name' => $request->name,
                'phone' => $request->phone,
                'page_type' => $request->page_type,
                'session_id' => session()->getId(),
                'has_cart_items' => !empty($request->cart_items)
            ]);

            // Minimum requirement check
            if (empty($request->name) && empty($request->phone)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Name or phone is required'
                ], 400);
            }

            // Session ID generate 
            $sessionId = session()->getId();
            if (empty($sessionId)) {
                session()->regenerate();
                $sessionId = session()->getId();
            }

            // Determine page type and prepare cart data accordingly
            $pageType = $request->page_type ?? 'buy_now';
            
            if ($pageType === 'cart_checkout') {
                // Cart checkout page data
                $cartData = $this->prepareCartCheckoutData($request);
            } else {
                // Buy now page data
                $cartData = $this->prepareBuyNowData($request);
            }

            // Update or Create incomplete lead
            $lead = IncompleteLead::updateOrCreate(
                ['session_id' => $sessionId],
                [
                    'user_id' => auth()->id(),
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'page_url' => $request->headers->get('referer') ?? url()->current(),
                    'ip_address' => $request->ip(),
                    'cart_data' => $cartData['items'],
                    'subtotal' => $cartData['subtotal'],
                    'total_items' => $cartData['total_items'],
                    'last_activity' => now(),
                    'converted' => false
                ]
            );

            Log::info('Incomplete Lead Saved Successfully:', [
                'lead_id' => $lead->id,
                'session_id' => $sessionId,
                'page_type' => $pageType,
                'items_count' => count($cartData['items'])
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Lead saved successfully',
                'lead_id' => $lead->id
            ]);

        } catch (\Exception $e) {
            Log::error('Incomplete Lead Store Error:', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Error saving lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cart checkout data prepare করুন
     */
    private function prepareCartCheckoutData(Request $request)
    {
        $cartItems = [];
        $subtotal = floatval($request->cart_subtotal ?? 0);
        $totalItems = intval($request->cart_total_items ?? 0);

        // Frontend থেকে পাঠানো cart items process করুন
        if ($request->has('cart_items') && is_array($request->cart_items)) {
            foreach ($request->cart_items as $item) {
                $cartItems[] = [
                    'product_name' => $item['product_name'] ?? '',
                    'product_slug' => $item['product_slug'] ?? '',
                    'product_url' => $item['product_url'] ?? '',
                    'image' => $item['image'] ?? '',
                    'total' => floatval($item['total'] ?? 0),
                    'added_at' => $item['added_at'] ?? now()->toDateTimeString()
                ];
            }
        }

        // যদি frontend data না থাকে তাহলে session cart থেকে নিন
        if (empty($cartItems)) {
            try {
                $cart = \Cart::content();
                
                foreach ($cart as $item) {
                    $product = Product::find($item->id);
                    
                    if ($product) {
                        // Price calculate করুন
                        $price = $item->price;
                        if ($item->qty >= 6 && $product->whole_price > 0) {
                            $price = $product->whole_price;
                        }
                        
                        $itemTotal = $price * $item->qty;
                        $subtotal += $itemTotal;
                        $totalItems += $item->qty;

                        $cartItems[] = [
                            'product_id' => $product->id,
                            'product_name' => $item->name,
                            'product_slug' => $product->slug,
                            'image' => $item->options->image ?? $product->image,
                            'price' => $price,
                            'qty' => $item->qty,
                            'total' => $itemTotal,
                            'size' => $item->options->attributes ?? null,
                            'color' => $item->options->color ?? null,
                            'added_at' => now()->toDateTimeString()
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::error('Cart Data Collection Error:', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'items' => $cartItems,
            'subtotal' => $subtotal,
            'total_items' => $totalItems
        ];
    }

    /**
     * Buy Now page data prepare করুন
     */
    private function prepareBuyNowData(Request $request)
    {
        $cartItems = [];
        $subtotal = 0;
        $totalItems = 0;

        if ($request->has('id') && $request->has('qty')) {
            try {
                $product = Product::find($request->id);
                
                if ($product) {
                    $price = $request->dynamic_price ?? $product->price;
                    
                    // Wholesale price check
                    if ($request->qty >= 6 && $product->whole_price > 0) {
                        $price = $product->whole_price;
                    }
                    
                    $itemTotal = $price * $request->qty;
                    $subtotal += $itemTotal;
                    $totalItems += $request->qty;

                    $cartItems[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->title,
                        'product_slug' => $product->slug,
                        'image' => $product->image,
                        'price' => $price,
                        'qty' => $request->qty,
                        'total' => $itemTotal,
                        'size' => $request->size ?? null,
                        'color' => $request->color ?? null,
                        'added_at' => now()->toDateTimeString()
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Buy Now Data Prepare Error:', [
                    'error' => $e->getMessage(),
                    'product_id' => $request->id
                ]);
            }
        }

        return [
            'items' => $cartItems,
            'subtotal' => $subtotal,
            'total_items' => $totalItems
        ];
    }

    /**
     * Lead কে converted হিসেবে mark করুন যখন order complete হয়
     */
    public function markAsConverted($sessionId = null, $userId = null)
    {
        try {
            $query = IncompleteLead::query();
            
            if ($sessionId) {
                $query->where('session_id', $sessionId);
            } elseif ($userId) {
                $query->where('user_id', $userId);
            } else {
                $sessionId = session()->getId();
                $query->where('session_id', $sessionId);
            }
            
            $lead = $query->where('converted', false)->first();
            
            if ($lead) {
                $lead->update(['converted' => true]);
                
                Log::info('Lead Marked as Converted:', [
                    'lead_id' => $lead->id,
                    'session_id' => $lead->session_id,
                    'user_id' => $lead->user_id
                ]);
                
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error('Mark Converted Error:', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId,
                'user_id' => $userId
            ]);
            
            return false;
        }
    }
}