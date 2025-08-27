<div class="card shadow-sm mt-3">
    <div class="card-body">
        <button class="btn btn-outline-info mb-3" id="addInputBtn" data-bs-toggle="modal" data-bs-target="#inputModal">
            <i class="fas fa-comment-medical me-1"></i> Add Input
        </button>

        @if($occurrence->inputs->count())
            <div class="d-flex flex-column gap-3">
                @php
                    $currentUserRole = auth()->user()->role;

                    function renderModernInputs($inputs, $currentUserRole, $level = 0) {
                        foreach ($inputs as $input) {

                            // Visibility logic
                            if (in_array($currentUserRole, ['house_keeper', 'hostel_attendant']) 
                                && !in_array($input->role, ['house_keeper', 'hostel_attendant'])) {
                                continue;
                            }

                            $indent = $level * 25;

                            // Background color based on level
                            $bgColor = $level === 0 ? '#e3f2fd' : '#f8f9fa'; // parent = light blue, reply = light gray

                            // Role colors
                            $roleColors = [
                                'house_keeper' => '#28a745',
                                'hostel_attendant' => '#20c997',
                                'admin' => '#0d6efd',
                                'manager' => '#ffc107',
                            ];
                            $roleColor = $roleColors[$input->role] ?? '#6c757d';

                            echo '<div class="card border-0 shadow-sm" style="margin-left:' . $indent . 'px; border-radius:12px; background-color:' . $bgColor . ';">';
                                echo '<div class="card-body p-3 position-relative">';
                                    
                                    // Left colored bar
                                    echo '<div style="position:absolute; left:0; top:0; bottom:0; width:4px; background-color:' . $roleColor . '; border-top-left-radius:12px; border-bottom-left-radius:12px;"></div>';

                                    // Header
                                    echo '<div class="d-flex justify-content-between align-items-center mb-2 ps-3">';
                                        echo '<div class="d-flex align-items-center gap-2">';
                                            echo '<strong>' . e($input->user->name ?? "Unknown") . '</strong>';
                                            echo '<span class="badge" style="background-color:' . $roleColor . '; color:white;">' . e(ucfirst($input->role)) . '</span>';
                                        echo '</div>';
                                        echo '<small class="text-muted">' . $input->created_at->format('M d, Y g:i A') . '</small>';
                                    echo '</div>';

                                    // Input text
                                    echo '<div class="mb-2 ps-3">' . nl2br(e($input->input_text)) . '</div>';

                                    // Reply button
                                    echo '<div class="text-end pe-3">';
                                        echo '<button class="btn btn-sm btn-outline-secondary reply-btn" data-comment-id="' . $input->id . '" title="Reply">';
                                            echo '<i class="fas fa-reply me-1"></i> Reply';
                                        echo '</button>';
                                    echo '</div>';

                                    // Recursively render replies
                                    if ($input->replies && $input->replies->count()) {
                                        renderModernInputs($input->replies, $currentUserRole, $level + 1);
                                    }

                                echo '</div>'; // card-body
                            echo '</div>'; // card
                        }
                    }

                    $topInputs = $occurrence->inputs->whereNull('parent_id')->sortBy('created_at');
                    renderModernInputs($topInputs, $currentUserRole);
                @endphp
            </div>
        @else
            <p class="text-muted">No inputs yet.</p>
        @endif
    </div>
</div>

<style>
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.15) !important;
}
</style>
