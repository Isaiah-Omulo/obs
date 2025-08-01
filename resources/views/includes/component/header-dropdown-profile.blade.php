<div class="dropdown-menu dropdown-menu-end me-1">
	<a href="{{ route('user.edit', auth()->user()->id) }}"  class="dropdown-item">Edit Profile</a>
	<div class="dropdown-divider"></div>
	<div class="dropdown-divider"></div>

    <!-- Logout Form (Styled Correctly) -->
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="dropdown-item">Log Out</button>
    </form>
</div>