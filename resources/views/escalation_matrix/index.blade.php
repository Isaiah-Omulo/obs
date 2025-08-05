@extends('layouts.default')
@section('title', 'Escalation Matrix')

@section('content')
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Escalation Matrix</h4>
    </div>
    <div class="panel-body p-3">
        <a href="{{ route('escalation.create') }}" class="btn btn-success mb-3">
            <i class="fa fa-plus"></i> Add New Entry
        </a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matrices as $matrix)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $matrix->department_name }}</td>
                            <td>{{ $matrix->email }}</td>
                            <td>{{ $matrix->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
