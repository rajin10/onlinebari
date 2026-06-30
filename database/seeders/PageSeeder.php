<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Seed the CMS pages linked from the storefront footer.
     *
     * `name` doubles as the URL slug (route('page', ['slug' => $name]))
     * and is matched by pageController@pageshow via where('name', $slug).
     * Rows are idempotent (updateOrCreate keyed on name) and editable in admin.
     */
    public function run(): void
    {
        $pages = [
            [
                'name' => 'about',
                'title' => 'About Us',
                'position' => 1,
                'body' => <<<'HTML'
<h1>About Us</h1>
<p>Welcome to AnasLuxyWorld — your destination for premium cozy lighting crafted to elevate your space with elegance and warmth.</p>
<p>We curate a hand-picked collection of lamps, fixtures, and accent lighting designed to transform any room into a warm, inviting retreat. Every product is selected for quality, durability, and timeless style.</p>
<p>Have a question? Visit our <a href="/contact/form">contact page</a> and our team will be happy to help.</p>
HTML,
            ],
            [
                'name' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'position' => 2,
                'body' => <<<'HTML'
<h1>Privacy Policy</h1>
<p>This Privacy Policy explains how AnasLuxyWorld collects, uses, and protects the information you provide when you use our website.</p>
<h3>Information We Collect</h3>
<p>We collect information you provide directly — such as your name, email address, shipping address, and payment details — when you create an account or place an order.</p>
<h3>How We Use Your Information</h3>
<p>Your information is used to process orders, provide customer support, and improve your shopping experience. We never sell your personal data to third parties.</p>
<h3>Contact</h3>
<p>For any privacy-related questions, please reach out through our <a href="/contact/form">contact page</a>.</p>
HTML,
            ],
            [
                'name' => 'terms-and-conditions',
                'title' => 'Terms & Conditions',
                'position' => 3,
                'body' => <<<'HTML'
<h1>Terms &amp; Conditions</h1>
<p>By accessing and using AnasLuxyWorld, you agree to the following terms and conditions.</p>
<h3>Use of the Site</h3>
<p>You agree to use this site for lawful purposes only and not to engage in any activity that disrupts or interferes with its operation.</p>
<h3>Orders &amp; Pricing</h3>
<p>All orders are subject to availability and confirmation of the order price. We reserve the right to refuse or cancel any order.</p>
<h3>Changes to These Terms</h3>
<p>We may update these terms from time to time. Continued use of the site constitutes acceptance of the revised terms.</p>
HTML,
            ],
            [
                'name' => 'refund-policy',
                'title' => 'Refund Policy',
                'position' => 4,
                'body' => <<<'HTML'
<h1>Refund Policy</h1>
<p>We want you to love your purchase. If you are not completely satisfied, our refund policy is here to help.</p>
<h3>Returns</h3>
<p>Items may be returned within 7 days of delivery, provided they are unused and in their original packaging.</p>
<h3>Refunds</h3>
<p>Once your return is received and inspected, an approved refund will be processed to your original payment method.</p>
<h3>Questions</h3>
<p>If you have any questions about a return or refund, please contact us through our <a href="/contact/form">contact page</a>.</p>
HTML,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['name' => $page['name']],
                array_merge($page, ['status' => true]),
            );
        }
    }
}
