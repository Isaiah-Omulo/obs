<div class="table-responsive">
    {{-- The table ID is now dynamic, passed from the main view --}}
    <table class="table table-striped table-hover table-bordered mb-0 report-table" id="{{ $tableId }}">
        <thead class="table-dark">
            <tr>
                @if (in_array(auth()->user()->role, ['director', 'manager']))
                    <th style="width: 100px;">Actions</th>
                @endif
                <th>Date & Time</th>
                <th>Report</th>
                <th>Shift</th>
                <th>Zone</th>
                <th>Report By</th>
                <th>Manager Input</th>
                <th>Director Input</th>
            </tr>
        </thead>
        <tbody>
            {{-- We use the generic $reports variable passed to this partial --}}
            @forelse ($reports as $report)
                <tr id="report-row-{{ $report->id }}">
                    @if (in_array(auth()->user()->role, ['director', 'manager']))
                        <td>
                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                <button class="btn btn-sm btn-info text-white btn-add-report-input" 
                                        data-id="{{ $report->id }}"
                                        data-role="{{ auth()->user()->role }}"
                                        data-input-url="{{ route('daily_reports.input', $report->id) }}"
                                        title="Add your Input"
                                        data-bs-toggle="modal"
                                        data-bs-target="#reportInputModal">
                                    <i class="fas fa-comment-medical"></i>
                                </button>
                                {{-- Add other action buttons like Edit or Delete here if needed --}}
                            </div>
                        </td>
                    @endif

                    <td data-order="{{ $report->created_at->timestamp }}">
                        <strong>{{ $report->created_at->format('M d, Y') }}</strong><br>
                        <small class="text-muted">{{ $report->created_at->format('h:i A') }}</small>
                    </td>
                  
                    <td>
                        {!! nl2br(e(Str::words($report->report, 40, '...'))) !!}
                        @if (Str::length($report->report) > Str::length(Str::words($report->report, 40)))
                            <br>
                            <a href="#" 
                               class="text-primary fw-bold view-full-report" 
                               data-report="{{ nl2br(e($report->report)) }}"
                               style="cursor: pointer;">View More</a>
                        @endif
                    </td>

                    <td>{{ ucfirst($report->shift) }}</td>
                    <td>{{ $report->user->zone ?? '-' }}</td>
                    <td>{{ $report->user->name ?? 'N/A' }}</td>
                    <td id="manager-{{ $report->id }}">{{ $report->manager_input ?? 'N/A' }}</td>
                    <td id="director-{{ $report->id }}">{{ $report->director_input ?? 'N/A' }}</td>
                </tr>
            @empty
               <tr>
                    {{-- Adjust colspan based on whether the Actions column is visible --}}
                    <td colspan="{{ in_array(auth()->user()->role, ['director', 'manager']) ? '8' : '7' }}" class="text-center text-muted">
                        No reports found for this category on this date.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>