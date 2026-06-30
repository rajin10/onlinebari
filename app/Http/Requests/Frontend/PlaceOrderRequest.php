<?php

namespace App\Http\Requests\Frontend;

use App\Models\Order;
use App\Rules\BangladeshPhone;
use App\Support\BdPhone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Public storefront checkout (guests + customers).
        return true;
    }

    /**
     * Normalise the phone before validation so the stored value and all
     * downstream lookups use the canonical 01XXXXXXXXX form.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $normalized = BdPhone::normalize((string) $this->input('phone'));

            $this->merge([
                'phone' => $normalized ?? trim((string) $this->input('phone')),
            ]);
        }

        if ($this->has('first_name')) {
            $this->merge(['first_name' => trim((string) $this->input('first_name'))]);
        }
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', new BangladeshPhone],
            'address' => ['nullable', 'string', 'max:1000'],
            'email' => ['nullable', 'email', 'max:191'],
            'payment_method' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'আপনার নাম লিখুন।',
            'first_name.max' => 'নাম অনেক বড় হয়ে গেছে।',
            'phone.required' => 'মোবাইল নম্বর দিন।',
            'address.max' => 'ঠিকানা অনেক বড় হয়ে গেছে।',
            'email.email' => 'সঠিক ইমেইল ঠিকানা দিন।',
        ];
    }

    /**
     * Enforce the anti-spam order limit (default: 1 order / 24h per number).
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $phone = BdPhone::normalize((string) $this->input('phone'));

            // Invalid phone already produces its own error.
            if (! $phone || ! $this->orderLimitEnabled() || $this->phoneBypassed($phone)) {
                return;
            }

            $hours = $this->orderLimitHours();
            $suffix = substr($phone, -10);

            $recentExists = Order::where('phone', 'like', '%'.$suffix)
                ->where('status', '!=', 2) // exclude cancelled so a cancel doesn't lock the buyer out
                ->where('created_at', '>=', now()->subHours($hours))
                ->exists();

            if ($recentExists) {
                $validator->errors()->add(
                    'phone',
                    'আপনি গত ২৪ ঘণ্টার মধ্যে একটি অর্ডার করেছেন, অনুগ্রহ করে পরে চেষ্টা করুন।'
                );
            }
        });
    }

    private function orderLimitEnabled(): bool
    {
        $value = setting('ORDER_LIMIT_ENABLED');

        // Default ON (anti-spam). Only an explicit '0'/'false' disables it.
        if ($value === null || $value === '') {
            return true;
        }

        return ! in_array(strtolower((string) $value), ['0', 'false', 'off', 'no'], true);
    }

    private function orderLimitHours(): int
    {
        $hours = (int) (setting('ORDER_LIMIT_HOURS') ?: 24);

        return $hours > 0 ? $hours : 24;
    }

    private function phoneBypassed(string $normalizedPhone): bool
    {
        $list = (string) (setting('ORDER_LIMIT_BYPASS_PHONES') ?? '');

        if (trim($list) === '') {
            return false;
        }

        foreach (preg_split('/[,\s]+/', $list) as $entry) {
            if (BdPhone::normalize($entry) === $normalizedPhone) {
                return true;
            }
        }

        return false;
    }
}
