@extends('layouts.default')
@section('title', 'New Stats')
@section('content')
<div class="container mt-4 ml-5">
    <div class="card shadow rounded-4 border-0">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Record Student Statistics</h5>
            <a href="{{ route('student_statistics.index') }}" class="btn btn-success btn-sm">
                <i class="fas fa-list me-1"></i> View All Records
            </a>
        </div>

        <form action="{{ route('student_statistics.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="card-body">
                {{-- Hostel --}}
                <div class="mb-3">
                    <label for="hostel_id" class="form-label">Hostel</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                        <select name="hostel_id" id="hostel_id" class="form-select" required>
                            <option value="">Select Hostel</option>
                            @foreach($hostels as $hostel)
                                <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Date --}}
                <div class="mb-3">
                    <label for="record_date" class="form-label">Date of Record</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" name="record_date" id="record_date" class="form-control" required>
                    </div>
                </div>

                {{-- Shift --}}
                <div class="mb-3">
                    <label for="shift" class="form-label">Shift</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-moon"></i></span>
                        <select name="shift" id="shift" class="form-select" required>
                            <option value="">Select Shift</option>
                            <option value="Day">Day</option>
                            <option value="Night">Night</option>
                        </select>
                    </div>
                </div>

                {{-- Students Present --}}
                <div class="mb-3">
                    <label for="students_present" class="form-label">Students Present</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                        <input type="number" name="students_present" id="students_present" class="form-control" min="0" required>
                    </div>
                </div>

                {{-- Comments --}}
                <div class="mb-3">
                    <label for="comments" class="form-label">Comments</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-comment-dots"></i></span>
                        <textarea name="comments" id="comments" class="form-control" rows="3" placeholder="Optional..."></textarea>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Save Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
