@extends('layouts.default')
@section('title', 'Update User')

@section('content')
<div class="container-fluid">
    <!-- begin row -->
    <div class="row d-flex align-items-center justify-content-center" style="min-height: 70vh;">
        <!-- begin col-6 -->
        <div class="col-xl-6 col-lg-8">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Edit User Details</h4>
                    <div class="panel-heading-btn">
                        <a href="{{ route('user.index') }}" class="btn btn-xs btn-primary">All Users</a>
                       
                    </div>
                </div>
                <div class="panel-body">
                    @if(session('success'))
                        <div class="alert alert-success text-white bg-primary p-2 rounded">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger text-white bg-danger p-2 rounded">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.update', $user->id) }}">
                        @csrf
                        @method('PUT') <!-- Use PUT method for updates -->

                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" id="name" name="name" value="{{ $user->name}}" class="form-control" placeholder="Enter full name" required>
                            </div>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                <input type="email" id="email" name="email" value="{{ $user->email}}" class="form-control" placeholder="Enter email address" required>
                            </div>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                <input type="text" id="phone" name="phone" value="{{  $user->phone }}" class="form-control" placeholder="Enter phone number">
                            </div>
                            @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="role">Role</label>
                            <select id="role" name="role" class="form-control select2" required>
                                <option value="">Select a role</option>
                                <option value="house_keeper" {{ old('role', $user->role) == 'house_keeper' ? 'selected' : '' }}>House Keeper</option>
                                <option value="hostel_attendant" {{ old('role', $user->role) == 'hostel_attendant' ? 'selected' : '' }}>Hostel Attendant</option>
                                <option value="administrator" {{ old('role', $user->role) == 'administrator' ? 'selected' : '' }}>Administrator</option>
                                <option value="coordinator" {{ old('role', $user->role) == 'coordinator' ? 'selected' : '' }}>Coordinator</option>
                                <option value="zonal_officer" {{ old('role', $user->role) == 'zonal_officer' ? 'selected' : '' }}>Zonal Officer</option>
                                <option value="director" {{ old('role', $user->role) == 'director' ? 'selected' : '' }}>Director</option>
                                <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                            </select>
                            @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update User</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-6 -->
    </div>
    <!-- end row -->
</div>
@endsection

@push('scripts')
<!-- Make sure to include Select2 CSS & JS in your main layout if not already present -->
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select a role",
            allowClear: true
        });
    });
</script>
@endpush