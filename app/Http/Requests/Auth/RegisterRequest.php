<?php


namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ReferralCode;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country' => ['required', 'string', 'max:100'],
            'phone_country_code' => ['required', 'string', 'max:5'],
            'phone_number' => ['required', 'string', 'max:20'],
            'referral_code' => ['nullable', 'string', 'max:50', 'exists:referral_codes,code']
        ];
    }

    public function messages()
    {
        return [
            'referral_code.exists' => 'The referral code is invalid or has expired.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'name.required' => 'Full Name is required.',
            'country.required' => 'Country is required.',
            'phone_country_code.required' => 'Country code is required.',
            'phone_number.required' => 'Phone number is required.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->referral_code) {
                $referralCode = ReferralCode::where('code', $this->referral_code)->first();
                if ($referralCode && !$referralCode->canBeUsed()) {
                    $validator->errors()->add('referral_code', 'The referral code is not valid or has reached its usage limit.');
                }
            }
        });
    }
}