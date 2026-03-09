@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-center mb-6">Setup Two-Factor Authentication</h2>
        
        <div class="text-center mb-6">
            <p class="text-gray-600 mb-4">Scan this QR code with your authenticator app:</p>
            <div class="flex justify-center">
                <img src="{{ $qrCodeUrl }}" alt="QR Code" class="border rounded">
            </div>
        </div>
        
        <div class="mb-6">
            <p class="text-sm text-gray-600 mb-2">Or enter this secret key manually:</p>
            <div class="bg-gray-100 p-3 rounded font-mono text-sm break-all">
                {{ $secretKey }}
            </div>
        </div>
        
        <form method="POST" action="{{ route('two-factor.enable') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Enter the 6-digit code from your authenticator app:
                </label>
                <input type="text" 
                       name="code" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       maxlength="6" 
                       required>
                @error('code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                Enable 2FA
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="{{ route('profile.show') }}" class="text-gray-600 hover:text-gray-800">
                Back to Profile
            </a>
        </div>
    </div>
</div>
@endsection