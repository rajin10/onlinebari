<form action="{{ route('product.filter') }}" method="GET" id="form">
    <div class="range">
        <ul class="dropdown-menu6">
            <li>
                <div id="slider-range"></div>
                <input type="text" name="amount" readonly id="amount"
                    style="border: 0; color: #ffffff; font-weight: normal;" />
            </li>
        </ul>
    </div>

    {{-- Use incoming $request if provided, otherwise fallback to global request() --}}
    @php
        $req = $request ?? request();
        // ensure $brands exists as a collection/array to avoid undefined variable errors
        $brands = $brands ?? collect();
    @endphp

    {{-- MINI CATEGORY --}}
    @if(!empty($req->mini_category))
        @php
            $data = DB::table('mini_categories')->where('slug', $req->mini_category)->first();
            $datan = $data ? DB::table('sub_categories')->where('id', $data->category_id)->first() : null;
            $cidn = optional($datan)->category_id;
        @endphp
    @endif

    {{-- EXTRA CATEGORY --}}
    @if(!empty($req->extra_category))
        @php
            $data = DB::table('extra_mini_categories')->where('slug', $req->extra_category)->first();
            $mini = $data ? DB::table('mini_categories')->where('id', $data->mini_category_id)->first() : null;
            $datas = $mini ? DB::table('sub_categories')->where('id', $mini->category_id)->first() : null;
            $cidn = optional($datas)->category_id;
        @endphp
    @endif

    {{-- SUB CATEGORY --}}
    @if(!empty($req->sub_category))
        @php
            $data = DB::table('sub_categories')->where('slug', $req->sub_category)->first();
            $cidn = optional($data)->category_id;
            $idn = optional($data)->id;
        @endphp
    @endif

    {{-- CATEGORY --}}
    @if(!empty($req->category))
        @php
            $data = DB::table('categories')->where('slug', $req->category)->first();
            $cidn = optional($data)->id;
        @endphp
    @endif

    {{-- COLLECTION --}}
    @if(!empty($req->collection))
        @php
            $data = DB::table('collections')->where('slug', $req->collection)->first();
        @endphp
    @endif

    {{-- NAME / VALUE based blocks (if the component is used with $name/$value context) --}}
    @php
        $name = $name ?? null;
        $value = $value ?? null;
    @endphp

    @if ($name == 'category')
        @php
            $data = DB::table('categories')->where('slug', $value)->first();
            $cidn = optional($data)->id;
        @endphp
    @endif

    @if ($name == 'sub_category')
        @php
            $data = DB::table('sub_categories')->where('slug', $value)->first();
            $cidn = optional($data)->category_id;
            $idn = optional($data)->id;
        @endphp
    @endif

    @if ($name == 'mini_category')
        @php
            $mini = DB::table('mini_categories')->where('slug', $value)->first();
            $data = $mini ? DB::table('sub_categories')->where('id', $mini->category_id)->first() : null;
            $cidn = optional($data)->category_id;
        @endphp
    @endif

    @if ($name == 'extra_category')
        @php
            $emini = DB::table('extra_mini_categories')->where('slug', $value)->first();
            $mini = $emini ? DB::table('mini_categories')->where('id', $emini->mini_category_id)->first() : null;
            $data = $mini ? DB::table('sub_categories')->where('id', $mini->category_id)->first() : null;
            $cidn = optional($data)->category_id;
        @endphp
    @endif

    {{-- ATTRIBUTE FILTERS (only if we resolved a category id) --}}
    @if(!empty($cidn))
        <style>
            .cck2 {
                border-radius: 5px;
                border: 1px solid black;
                text-align: center;
                padding: 2px 10px !important;
                margin-block: .4rem !important;
                position: relative;
                margin: 0;
                padding: 0;
            }

            .cck2 input {
                opacity: 0;
                position: absolute;
                cursor: pointer;
                text-align: center;
                z-index: 99999;
                width: 100%;
                height: 100%;
            }
            .cck2:hover{
                background: orange;
                border-color: orange;
            }
            .cck2.active {
                background: black;
                color: white;
            }
        </style>

        @foreach (App\Models\Attribute::where('category_id', $cidn)->get() as $attribute)
            @if ($attribute->values->count() > 0)
                <div class="left-side mb-3">
                    <p class=""
                        style="border-top: 1px solid gainsboro;margin-bottom: 10px;padding-top: 10px;font-weight: 700;">
                        {{ $attribute->name }}</p>
                    @foreach ($attribute->values as $avalue)
                        <li style="display:inline-block;">
                            <label
                                class="cck2 @isset($req->attri) @foreach ($req->attri as $req_brand) {{ $avalue->slug == $req_brand ? 'active' : '' }}  @endforeach @endisset"
                                for="{{ $avalue->slug }}">
                                <input type="checkbox" id="{{ $avalue->slug }}" name="attri[]" class="checked"
                                    value="{{ $avalue->slug }}" onchange="document.getElementById('form').submit()"
                                    @isset($req->attri) @foreach ($req->attri as $req_brand) {{ $avalue->slug == $req_brand ? 'checked' : '' }}  @endforeach @endisset>
                                {{ $avalue->name }}
                            </label>
                        </li>
                    @endforeach
                </div>
            @endif
        @endforeach
    @endif

    {{-- DISPLAY SELECTED CATEGORY / SUBCATEGORY / MINI etc (safe checks) --}}
    @if(!empty($data) || !empty($input_name) || !empty($name))
        @php
            // ensure input_name and input variables exist when built above
            $input_name = $input_name ?? null;
            $input = $input ?? null;
        @endphp
    @endif

    @if(!empty($input_name) || !empty($data))
        <div class="left-side mb-3">
            <p class=""
                style="border-top: 1px solid gainsboro;margin-bottom: 10px;padding-top: 10px;font-weight: 700;">
                {{ $input_name ?? '' }}</p>
            <ul>
                <li>
                    <input style="opacity:0" type="checkbox" name="{{ $input ?? 'category' }}" class="checked" id="dcd"
                        onchange="document.getElementById('form').submit()"
                        value="{{ optional($data)->slug }}" checked>
                    <span class="span">
                        @if (($input ?? '') == 'category')
                            {{ optional($data)->name }}
                        @endif

                        @if (($input ?? '') == 'sub_category')
                            @php($cs = App\Models\Category::where('id', $cidn)->first())
                            @if($cs)
                                <a href="{{ route('category.product', $cs->slug) }}">{{ $cs->name }}</a><i
                                    class="icon-down icofont icofont-simple-right"></i>
                            @endif
                            {{ optional($data)->name }}
                        @endif

                        @if (($input ?? '') == 'mini_category')
                            @php($cs = App\Models\Category::where('id', $cidn)->first())
                            @if(isset($datan) && $datan)
                                <a href="{{ route('category.product', optional($cs)->slug) }}">{{ optional($cs)->name }}</a><i
                                    class="icon-down icofont icofont-simple-right"></i>
                                <a href="{{ route('subCategory.product', $datan->slug) }}"> {{ $datan->name }}</a><i
                                    class="icon-down icofont icofont-simple-right"></i>{{ optional($data)->name }}
                            @else
                                @if($cs)
                                    <a href="{{ route('category.product', $cs->slug) }}">{{ $cs->name }}</a><i
                                        class="icon-down icofont icofont-simple-right"></i>
                                @endif
                                {{ optional($data)->name }}
                            @endif
                        @endif
                    </span>
                </li>

                {{-- Subcategory list if category selected --}}
                @if (($input ?? '') == 'category' && !empty($cidn))
                    @foreach (App\Models\SubCategory::where('category_id', $cidn)->get() as $cat)
                        <li>
                            <input class="sub_mod" type="radio" id="{{ $cat->slug }}" name="sub_category" onchange="document.getElementById('form').submit()"
                                value="{{ $cat->slug }}">
                            <label for="{{ $cat->slug }}"><span class="span sub_mod">{{ $cat->name }}</span></label>
                        </li>
                    @endforeach
                @endif

                {{-- miniCategory list if sub_category selected --}}
                @if (($input ?? '') == 'sub_category' && !empty($idn))
                    @foreach (App\Models\miniCategory::where('category_id', $idn)->get() as $cat)
                        <li>
                            <input class="sub_mod" type="radio" id="{{ $cat->slug }}" name="mini_category" onchange="document.getElementById('form').submit()"
                                value="{{ $cat->slug }}">
                            <label for="{{ $cat->slug }}"><span class="span sub_mod">{{ $cat->name }}</span></label>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    @else
        {{-- Show all categories as default --}}
        <ul>
            @foreach (App\Models\Category::get() as $cat)
                <li>
                    <input class="sub_mod" type="radio" id="{{ $cat->slug }}" name="category" onchange="document.getElementById('form').submit()"
                        value="{{ $cat->slug }}">
                    <label for="{{ $cat->slug }}"><span class="span sub_mod">{{ $cat->name }}</span></label>
                </li>
            @endforeach
        </ul>
    @endif

    <!--<input type="hidden" name="amount" value="TK10+-+TK600000">-->
    <div class="left-side">
        <p style="border-top: 1px solid gainsboro;margin-bottom: 10px;padding-top: 10px;font-weight: 700;">Brand</p>
        <ul style="height: 200px;overflow-y: scroll;scrollbar-width: thin;">
            @foreach ($brands as $brand)
                <li>
                    <input type="checkbox" name="brands[]" class="checked" value="{{ $brand->slug }}" onchange="document.getElementById('form').submit()"
                        @isset($req->brands) @foreach ($req->brands as $req_brand) {{ $brand->slug == $req_brand ? 'checked' : '' }}  @endforeach @endisset>
                    <span class="span">{{ $brand->name }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <style>
        .cc label {
            display: block;
        }
    </style>
    <hr>

    <div class="cc">
        <label>
            <input @isset($req->sort) @if ($req->sort == 'Best Sellers') checked @endif @endisset
                name="sort" type="radio" value="Best Sellers" onchange="document.getElementById('form').submit()"> Best Sellers
        </label>
        <label>
            <input @isset($req->sort) @if ($req->sort == 'New To Old') checked @endif @endisset
                name="sort" type="radio" value="New To Old" onchange="document.getElementById('form').submit()"> New Released
        </label>
        <label>
            <input @isset($req->sort) @if ($req->sort == 'High To Low') checked @endif @endisset
                name="sort" type="radio" value="High To Low" onchange="document.getElementById('form').submit()"> Price- High To Low
        </label>
        <label>
            <input @isset($req->sort) @if ($req->sort == 'Low To High') checked @endif @endisset
                name="sort" type="radio" value="Low To High" onchange="document.getElementById('form').submit()"> Price- Low To High
        </label>
        <label>
            <input @isset($req->sort) @if ($req->sort == 'dHigh To Low') checked @endif @endisset
                name="sort" type="radio" value="dHigh To Low" onchange="document.getElementById('form').submit()"> Discount- High To Low
        </label>
        <label>
            <input @isset($req->sort) @if ($req->sort == 'dLow To High') checked @endif @endisset
                name="sort" type="radio" value="dLow To High" onchange="document.getElementById('form').submit()"> Discount- Low To High
        </label>
    </div>
    <hr>

    <input type="hidden" name="unr" value="{{ $req->unr ?? Request::url() }}">

    <div class="left-side action">
        <ul>
            <li><input value="Filter" type="submit"></li>
            <li> <a class="redirect"
                    style="background: #ff5722;color: white;border: none;padding: 8px 25px;display: inline-block;"
                    href="{{ $req->unr ?? Request::url() }}">Reset</a> </li>
        </ul>
    </div>
    <style>
        .action ul li {
            display: inline-block;
        }

        .action ul li input {
            display: block;
            background: #2abbe8;
            color: white;
            padding: 8px 25px;
            border-radius: 4px;
            border: none;
            font: 16px;
        }
    </style>
</form>

@push('js')
    <script>
        $('.cck').click(function() {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
            }
        })
        $('.sub_mod').click(function() {
            if ($(this).is(':checked')) {
                $('#dcd').prop('checked', false)
            }
        })
        $('.cck2').click(function() {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
            }
        })
    </script>
@endpush
