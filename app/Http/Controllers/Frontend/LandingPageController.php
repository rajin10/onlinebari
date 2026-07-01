<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LandingPageContent;

class LandingPageController extends Controller
{
    /**
     * GET /lice-comb — standalone lice-comb landing page.
     */
    public function liceComb()
    {
        $content = LandingPageContent::forPage('lice-comb');

        return view('frontend.landing.lice-comb', compact('content'));
    }

    /**
     * GET /rust-removals — standalone rust-removal landing page.
     */
    public function rustRemoval()
    {
        $content = LandingPageContent::forPage('rust-removals');

        return view('frontend.landing.rust-removal', compact('content'));
    }
}
