@php
    $hasSub = !empty($item['sub_menu']);
    $hasActiveChild = $hasSub && collect($item['sub_menu'])->contains('active', true);
    $isActive = !empty($item['active']);
    $isExpanded = $isActive || $hasActiveChild;

    // Base class for the menu item
    $menuItemClass = 'menu-item';
    if ($hasSub) $menuItemClass .= ' has-sub';
    if ($isExpanded) $menuItemClass .= ' active';
@endphp

<div class="{{ $menuItemClass }}">
    <a href="{{ $item['url'] }}" class="menu-link">
        @if (!empty($item['icon']))
            <div class="menu-icon"><i class="{{ $item['icon'] }}"></i></div>
        @endif
        @if (!empty($item['img']))
            <div class="menu-icon-img"><img src="{{ $item['img'] }}" /></div>
        @endif
        
        <div class="menu-text">
            {{ $item['title'] }}
            @if (!empty($item['label']))
                <span class="menu-label">{{ $item['label'] }}</span>
            @endif
            @if (!empty($item['highlight']))
                <i class="fa fa-paper-plane text-theme ms-1"></i>
            @endif
        </div>

        @if (!empty($item['badge']))
            <div class="menu-badge">{{ $item['badge'] }}</div>
        @endif
        @if ($hasSub)
            <div class="menu-caret"></div>
        @endif
    </a>

    @if ($hasSub)
        <div class="menu-submenu">
            @foreach ($item['sub_menu'] as $subItem)
                {{-- Here is the recursion! --}}
                @include('partials.sidebar-item', ['item' => $subItem])
            @endforeach
        </div>
    @endif
</div>