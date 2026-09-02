@php
    $categoryIds = collect($menu_nodes)
        ->filter(fn ($row) => $row->reference_type === \Botble\Blog\Models\Category::class)
        ->pluck('reference_id')
        ->filter()
        ->unique()
        ->values();
    $categories = \Botble\Blog\Models\Category::query()
        ->whereIn('id', $categoryIds)
        ->with('slugable')
        ->get()
        ->keyBy('id');
@endphp

<ul {!! $options !!}>
    @foreach ($menu_nodes as $row)
        @php
            $category = $categories->get($row->reference_id);
            $isActive = $category && rtrim(url($category->url), '/') === rtrim(request()->url(), '/');
        @endphp
        @if ($row->reference_type === \Botble\Blog\Models\Category::class && $category)
            <li @class([$row->css_class, 'current' => $isActive])>
                <a href="{{ url($category->url) }}" @if ($row->target !== '_self') target="{{ $row->target }}" @endif>
                    {!! $row->icon_html !!} <span>{{ $category->name }}</span>
                </a>
            </li>
        @endif
    @endforeach
    @if (\Illuminate\Support\Facades\Route::has('public.galleries'))
        <li @class(['socialoud-gallery-menu-item', 'current' => request()->routeIs('public.galleries')])>
            <a href="{{ route('public.galleries') }}"><span>Gallery</span></a>
        </li>
    @endif
</ul>

