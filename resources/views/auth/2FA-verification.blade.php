@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-center mb-6">Two-Factor Authentication</h2>
        
        <p class="text-gray-600 text-center mb-6">
            Enter the 6-digit code from your authenticator app or use a backup code.
        </p>
        
        <form method="POST" action="{{ route('two-factor.verify') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Authentication Code:
                </label>
                <input type="text" 
                       name="code" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-center text-lg"
                       placeholder="000000"
                       maxlength="8" 
                       required>
                @error('code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                Verify
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="text-gray-600 hover:text-gray-800">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
@endsection