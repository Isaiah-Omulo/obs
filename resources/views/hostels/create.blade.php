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
                    <input type="text" class="form-control" name="name" placeholder="Enter hostel name" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="zone_id" class="form-label">Zone</label>
                    <select name="zone_id" class="form-control" required>
                        <option value="">Select a Zone</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="number_of_students" class="form-label">Number of Students</label>
                    <input type="number" name="number_of_students" class="form-control" required placeholder="Enter number of students">
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
