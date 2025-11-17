<div class="table-responsive">
     <table class="table table-striped table-hover table-bordered mb-0" id="attendantTable">
                    <thead class="table-dark">
                        <tr>




                            @if (in_array(auth()->user()->role, ['director', 'manager']))
                            <th style="width: 160px;">Actions</th>
                            @endif
                            <th>Date & Time</th>
                            <th>Report</th>
                            <th>Shift</th>
                            <th>Hostel</th>
                            <th>Report By</th>
                            <th>Manager</th>
                            <th>Director</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendantReports as $report)
                            <tr id="report-row-{{ $report->id }}">
                                @if (in_array(auth()->user()->role, ['director', 'manager']))
                                <td>

                                <div class="d-flex flex-wrap gap-1">

                                    {{-- <a href="{{ route('daily_reports.edit', $report->id) }}" 
                                           class="btn btn-sm btn-warning m-1" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                       <form action="{{ route('daily_reports.destroy', $report->id) }}"
                                          method="POST"
                                          class="delete-form m-1"
                                          data-report-id="{{ $report->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>--}}
                                        

                                    <button class="btn btn-sm btn-info text-white m-1 btn-add-report-input" 
                                            data-id="{{ $report->id }}"
                                            data-role="{{ auth()->user()->role }}"
                                            data-input-url="{{ route('daily_reports.input', $report->id) }}"
                                            title="Add your Input"
                                            data-bs-toggle="modal"
                                            data-bs-target="#reportInputModal">
                                        <i class="fas fa-comment-medical"></i>
                                    </button>

                                    </div>
                                
                                    
                                </td>
                                @endif

                                <td data-order="{{ $report->report_date }} {{ $report->report_time }}">
                                    <strong>{{ \Carbon\Carbon::parse($report->report_date)->format('M d, Y') }}</strong><br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($report->report_time)->format('h:i A') }}</small>
                                </td>
                              
                                <td>{!! nl2br(e(Str::words($report->report, 40, '...'))) !!}
                                    <br>
                                    <a href="#" 
                                       class="text-primary fw-bold view-full-report" 
                                       data-report="{{ nl2br(e($report->report)) }}"
                                       style="cursor: pointer;">View More</a>
                                </td>

                                <td>{{ ucfirst($report->shift) }}</td>
                                <td>{{ ucfirst($report->hostel->name) }}</td>
                                <td>{{ $report->user->name ?? 'N/A' }}</td>
                                <td id="manager-{{ $report->id }}">{{ $report->manager_input ?? 'N/A' }}</td>
                                <td id="director-{{ $report->id }}">{{ $report->director_input ?? 'N/A' }}</td>
                            </tr>
                        @empty
                           <tr>
                                 @if (in_array(auth()->user()->role, ['director', 'manager']))
                            <td style="width: 160px;"></td>
                            @endif
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>

                            </tr>
                        @endforelse
                    </tbody>
                </table>
</div>