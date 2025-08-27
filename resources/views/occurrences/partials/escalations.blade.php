<div class="card shadow-sm mt-3 border-0 rounded-4">
    <div class="card-header bg-warning text-dark rounded-top-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Escalations</h5>
        <a href="{{ route('escalate.create', ['id' => $occurrence->id]) }}" class="btn btn-outline-dark btn-sm">
            <i class="fas fa-plus me-1"></i> Escalate
        </a>
    </div>
    <div class="card-body p-0">
        @if($occurrence->escalations->count())
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered mb-0" id="occurrenceEscalationTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Recipient</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Sent By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($occurrence->escalations as $escalation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($escalation->created_at)->format('M d, Y h:i A') }}</td>
                                <td>{{ $escalation->recipient_email }}</td>
                                <!-- Subject with tooltip -->
                                <td>
                                    <span 
                                        class="d-inline-block text-truncate" 
                                        style="max-width: 150px;" 
                                        data-bs-toggle="tooltip" 
                                        title="{{ $escalation->subject }}"
                                    >
                                        {{ Str::limit($escalation->subject, 30) }}
                                    </span>
                                </td>
                                <!-- Message with tooltip -->
                                <td>
                                    <span 
                                        class="d-inline-block text-truncate" 
                                        style="max-width: 200px;" 
                                        data-bs-toggle="tooltip" 
                                        title="{{ $escalation->message }}"
                                    >
                                        {{ Str::limit($escalation->message, 50) }}
                                    </span>
                                </td>
                                <!-- Sent By badge -->
                                <td>
                                    @if($escalation->user)
                                        <span class="badge bg-primary">{{ $escalation->user->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">System</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted m-3">No escalations for this occurrence yet.</p>
        @endif
    </div>
</div>
