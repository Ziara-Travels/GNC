@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-center mb-6">Backup Codes</h2>
        
        <div class="text-center mb-6">
            <p class="text-gray-600 mb-4">
                Store these backup codes in a secure password manager. Each code can be used once to access your account if you lose your authentication device.
            </p>
        </div>
        
        <div class="bg-gray-100 p-4 rounded-lg mb-6">
            <p class="text-center text-red-600 mb-4 font-bold">Save these codes in a secure place. They can only be viewed once!</p>
            <ul class="grid grid-cols-2 gap-2">
                @foreach ($backupCodes as $code)
                <li class="font-mono text-center p-2 bg-white border border-gray-200 rounded">{{ $code }}</li>
                @endforeach
            </ul>
        </div>
        
        <div class="flex flex-col space-y-4">
            <button onclick="printBackupCodes()" class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition-colors">
                Print Codes
            </button>
            
            <a href="{{ route('user.profile.my') }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors text-center">
                I've Saved My Backup Codes
            </a>
        </div>
    </div>
</div>

<script>
    function printBackupCodes() {
        window.print();
    }
</script>
@endsection
