@extends('layouts.default')
@section('title', 'Send')


@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i> Escalate an Issue: </h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('escalate.send') }}">
                @csrf

                <input type="hidden" name="occurrence_id" value="{{ $id ?? '' }}">

                <div class="mb-3">
                    <label for="recipient_email" class="form-label">Recipient</label>
                    <select name="recipient_email" id="recipient_email" class="form-select" required>
                        <option value="">Select recipient</option>
                        @foreach($recipients as $recipient)
                            <option value="{{ $recipient->email }}">{{ $recipient->department_name ?? $recipient->name }} - {{ $recipient->email }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
                </div>

                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-paper-plane me-1"></i> Send Escalation
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
