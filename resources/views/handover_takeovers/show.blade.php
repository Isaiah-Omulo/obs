@extends('layouts.default')

@section('title', 'View Changeover')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h3 class="mb-0">Changeover Details</h3>
            <small class="text-muted">Full record with items and takeovers</small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('takeover.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back
            </a>

            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="fa fa-print"></i> Print
            </button>
        </div>
    </div>

    @php
        // Determine parent and takeovers (children)
        $isChild = (bool) $handover->parent_id;
        $parent = $isChild ? ($handover->parent ?? $handover) : $handover;
        $takeovers = $isChild
            ? collect([$handover])
            : ($parent->children ?? collect());

        // Parent items collection
        $parentItems = collect($parent->items ?? []);
        // Build confirmation map: itemId => takeover info (first takeover that confirmed it)
        $itemConfirmation = [];
        foreach ($takeovers as $t) {
            foreach ($t->items ?? [] as $it) {
                if (!isset($itemConfirmation[$it->id])) {
                    $itemConfirmation[$it->id] = (object) [
                        'takeover_id' => $t->id,
                        'confirmed_by' => optional($t->actingUser)->name ?: 'N/A',
                        'confirmed_at' => $t->created_at ? $t->created_at->format('M d, Y, h:i A') : null,
                        'takeover_status' => $t->status,
                    ];
                }
            }
        }
    @endphp

    {{-- Parent Handover Card --}}
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-start">
            <div>
                <h5 class="mb-0">Handover (Parent)</h5>
                <small class="text-muted">
                    <strong>Type:</strong>
                    <span class="badge {{ $parent->changeover_type === 'take-over' ? 'bg-success' : 'bg-info' }}">
                        {{ ucwords(str_replace('-', ' ', $parent->changeover_type)) }}
                    </span>
                    &nbsp;&nbsp;
                    <strong>Status:</strong>
                    <span class="badge
                        {{ $parent->status === 'pending' ? 'bg-warning text-dark' : '' }}
                        {{ $parent->status === 'completed' ? 'bg-success' : '' }}
                        {{ $parent->status === 'rejected' ? 'bg-danger' : '' }}
                    ">
                        {{ ucfirst($parent->status) }}
                    </span>
                </small>
            </div>

            <div class="text-end">
                <div><strong>Hostel:</strong> {{ $parent->hostel->name ?? 'N/A' }}</div>
                <div><strong>Shift:</strong> {{ $parent->shift }}</div>
            </div>
        </div>

        <div class="card-body">
            <p class="mb-1"><strong>Handover By:</strong> {{ optional($parent->actingUser)->name ?? 'N/A' }}</p>
            <p class="mb-1"><strong>Involved User:</strong> {{ optional($parent->involvedUser)->name ?? 'N/A' }}</p>
            <p class="mb-2"><strong>Comments:</strong> {{ $parent->comments ?? '-' }}</p>

            <div class="mb-2">
                <h6 class="mb-1">Items left by {{ optional($parent->actingUser)->name ?? 'N/A' }}:</h6>
                @if($parentItems->isNotEmpty())
                    <ul class="list-unstyled mb-0">
                        @foreach($parentItems as $it)
                            <li>
                                <i class="fa fa-box me-1 text-muted"></i> {{ $it->name }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted">No items recorded for this handover.</div>
                @endif
            </div>

            <div class="mt-3 text-muted small">
                <div><strong>Done at:</strong> {{ $parent->created_at->format('M d, Y, h:i A') }}</div>
                <div><strong>Last updated:</strong> {{ $parent->updated_at->format('M d, Y, h:i A') }}</div>
            </div>
        </div>
    </div>

    {{-- Consolidated Items Table (left vs confirmed) --}}
    <div class="card mb-3">
        <div class="card-header">
            <strong>Items — Left vs Confirmed</strong>
            <span class="text-muted small ms-2">Shows which handover items were confirmed by which takeover (if any)</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 40%;">Item</th>
                            <th>Left by</th>
                            <th>Confirmed</th>
                            <th>Confirmed by (Takeover)</th>
                            <th>Confirmed at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($parentItems->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No items recorded.</td>
                            </tr>
                        @else
                            @foreach($parentItems as $it)
                                @php
                                    $conf = $itemConfirmation[$it->id] ?? null;
                                @endphp
                                <tr>
                                    <td>{{ $it->name }}</td>
                                    <td>{{ optional($parent->actingUser)->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($conf)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($conf)
                                            {{ $conf->confirmed_by }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($conf)
                                            {{ $conf->confirmed_at }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Takeover(s) Section --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Takeover(s)</strong>
            <span class="text-muted small">
                @if($takeovers->isEmpty())
                    No take-over recorded yet
                @else
                    {{ $takeovers->count() }} take-over(s)
                @endif
            </span>
        </div>

        <div class="card-body">
            @if($takeovers->isEmpty())
                <div class="alert alert-info mb-0">No take-over has been performed for this handover yet.</div>
            @else
                <div class="accordion" id="takeoversAccordion">
                    @foreach($takeovers as $tIndex => $t)
                        @php
                            $tItems = collect($t->items ?? []);
                        @endphp

                        <div class="accordion-item mb-2">
                            <h2 class="accordion-header" id="heading{{ $t->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $t->id }}" aria-expanded="false" aria-controls="collapse{{ $t->id }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <strong>Take-over #{{ $t->id }}</strong>
                                            <small class="text-muted"> by {{ optional($t->actingUser)->name ?? 'N/A' }}</small>
                                        </div>
                                        <div class="text-end">
                                            <div>
                                                <span class="badge {{ $t->status === 'completed' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($t->status) }}
                                                </span>
                                            </div>
                                            <div class="small text-muted">{{ $t->created_at->format('M d, Y, h:i A') }}</div>
                                        </div>
                                    </div>
                                </button>
                            </h2>

                            <div id="collapse{{ $t->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $t->id }}" data-bs-parent="#takeoversAccordion">
                                <div class="accordion-body">
                                    <p class="mb-1"><strong>Confirmed by:</strong> {{ optional($t->actingUser)->name ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Involved User:</strong> {{ optional($t->involvedUser)->name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Comments:</strong> {{ $t->comments ?? '-' }}</p>

                                    <div>
                                        <h6 class="mb-2">Items confirmed in this takeover:</h6>
                                        @if($tItems->isEmpty())
                                            <div class="text-muted">No items attached to this takeover.</div>
                                        @else
                                            <ul class="list-unstyled">
                                                @foreach($tItems as $ti)
                                                    <li><i class="fa fa-check-circle text-success me-1"></i> {{ $ti->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>

                                    <div class="mt-3 small text-muted">
                                        <div><strong>Created:</strong> {{ $t->created_at->format('M d, Y, h:i A') }}</div>
                                        <div><strong>Updated:</strong> {{ $t->updated_at->format('M d, Y, h:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div> {{-- end accordion --}}
            @endif
        </div>
    </div>

</div>
@endsection
