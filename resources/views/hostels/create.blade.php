@extends('layouts.default')
@section('title', 'Add Hostel')

@section('content')
<!-- BEGIN panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Add New Hostel</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                <i class="fa fa-expand"></i>
            </a>
        </div>
    </div>
    <div class="panel-body p-3">
        <form action="{{ route('hostels.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Hostel Name</label>
                    {{-- 
                        1. Add 'is-invalid' class if an error for 'name' exists.
                        2. Use old('name') to repopulate the field with its previous value.
                    --}}
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter hostel name" value="{{ old('name') }}" required>
                    
                    {{-- 3. Display the error message if it exists. --}}
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="zone_id" class="form-label">Zone</label>
                    <select name="zone_id" class="form-control @error('zone_id') is-invalid @enderror" required>
                        <option value="">Select a Zone</option>
                        @foreach($zones as $zone)
                            {{-- Conditionally add 'selected' if this was the previously selected option --}}
                            <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('zone_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="number_of_students" class="form-label">Number of Students (Capacity)</label>
                    <input type="number" name="number_of_students" class="form-control @error('number_of_students') is-invalid @enderror" value="{{ old('number_of_students') }}" required placeholder="Enter hostel capacity">
                    @error('number_of_students')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Save Hostel
                </button>
                <a href="{{ route('hostels.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<!-- END panel -->
@endsection