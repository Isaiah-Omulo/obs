@extends('layouts.default')
@section('title', 'Edit Hostel')

@section('content')
<div class="row mt-4">
    <div class="col-xl-8 mx-auto">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-edit me-2"></i> Edit Hostel</h4>
            </div>
            <div class="panel-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('hostels.update', $hostel->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Zone</label>
                        <select name="zone_id" class="form-select">
                            @foreach ($zones as $zone)
                                <option value="{{ $zone->id }}" {{ $hostel->zone_id == $zone->id ? 'selected' : '' }}>
                                    {{ $zone->name }}
                                </option>
                            @endforeach
                        </select>
                        
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hostel Name</label>
                        <input type="text" name="name" value="{{ old('name', $hostel->name) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Number of Students</label>
                        <input type="number" name="number_of_students" value="{{ old('number_of_students', $hostel->number_of_students) }}" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('hostels.index', $hostel->zone_id) }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save me-1"></i> Update Hostel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
