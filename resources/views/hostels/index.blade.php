@extends('layouts.default')
@section('title', 'All Hostels')

@section('content')
<div class="row mt-4">
    <div class="col-xl-10 mx-auto">
        <div class="panel panel-inverse">
            <div class="panel-heading d-flex justify-content-between align-items-center">
                <h4 class="panel-title">
                    <i class="fa fa-building me-2"></i> All Hostels
                </h4>
                <a href="{{ route('hostels.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus me-1"></i> Add Hostel
                </a>
            </div>

            <div class="panel-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Hostel Name</th>
                                <th>Zone</th>
                                <th>No. of Students</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hostels as $index => $hostel)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $hostel->name }}</td>
                                    <td>{{ $hostel->zone->name ?? 'Unassigned' }}</td>
                                    <td>{{ $hostel->number_of_students }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('hostels.edit', $hostel->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('hostels.destroy', $hostel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this hostel?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hostels found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
