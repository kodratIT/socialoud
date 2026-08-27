@php
    $parentCategories = collect($menu_nodes)->filter(fn ($row) => $row->reference_type === \Botble\Blog\Models\Category::class);
@endphp

<ul {!! $options !!}>
    @foreach ($parentCategories as $row)
        <li @class([$row->css_class, 'current' => $row->active])>
            <a href="{{ url($row->url) }}" @if ($row->target !== '_self') target="{{ $row->target }}" @endif>
                {!! $row->icon_html !!} <span>{{ $row->title }}</span>
            </a>
        </li>
    @endforeach
    @if (\Illuminate\Support\Facades\Route::has('public.galleries'))
        <li @class(['socialoud-gallery-menu-item', 'current' => request()->routeIs('public.galleries')])>
            <a href="{{ route('public.galleries') }}"><span>Gallery</span></a>
        </li>
    @endif
</ul>
