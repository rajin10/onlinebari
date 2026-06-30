<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    public function index()
    {
        $banners = DB::table('banners')->latest('id')->get();
        return view('admin.e-commerce.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.e-commerce.banner.form');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
            'url'   => 'nullable|url|string|max:255'
        ]);
        
        $image = $request->file('image');
        if ($image) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
            
            if (!file_exists('uploads/banner')) {
                mkdir('uploads/banner', 0777, true);
            }
            $image->move(public_path('uploads/banner'), $imageName);
        }

        Banner::create([
            'image'   => $imageName,
            'url'     => $request->url,
            'status'  => $request->filled('status'),
            'is_feature' => $request->filled('is_feature'),
            'is_pop'    => $request->filled('is_pop'),
            'is_sub'    => $request->filled('is_sub'),
        ]);

        notify()->success("Banner successfully added", "Added");
        return redirect()->to(routeHelper('banner'));
    }

    public function edit(Banner $banner)
    {
        return view('admin.e-commerce.banner.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $this->validate($request, [
            'image' => 'nullable|image|max:1024|mimes:jpg,jpeg,png,bmp,webp',
            'url'   => 'nullable|url|string|max:255'
        ]);
        
        $image = $request->file('image');
        if ($image) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
            
            if (file_exists('uploads/banner/'.$banner->image)) {
                unlink('uploads/banner/'.$banner->image);
            }

            if (!file_exists('uploads/banner')) {
                mkdir('uploads/banner', 0777, true);
            }
            $image->move(public_path('uploads/banner'), $imageName);
        } else {
            $imageName = $banner->image;
        }

        $banner->update([
            'image'   => $imageName,
            'url'     => $request->url,
            'status'  => $request->filled('status'),
            'is_feature' => $request->filled('is_feature'),
            'is_pop'    => $request->filled('is_pop'),
            'is_sub'    => $request->filled('is_sub'),
        ]);

        notify()->success("Banner successfully updated", "Updated");
        return redirect()->to(routeHelper('banner'));
    }

    public function destroy(Banner $banner)
    {
        if (file_exists('uploads/banner/'.$banner->image)) {
            unlink('uploads/banner/'.$banner->image);
        }
        $banner->delete();
        notify()->success("Banner successfully deleted", "Deleted");
        return back();
    }




    public function show(Banner $banner)
    {
        if ($banner->status) {
            $banner->status = false;
        } 
        else {
            $banner->status = true;
        }
        $banner->update();
        notify()->success("Slider status updated", "Update");
        return back();
    }
    
    
    
    
}
