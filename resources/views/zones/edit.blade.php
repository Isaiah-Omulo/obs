@extends('layouts.default')
@section('title', 'Edit Zone')

@section('content')
<!-- BEGIN row -->
<div class="row mt-4">
    <div class="col-xl-8 mx-auto">
        <!-- BEGIN panel -->
        <div class="panel panel-inverse">
            <div class="panel-heading d-flex justify-content-between align-items-center">
                <h4 class="panel-title"><i class="fa fa-edit me-2"></i> Edit Zone</h4>
                <a href="{{ route('zones.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
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

                <form action="{{ route('zones.update', $zone->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Zone Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $zone->name) }}" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save me-1"></i> Update Zone
                        </button>
                        <a href="{{ route('zones.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- END panel -->
    </div>
</div>
<!-- END row -->
@endsection
