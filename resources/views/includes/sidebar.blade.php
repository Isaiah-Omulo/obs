



@php
    $appSidebarClass = !empty($appSidebarTransparent) ? 'app-sidebar-transparent' : '';
    $appSidebarAttr  = !empty($appSidebarLight) ? '' : ' data-bs-theme=dark';
@endphp
<style type="text/css">
	/* Sidebar for mobile */
@media (max-width: 991px) {
    #sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: fixed;
        top: 0;
        left: 0;
        width: 260px;
        z-index: 1051;
        height: 100vh;
        overflow-y: auto;
        background: #2d353c;
    }

    #sidebar.active {
        transform: translateX(0);
        box-shadow: 2px 0 8px rgba(0,0,0,0.3);
    }

    #sidebar-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none;
        z-index: 1050;
    }

    #sidebar-backdrop.active {
        display: block;
    }

    .menu-link {
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
    }
}

/* Smooth scrolling inside sidebar */
.app-sidebar-content {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    height: 100%;
}

</style>
<div id="sidebar" class="app-sidebar {{ $appSidebarClass }}" {{ $appSidebarAttr }}>
    <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
        <div class="menu">
            <!-- Profile -->
            <div class="menu-profile">
                <a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
                    <div class="menu-profile-cover with-shadow"></div>
                    <div class="menu-profile-image">
                        <img src="/assets/img/user/user-13.jpg" alt="" />
                    </div>
                    <div class="menu-profile-info">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">{{ auth()->user()->name }}</div>
                            <div class="menu-caret ms-auto"></div>
                        </div>
                        <small>{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</small>
                    </div>
                </a>
            </div>

            <div id="appSidebarProfileMenu" class="collapse">
                <div class="menu-item pt-5px">
                    <a href="{{ route('user.edit',  ['id' =>auth()->user()->id] ) }}" class="menu-link">
                        <div class="menu-icon"><i class="fa fa-cog"></i></div>
                        <div class="menu-text">Settings</div>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('other.feedback') }}" class="menu-link">
                        <div class="menu-icon"><i class="fa fa-pencil-alt"></i></div>
                        <div class="menu-text">Send Feedback</div>
                    </a>
                </div>
                <div class="menu-item pb-5px">
                    <a href="{{ route('other.help') }}" class="menu-link">
                        <div class="menu-icon"><i class="fa fa-question-circle"></i></div>
                        <div class="menu-text">Helps</div>
                    </a>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="menu-header">Navigation</div>
            @php
                function renderSubMenu($subMenuItems) {
                    $subMenuHtml = '';
                    $isChildActive = false;
                    foreach ($subMenuItems as $menu) {
                        $subSubMenu = '';
                        $isCurrentRouteActive = !empty($menu['route-name']) && Route::currentRouteName() == $menu['route-name'];
                        $hasSub = !empty($menu['sub_menu']);
                        $isParentOfActive = false;

                        if ($hasSub) {
                            list($subSubMenu, $isChildOfSubActive) = renderSubMenu($menu['sub_menu']);
                            if ($isChildOfSubActive) {
                                $isParentOfActive = true;
                                $isChildActive = true;
                            }
                        }

                        if ($isCurrentRouteActive) $isChildActive = true;

                        $activeClass = ($isCurrentRouteActive || $isParentOfActive) ? 'active' : '';
                        $hasCaret = $hasSub ? '<div class="menu-caret"></div>' : '';
                        $hasTitle = !empty($menu['title']) ? '<div class="menu-text">'. $menu['title'] .'</div>' : '';
                        $url = !empty($menu['route-name']) ? route($menu['route-name']) : 'javascript:;';

                        $subMenuHtml .= '
                            <div class="menu-item '. ($hasSub ? 'has-sub' : '') .' '. $activeClass .'">
                                <a href="'. $url .'" class="menu-link">' . $hasTitle . $hasCaret .'</a>
                                '. ($hasSub ? '<div class="menu-submenu">'. $subSubMenu .'</div>' : '') .'
                            </div>
                        ';
                    }
                    return [$subMenuHtml, $isChildActive];
                }

                $role = auth()->user()->role ?? '';
                $sidebarMenu = in_array($role, ['house_keeper', 'hostel_attendant'])
                        ? config('sidebar_attendant.menu')
                        : config('sidebar.menu');

                foreach ($sidebarMenu as $menu) {
                    $isParentActive = false;
                    $hasSub = !empty($menu['sub_menu']);
                    $isCurrentRouteActive = !empty($menu['route-name']) && Route::currentRouteName() == $menu['route-name'];

                    $subMenuHtml = '';
                    if ($hasSub) {
                        list($subMenuHtml, $isChildActive) = renderSubMenu($menu['sub_menu']);
                        if ($isChildActive) $isParentActive = true;
                    }

                    $activeClass = ($isCurrentRouteActive || $isParentActive) ? 'active' : '';
                    $hasCaret = $hasSub ? '<div class="menu-caret"></div>' : '';
                    $hasIcon = !empty($menu['icon']) ? '<div class="menu-icon"><i class="'. $menu['icon'] .'"></i></div>' : '';
                    $hasTitle = !empty($menu['title']) ? '<div class="menu-text">'. $menu['title'] .'</div>' : '';
                    $url = !empty($menu['route-name']) ? route($menu['route-name']) : 'javascript:;';

                    echo '
                        <div class="menu-item '. ($hasSub ? 'has-sub' : '') .' '. $activeClass .'">
                            <a href="'. $url .'" class="menu-link">
                                '. $hasIcon . $hasTitle . $hasCaret .'
                            </a>
                            '. ($hasSub ? '<div class="menu-submenu">'. $subMenuHtml .'</div>' : '') .'
                        </div>
                    ';
                }
            @endphp

            <!-- Minify Button -->
            <div class="menu-item d-flex">
                <a href="javascript:;" class="app-sidebar-minify-btn ms-auto" data-toggle="app-sidebar-minify">
                    <i class="fa fa-angle-double-left"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Backdrop -->
<div id="sidebar-backdrop" class="app-sidebar-backdrop"></div>


<script>
  function updateNairobiTime() {
    // Get the current time in Nairobi (UTC+3)
    const options = {
      timeZone: 'Africa/Nairobi',
      hour: 'numeric',
      minute: 'numeric',
      hour12: true
    };

    const formatter = new Intl.DateTimeFormat('en-US', options);
    const timeString = formatter.format(new Date());

    document.getElementById('currentTime').innerText = 'Current Time: ' + timeString;
  }

  // Update time immediately and every minute
  updateNairobiTime();
  setInterval(updateNairobiTime, 60000); // update every 60 seconds
</script>


