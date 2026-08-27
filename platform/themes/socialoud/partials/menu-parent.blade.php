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
</ul>
