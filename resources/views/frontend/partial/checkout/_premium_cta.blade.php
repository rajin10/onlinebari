{{-- Shared premium place-order CTA. Requires the Alpine `checkout()` scope. --}}
<button type="submit" class="pc-cta" :class="{ 'is-loading': submitting }" :disabled="submitting">
    <span class="pc-spinner" x-show="submitting" x-cloak></span>
    <span x-show="!submitting">অর্ডার কনফার্ম করুন</span>
    <span x-show="submitting" x-cloak>প্রসেসিং...</span>
</button>

<div class="pc-trust">
    <span>✔ ক্যাশ অন ডেলিভারি সুবিধা</span>
    <span>✔ দ্রুত ডেলিভারি নিশ্চিত</span>
    <span>🔒 ১০০% নিরাপদ চেকআউট</span>
</div>
