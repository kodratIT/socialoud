@php
    $primarySidebar = dynamic_sidebar('primary_sidebar');

    if (is_plugin_active('ads')) {
        $primarySidebar = AdsManager::display('top-sidebar', ['class' => 'sidebar-item sidebar-item-ads']) . $primarySidebar .
        AdsManager::display('bottom-sidebar', ['class' => 'sidebar-item sidebar-item-ads']);
    }
@endphp

@if ($primarySidebar)
    <aside class="sidebar fright">
        {!! $primarySidebar !!}
    </aside>
    <section class="cboth"></section>
@endif
