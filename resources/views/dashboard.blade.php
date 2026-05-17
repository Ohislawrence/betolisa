@php
    // Safety fallback — redirect to the correct dashboard based on role
    if (auth()->user()?->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('bettor.dashboard');
@endphp
