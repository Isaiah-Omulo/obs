@extends('layouts.default')
@section('title', 'All Zones')


@section('content')
<!-- BEGIN row -->
<div class="row mt-4">
    <div class="col-xl-10 mx-auto">
        <!-- BEGIN panel -->
        <div class="panel panel-inverse">
            <div class="panel-heading d-flex justify-content-between align-items-center">
                <h4 class="panel-title"><i class="fa fa-map-marker-alt me-2"></i> Zones List</h4>
                <div>
                    <a href="{{ route('zones.create') }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-plus me-1"></i> Add Zone
                    </a>
                </div>
            </div>
            <div class="panel-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="10%">#</th>
                                <th>Zone Name</th>
                                <th class="text-end" width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($zones as $index => $zone)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $zone->name }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('zones.edit', $zone->id) }}" class="btn btn-sm btn-outline-success">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('zones.destroy', $zone->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete this zone?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No zones found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END panel -->
    </div>
</div>
<!-- END row -->
@endsection
