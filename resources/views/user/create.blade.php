@extends('layouts.default')
@section('title', 'Add User')

@section('content')
<div class="container-fluid">
    <!-- begin page-header -->
   
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row d-flex align-items-center justify-content-center" style="min-height: 70vh;">
        <!-- begin col-6 -->
        <div class="col-xl-6 col-lg-8">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">New User Details</h4>
                    <div class="panel-heading-btn">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('user.index') }}" class="btn btn-primary">All Users</a>
                        </div>
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


                    <form method="POST" action="{{ route('user.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter full name" required>
                            </div>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter email address" required>
                            </div>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                <input type="text" id="phone" name="phone"  class="form-control" placeholder="Enter phone number">
                            </div>
                            @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="role">Role</label>
                            <select id="role" name="role" class="form-control select2" required>
                                <option value="">Select a role</option>
                                <option value="house_keeper">House Keeper</option>
                                <option value="hostel_attendant">Hostel Attendant</option>
                                <option value="administrator">Administrator</option>
                                <option value="coordinator">Coordinator</option>
                                
                                <option value="zonal_officer" >Zonal Officer</option>
                                <option value="director" >Director</option>
                                <option value="manager" >Manager</option>
                            </select>
                            @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100">Create User</button>
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