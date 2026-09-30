@if(session('impersonated_by'))
<div class="bg-red-600 text-white p-3 text-center">
    <div class="flex items-center justify-center gap-4">
        <i data-lucide="alert-triangle" class="h-5 w-5"></i>
        <span>You are impersonating {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('superadmin.stop-impersonation') }}" class="inline">
            @csrf
            <button type="submit" class="bg-red-700 hover:bg-red-800 px-3 py-1 rounded text-sm">
                Stop Impersonation
            </button>
        </form>
    </div>
</div>
@endif