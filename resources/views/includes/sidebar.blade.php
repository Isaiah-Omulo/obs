@php
	$appSidebarClass = !empty($appSidebarTransparent) ? 'app-sidebar-transparent' : '';
	$appSidebarAttr  = !empty($appSidebarLight) ? '' : ' data-bs-theme=dark';
@endphp
<!-- BEGIN #sidebar -->
<div id="sidebar" class="app-sidebar {{ $appSidebarClass }}"{{ $appSidebarAttr }}>
	<!-- BEGIN scrollbar -->
	<div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
		<div class="menu">
			@if (!$appSidebarSearch)
			<div class="menu-profile">
				<a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
					<div class="menu-profile-cover with-shadow"></div>
					<div class="menu-profile-image">
						<img src="/assets/img/user/user-13.jpg" alt="" />
					</div>
					<div class="menu-profile-info">
						<div class="d-flex align-items-center">
							<div class="flex-grow-1">
								{{ auth()->user()->name }}
							</div>
							<div class="menu-caret ms-auto"></div>
						</div>
						<small>{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</small>
					</div>
				</a>
			</div>
			<div id="appSidebarProfileMenu" class="collapse">
				<div class="menu-item pt-5px">
				<a href="{{ route('user.edit', auth()->user()->id) }}" class="menu-link">
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

				<div class="menu-divider m-0"></div>
			</div>
			@endif

			@if ($appSidebarSearch)
			<div class="menu-search mb-n3">
				<input type="text" class="form-control" placeholder="Sidebar menu filter..." data-sidebar-search="true" />
			</div>
			@endif

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

						if ($isCurrentRouteActive) {
							$isChildActive = true;
						}

						$activeClass = ($isCurrentRouteActive || $isParentOfActive) ? 'active' : '';
						$hasCaret = $hasSub ? '<div class="menu-caret"></div>' : '';
						$hasHighlight = !empty($menu['highlight']) ? '<i class="fa fa-paper-plane text-theme ms-1"></i>' : '';
						$hasTitle = !empty($menu['title']) ? '<div class="menu-text">'. $menu['title'] . $hasHighlight .'</div>' : '';
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

				foreach (config('sidebar.menu') as $menu) {
					$isParentActive = false;
					$hasSub = !empty($menu['sub_menu']);
					$isCurrentRouteActive = !empty($menu['route-name']) && Route::currentRouteName() == $menu['route-name'];

					$subMenuHtml = '';
					if ($hasSub) {
						list($subMenuHtml, $isChildActive) = renderSubMenu($menu['sub_menu']);
						if ($isChildActive) {
							$isParentActive = true;
						}
					}

					$activeClass = ($isCurrentRouteActive || $isParentActive) ? 'active' : '';

					$hasCaret = !empty($menu['caret']) || $hasSub ? '<div class="menu-caret"></div>' : '';
					$hasIcon = !empty($menu['icon']) ? '<div class="menu-icon"><i class="'. $menu['icon'] .'"></i></div>' : '';
					$hasImg = !empty($menu['img']) ? '<div class="menu-icon-img"><img src="'. $menu['img'] .'" /></div>' : '';
					$hasLabel = !empty($menu['label']) ? '<span class="menu-label">'. $menu['label'] .'</span>' : '';
					$hasTitle = !empty($menu['title']) ? '<div class="menu-text">'. $menu['title'] . $hasLabel .'</div>' : '';
					$hasBadge = !empty($menu['badge']) ? '<div class="menu-badge">'. $menu['badge'] .'</div>' : '';
					$url = !empty($menu['route-name']) ? route($menu['route-name']) : 'javascript:;';

					echo '
						<div class="menu-item '. ($hasSub ? 'has-sub' : '') .' '. $activeClass .'">
							<a href="'. $url .'" class="menu-link">
								'. $hasImg .'
								'. $hasIcon .'
								'. $hasTitle .'
								'. $hasBadge .'
								'. $hasCaret .'
							</a>
							'. ($hasSub ? '<div class="menu-submenu">'. $subMenuHtml .'</div>' : '') .'
						</div>
					';
				}
			@endphp
			<!-- BEGIN minify-button -->
			<div class="menu-item d-flex">
				<a href="javascript:;" class="app-sidebar-minify-btn ms-auto" data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
			</div>
			<!-- END minify-button -->
		</div>
		<!-- END menu -->
	</div>
	<!-- END scrollbar -->
</div>
<div class="app-sidebar-bg"{{ $appSidebarAttr }}></div>
<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
<!-- END #sidebar -->