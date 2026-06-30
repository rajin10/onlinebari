<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View ;
class ProductsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
       return view('admin.e-commerce.product.exports',['products'=>Product::all()]);
    }
}
