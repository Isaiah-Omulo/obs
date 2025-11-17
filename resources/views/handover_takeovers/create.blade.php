@extends('layouts.default')
@section('title', 'Change Over')



@section('content')
<div class="container-fluid mt-5" >
    <!-- begin row -->
    <div class="row d-flex align-items-center justify-content-center" style="min-height: 70vh;">
        <!-- begin col -->
        <div class="col-xl-6 col-lg-8">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Changeover Form</h4>
                    <div class="panel-heading-btn">
                        <a href="{{ route('takeover.index') }}" class="btn btn-sm btn-primary">All Changeovers</a>
                    </div>
                </div>

                <div class="panel-body">
                    @if(session('success'))
                        <div class="alert alert-success text-white bg-primary p-2 rounded">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger text-white bg-danger p-2 rounded">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                 

                    <form action="{{ route('takeover.store') }}" method="POST">
                        @csrf

                        <!-- Step 1 -->
                        <div class="mb-3 form-step" id="step-1">
                            <label for="hostel_id" class="form-label">Hostel</label>
                            <select name="hostel_id" id="hostel_id" class="form-control select2" required>
                                <option value="">Select Hostel</option>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Step 2 -->
                        <div class="mb-3 form-step" id="step-2" style="display:none;">
                            <label for="changeoverType" class="form-label">Changeover Type</label>
                            <select name="changeover_type" id="changeoverType" class="form-control select2" required>
                                <option value="">Select Changeover Type</option>
                                <option value="take-over">Taking Over</option>
                                <option value="hand-over">Handing Over</option>
                            </select>
                        </div>

                        <!-- Step 3 -->
                        <div class="form-group form-step" id="step-3" style="display:none;">
                            <label for="parent_id">Select Pending Handover</label>
                            <select name="parent_id" id="parent_id" class="form-control"></select>
                        </div>

                        


                        <!-- Step 4 -->
                        <div class="mb-3 form-step" id="step-4" style="display:none;">
                            <label for="user_id" id="userLabel" class="form-label">User</label>
                            <select name="user_id" id="user_id" class="form-control select2">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>



                        <!-- Step 5 -->
                        <div class="mb-3 form-step" id="step-5" style="display:none;">
                            <label class="form-label">Shift</label>
                            <select id="shift_display" class="form-control" readonly>
                                <option value="Day">Day</option>
                                <option value="Night">Night</option>
                            </select>
                            <input type="hidden" name="shift" id="shift">
                        </div>

                        <div id="user_name" style="display:none;" 
                                 class="mt-3 p-3 rounded bg-light border-start border-4 border-primary shadow-sm">
                                <!-- Dynamic content (user + comments + note) will be injected here -->
                        </div>


                        <div id="itemsContainer" style="display:none;" class="mt-3"></div>

                        <!-- Step 6 -->
                        <div class="mb-3 form-step" id="step-6" style="display:none;">
                            <label for="items" class="form-label">Items</label>
                            <select name="items[]" id="items" class="form-control select2" multiple>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>

                              <button type="button" class="btn btn-gradient btn-sm rounded-pill shadow-sm d-flex align-items-center gap-1" 
                                    id="addNewItemBtn" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                <i class="fa fa-plus-circle"></i> Add Item
                            </button>

                        </div>



                        <!-- Step 7 -->
                        <div class="mb-3 form-step" id="step-7" style="display:none;">
                            <label for="comments" class="form-label">Comments</label>
                            <textarea name="comments" class="form-control" rows="4" required></textarea>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-success" id="submitBtn" style="display:none;">Submit</button>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</div>


<!-- Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="addItemForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add New Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label for="item_name" class="form-label">Item Name</label>
          <input type="text" name="name" id="item_name" class="form-control" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Item</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection



@push('scripts')


<script>
    
    document.addEventListener('DOMContentLoaded', function () {
        const shiftDisplay = document.getElementById('shift_display');
        const shiftHidden = document.getElementById('shift');

        const nairobiTimeStr = new Date().toLocaleString("en-US", { timeZone: "Africa/Nairobi" });
        const nairobiDate = new Date(nairobiTimeStr);

        const hours = nairobiDate.getHours();
        const minutes = nairobiDate.getMinutes();
        const totalMinutes = hours * 60 + minutes;

        const dayStart = 6 * 60 + 59;
        const dayEnd = 18 * 60 + 59;

        const shift = (totalMinutes >= dayStart && totalMinutes <= dayEnd) ? 'Day' : 'Night';

        shiftDisplay.value = shift;
        shiftHidden.value = shift;
    });

</script>

<script>
    // Wait for the document to be fully loaded and ready
    document.addEventListener('DOMContentLoaded', function() {
        
        // Find the elements we need to work with by their IDs
        const changeoverSelect = document.getElementById('changeoverType');
        const userLabel = document.getElementById('userLabel');

        // Check if both elements were actually found before adding an event listener
        if (changeoverSelect && userLabel) {
            
            // Listen for any change in the "Changeover Type" dropdown
            changeoverSelect.addEventListener('change', function() {
                
                // Get the currently selected value (e.g., "take-over")
                const selectedValue = this.value;

                // Update the label text based on the selection
                if (selectedValue === 'take-over') {
                    userLabel.innerText = 'Taking Over from';
                } else if (selectedValue === 'hand-over') {
                    userLabel.innerText = 'Handing Over to';
                } else {
                    // Revert to the default text if nothing is selected
                    userLabel.innerText = 'User';
                }
            });
        }
    });




</script>






<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>


$(document).ready(function() {

   $(".select2").select2({ width: "100%" });


        function showNextStep(currentStepId, nextStepId, alsoShowSubmit = false, skipIfHandOver = false, skipIfTakeOver = false) {
    let $field = $(`#${currentStepId} select, #${currentStepId} textarea`);

    $field.on("change input", function () {
        if ($(this).val() && $(this).val() !== "") {

            // Special case: step-2 → step-3 (skip if hand-over)
            if (skipIfHandOver && $(this).attr("id") === "changeoverType") {
                if ($(this).val() === "hand-over") {
                    $("#step-3").hide();
                    $("#step-4").fadeIn();
                    return;
                }
            }

            // Special case: step-3 → step-4 (skip if take-over)
            if (skipIfTakeOver && currentStepId === "step-3") {
                let type = $("#changeoverType").val();
                if (type === "take-over") {
                    $("#step-4").hide();      // 🔴 Hide User step
                    $("#step-6").hide();    // ⏭ Go directly to Items
                    $("#step-7").fadeIn(); 
                      $("#submitBtn").fadeIn();
                    return;
                }
            }

            $(`#${nextStepId}`).fadeIn();

            if (alsoShowSubmit) {
                $("#submitBtn").fadeIn();
            }
        }
    });
}

// Steps setup
showNextStep("step-1", "step-2");                      // Hostel → Changeover
showNextStep("step-2", "step-3", false, true);         // Changeover → Handover (skip if hand-over)
showNextStep("step-3", "step-4", false, false, true);  // Handover → User (skip if take-over ✅)
showNextStep("step-4", "step-6");                      // User → Items
showNextStep("step-6", "step-7", true);                // Items → Comments + Submit



    // Handle modal select2 dropdown properly
    $('#addItemModal').on('shown.bs.modal', function () {
        $(this).find('.select2').select2({
            dropdownParent: $('#addItemModal'),
            width: '100%'
        });
    });

    // Open modal
    $("#addNewItemBtn").on("click", function() {
        $("#addItemModal").modal("show");
    });

    // Handle Add Item form
    $("#addItemForm").on("submit", function(e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('takeover.items.store') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    let newOption = new Option(response.item.name, response.item.id, true, true);
                    $("#items").append(newOption).trigger("change");

                    $("#addItemModal").modal("hide");
                    $("#item_name").val("");
                }
            },
            error: function() {
                alert("Error saving item. Try again.");
            }
        });
    });
});

     function loadPendingHandovers() {
        let changeoverType = $("#changeoverType").val();
        if (changeoverType === "take-over") {
            $("#step-4").hide();  

            let hostelId = $("#hostel_id").val();
            let shift = $("#shift").val();
            

            console.log("Selected hostelId:", hostelId);
            console.log("Selected shift:", shift);

            if (hostelId) {
                let url = "{{ route('takeover.handover.pending', [':hostel', ':shift']) }}";
                url = url.replace(":hostel", hostelId).replace(":shift", shift || ""); // fallback empty shift

                console.log("Requesting URL:", url);

                $.get(url, function (data) {
                    console.log("Data received:", data);

                    let options = '<option value="">-- Select Handover --</option>';
                    if (data.length > 0) {
                        data.forEach(h => {
                           options += `<option value="${h.id}" data-user="${h.user_id}">${h.label}</option>`;

                        });
                    } else {
                        options += '<option value="" disabled>No pending handovers available</option>';
                    }
                    $("#parent_id").html(options);
                }).fail(function (xhr, status, error) {
                    console.error("AJAX error:", status, error);
                });
            }
        } else {
            
        }
    }




     $("#hostel_id").on("change", function () {

        let changingOverType = $('#changeoverType').val();
        let hostelID = $('#hostel_id').val();

        console.log("Changing over: "+changingOverType);
        loadPendingHandovers();
    });

     


</script>

<script>
$('#changeoverType').on('change', function() {
    let type = $(this).val();

    // Reset the second select (#user_id) WITHOUT removing Blade users
    $("#user_id").val('').prop("disabled", false).trigger('change');
    // Remove dynamically added options that start with "User #"
    $("#user_id option").filter(function() {
        return $(this).text().startsWith("User #");
    }).remove();
    let step6 = $('#step-6');
    if (type === 'take-over') {
        $("#step-4").hide();
        step6.hide();
        let hostelId = $('#hostel_id').val();
        let shift = $('#shift').val();

        if (hostelId) {
            let url = "{{ route('takeover.handover.pending', [':hostel', ':shift']) }}";
            url = url.replace(':hostel', hostelId).replace(':shift', shift);

            $.get(url, function(data) {
                let options = '<option value="">-- Select Handover --</option>';
                data.forEach(h => {
                    options += `<option value="${h.id}" data-user="${h.user_id}">${h.label}</option>`;
                });
                $('#parent_id').html(options);
            });
        }
    } else {
        $("#itemsContainer").hide();
        $("#user_name").hide();
         step6.show();
    }
});


$("#parent_id").on("change", function() {
    let handoverId = $(this).val(); // selected handover ID
    let userId = $(this).find(":selected").data("user"); // corresponding user ID

    // --- Handle #user_id select ---
    if (userId) {
        // Remove any existing dynamic "User #" options to avoid duplicates
        $("#user_id option").filter(function() {
            return $(this).text().startsWith("User #");
        }).remove();

        // Add the user option if it doesn't exist
        if ($("#user_id option[value='" + userId + "']").length === 0) {
            $("#user_id").append(new Option("User #" + userId, userId, true, true));
        }

        // Set the value and refresh Select2 UI
        $("#user_id").val(userId).prop("disabled", false).trigger("change");
        console.log("Auto-assigned user id:", userId);
    } else {
        // Reset select if no user
        $("#user_id").val("").prop("disabled", false).trigger("change");
        console.log("No user assigned for this handover");
    }

    // --- Handle items container ---
    if (handoverId) {
        let url = "{{ route('takeover.handover.pending.items', ':id') }}".replace(":id", handoverId);
        console.log("Fetching items for handover:", handoverId);

        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                let itemsContainer = $("#itemsContainer");
                let userNameDisplay = $("#user_name");

                if (response.items && response.items.length > 0) {
                    // Organized display for user + comments
                    let leftByHtml = `
                        <div class="handover-info p-2 mb-2 border-start border-4 border-primary rounded">
                        ${response.comments ? `
                                <div class="handover-comments mt-1">
                                    <strong>${response.user_name} Comments:</strong> <em class="text-muted">${response.comments}</em>
                                </div>
                            ` : ''}
                            <strong>Items left by:</strong> <span class="text-primary">${response.user_name}</span>
                            
                            <div class="confirmation-note mt-2 text-success fw-semibold">
                                ✅ Please confirm the items below
                            </div>
                        </div>

                            
                    `;
                    userNameDisplay.html(leftByHtml).show();

                     // Label for checkboxes
                    let checkboxesLabel = `<div class="mb-2 fw-semibold text-dark">Select items to confirm:</div>`;

                    // Generate checkboxes
                    let checkboxesHtml = response.items.map(item => `
                        <div class="form-check mb-1">
                            <input type="checkbox" name="confirmed_items[]" value="${item.id}" class="form-check-input" id="item_${item.id}">
                            <label class="form-check-label" for="item_${item.id}">${item.name}</label>
                        </div>
                    `).join("");

                    // Combine label + checkboxes
                    itemsContainer.html(checkboxesLabel + checkboxesHtml).show();
                } else {
                    itemsContainer.html("<p>No items found.</p>").show();
                    userNameDisplay.hide();
                }

            },
            error: function() {
                $("#itemsContainer").html("<p class='text-danger'>Error loading items.</p>").show();
                $("#user_name").hide();
            }
        });
    } else {
        // No handover selected → hide and clear items
        $("#itemsContainer").hide().empty();
        $("#user_name").hide();
    }
});


</script>





@endpush


