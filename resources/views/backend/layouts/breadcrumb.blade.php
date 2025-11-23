@if ($breadcrumbs)
<div class="page-header">
    <h5 class="fw-bold mb-2">
        {{ $breadcrumbs['title'] }}
    </h5>
    <ul class="breadcrumbs mb-2">
        <li class="nav-home">
            <a href="{{ route('dashboard') }}">
                <i class="icon-home"></i>
            </a>
        </li>
        @foreach ($breadcrumbs['data'] as $item)
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            @if ($item['route'])
            <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}">{{ $item['name'] }}</a>
            @else
            {{ $item['name'] }}
            @endif
        </li>
        @endforeach
    </ul>
</div>
@endif