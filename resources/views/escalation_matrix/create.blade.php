@extends('layouts.default')
@section('title', 'Add Escalation Matrix')

@section('content')
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Add Escalation Matrix</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                <i class="fa fa-expand"></i>
            </a>
        </div>
    </div>
    <div class="panel-body p-3">
        <form action="{{ route('escalation.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="department_name" class="form-label">Department Name</label>
                    <input type="text" class="form-control" name="department_name" required placeholder="e.g. Health Unit, OSA, Security">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" required placeholder="Enter email">
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Save Entry
                </button>
                <a href="{{ route('escalation.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
