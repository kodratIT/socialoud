@php
    $pageNodes = collect($menu_nodes)->filter(fn ($row) => $row->reference_type === \Botble\Page\Models\Page::class);
@endphp

<ul {!! $options !!}>
    @foreach ($pageNodes as $row)
        <li @class([$row->css_class, 'current' => $row->active])>
            <a href="{{ url($row->url) }}" @if ($row->target !== '_self') target="{{ $row->target }}" @endif>
                {!! $row->icon_html !!}<span>{{ $row->title }}</span>
            </a>
            @if ($row->has_child)
                {!! Menu::generateMenu([
                    'menu' => $menu,
                    'menu_nodes' => $row->child,
                    'view' => 'menu-pages',
                    'theme' => true,
                ]) !!}
            @endif
        </li>
    @endforeach
</ul>
