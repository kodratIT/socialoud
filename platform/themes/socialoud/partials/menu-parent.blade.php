@php
    $parentCategories = \Botble\Blog\Models\Category::query()
        ->wherePublished()
        ->where(fn ($query) => $query->whereNull('parent_id')->orWhere('parent_id', 0))
        ->orderBy('order')
        ->orderBy('name')
        ->get();
@endphp

<ul {!! $options !!}>
    @foreach ($parentCategories as $category)
        <li @class(['current' => rtrim(url($category->url), '/') === rtrim(request()->url(), '/')])>
            <a href="{{ url($category->url) }}">
                <span>{{ $category->name }}</span>
            </a>
        </li>
    @endforeach
    @if (\Illuminate\Support\Facades\Route::has('public.galleries'))
        <li @class(['socialoud-gallery-menu-item', 'current' => request()->routeIs('public.galleries')])>
            <a href="{{ route('public.galleries') }}"><span>Gallery</span></a>
        </li>
    @endif
</ul>

