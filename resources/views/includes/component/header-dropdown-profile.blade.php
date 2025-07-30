<div class="dropdown-menu dropdown-menu-end me-1">
	<a href="javascript:;" class="dropdown-item">Edit Profile</a>
	<a href="javascript:;" class="dropdown-item d-flex align-items-center">
		Inbox
		<span class="badge bg-danger rounded-pill ms-auto pb-4px">2</span>
	</a>
	<a href="javascript:;" class="dropdown-item">Calendar</a>
	<a href="javascript:;" class="dropdown-item">Setting</a>
	<div class="dropdown-divider"></div>
	<div class="dropdown-divider"></div>

    <!-- Logout Form (Styled Correctly) -->
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="dropdown-item">Log Out</button>
    </form>
</div>