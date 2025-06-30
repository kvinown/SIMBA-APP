@props(['default' => url('/dashboard')]) {{-- fallback default kalau history kosong --}}

<a href="#"
   onclick="window.history.length > 1 ? history.back() : window.location='{{ $default }}'"
   class="btn btn-outline-secondary ms-3">
    <i class="bi bi-arrow-left"></i> {{ $slot ?? 'Back' }}
</a>
