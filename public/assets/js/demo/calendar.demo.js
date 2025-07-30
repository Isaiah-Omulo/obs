
var handleCalendarDemo = function () {



	// Initialize FullCalendar
	var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        height: 550,
        headerToolbar: {
            left: 'dayGridMonth,timeGridWeek,timeGridDay',
            center: 'title',
            right: 'prev,next today'
        },
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day'
        },
        initialView: 'dayGridMonth',
        editable: false,
        droppable: false,
        themeSystem: 'bootstrap',
        eventTimeFormat: false,
        events: `${window.AppData.baseUrl}/cases/calendar/calendar_events`, // URL for fetching events dynamically

        // Event click handler
        eventClick: function(info) {


            let eventId = info.event.id; // Use the event ID to fetch details


			let parts = eventId.split(".");
			
			eventId = parts[0];



           if (parts[1]==='activity' ) {
			$('#viewActivityModal').modal('show');

            // Display loading spinner
            $('#activity-content').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading details...</div>');

				$.ajax({
					url: `${window.AppData.baseUrl}/cases/case-activity/${eventId}`, // Adjust this to your route to fetch the event details
					type: "GET",
					success: function (response) {
						let activity = response.data;
						let case_name = response.case_name;
						let case_number = response.case_number;
	
						const capType = activity.type.charAt(0).toUpperCase() + activity.type.slice(1);
	
						let content = `
						<div class="row">
							<div class="col-md-6">
							<p><strong>Case Number:</strong> ${case_number}</p>
							<p><strong>Case Name:</strong> ${case_name}</p>
							<p><strong>Type & Sequence:</strong> ${activity.seq_num}<sup>${activity.seq_suffix}</sup> ${capType}</p>
							<p><strong>Court Room #:</strong> ${activity.court_room_number}</p>
							<p><strong>Court Name:</strong> ${activity.court_name}</p>
							<p><strong>Date:</strong> ${activity.formatted_date}</p>
							<p><strong>Time:</strong> ${activity.formatted_time}</p>
							</div>
							<div class="col-md-6">
							<p><strong>Mode:</strong> ${activity.hearing_type}</p>
							<p><strong>Virtual Link:</strong> ${activity.virtual_link ? `<a href="${activity.virtual_link}" target="_blank">Join</a>` : 'N/A'}</p>
							<p><strong>Court Contacts:</strong> ${activity.court_contacts || 'N/A'}</p>
							<p><strong>Full Texts:</strong> ${activity.full_texts || 'N/A'}</p>
							<p><strong>Created At:</strong> ${activity.created_at}</p>
							<p><strong>Updated At:</strong> ${activity.updated_at}</p>
							</div>
						</div>
						`;
	
						// Set the modal title
						$('#viewActivityModalLabel').text(capType + " Details");
	
						// Populate the modal content with the event details
						$('#activity-content').html(content);
					},
					error: function () {
						$('#activity-content').html('<div class="text-danger text-center">Error loading activity details.</div>');
					}
				});
			}
            // Show the modal
            
            // Fetch event details using AJAX
            
//Fetching for Appeal
			else if(parts[1]==='appeal'){
				$('#viewAppealModal').modal('show');
				let appealId = eventId;
				// Display a loading indicator
				$('#appeal-content').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading details...</div>');
				$.ajax({
					url:`${window.AppData.baseUrl}/appeals/show/${appealId}`,
					type: "GET",
					success: function (response) {
						let appeal = response.appeal;
						let attachments = response.attachments;
						let case_name = response.case_name;
	
						let content = `
							<div class="row">
								<div class="col-md-6">
								  <strong>Case Name:</strong> ${case_name ?? 'N/A'}<br>
									<strong>Next Hearing Date:</strong> ${appeal.next_hearing_date ?? 'N/A'}<br>
									 <strong>Next Hearing Time:</strong> ${appeal.next_hearing_time ?? 'N/A'}<br>
									<strong>Appeal Comments:</strong> <p>${appeal.appeal_comments ?? 'N/A'}</p>
								</div>
								<div class="col-md-6">
									<strong>Created At:</strong> ${appeal.created_at ?? 'N/A'}<br>
									<strong>Updated At:</strong> ${appeal.updated_at ?? 'N/A'}<br>
								</div>
							</div>
							<hr>
							<h5>Attachments</h5>
							<ul class="list-group">
						`;
	
						if (attachments.length > 0) {
							attachments.forEach(file => {
								content += `
									<li class="list-group-item d-flex justify-content-between align-items-center">
										<a href="${file.file_path}" target="_blank">${file.file_name}</a>
										<span class="badge bg-primary">${file.file_type ?? 'Unknown'}</span>
									</li>
								`;
							});
						} else {
							content += `<li class="list-group-item text-muted">No attachments found.</li>`;
						}
	
						content += `</ul>`;
	
						$('#appeal-content').html(content);
					},
					error: function () {
						$('#appeal-content').html('<div class="text-danger text-center">Error loading appeal details.</div>');
					}
				});
			}

			//Fetching for adjoin
			else if(parts[1]==='adjourn'){

				let adjournId = eventId;
				// Show the modal
				$('#viewAdjournModal').modal('show');

				// Display a loading indicator
				$('#adjourn-content').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading details...</div>');
	
				// Fetch adjourn details
				$.ajax({
					url:  `${window.AppData.baseUrl}/adjourns/show/${adjournId}`,
					type: "GET",
					success: function (response) {
						let adjourn = response.adjourn;
						let attachments = response.attachments;
						let case_name = response.case_name;
	
						let content = `
							<div class="row">
								<div class="col-md-6">
								  <strong>Case Name:</strong> ${case_name ?? 'N/A'}<br>
									<strong>Next Hearing Date:</strong> ${adjourn.next_hearing_date ?? 'N/A'}<br>
									 <strong>Next Hearing Time:</strong> ${adjourn.next_hearing_time ?? 'N/A'}<br>
									<strong>Adjourn Comments:</strong> <p>${adjourn.adjourn_comments ?? 'N/A'}</p>
								</div>
								<div class="col-md-6">
									<strong>Created At:</strong> ${adjourn.created_at ?? 'N/A'}<br>
									<strong>Updated At:</strong> ${adjourn.updated_at ?? 'N/A'}<br>
								</div>
							</div>
							<hr>
							<h5>Attachments</h5>
							<ul class="list-group">
						`;
	
						if (attachments.length > 0) {
							attachments.forEach(file => {
								content += `
									<li class="list-group-item d-flex justify-content-between align-items-center">
										<a href="${file.file_path}" target="_blank">${file.file_name}</a>
										<span class="badge bg-primary">${file.file_type ?? 'Unknown'}</span>
									</li>
								`;
							});
						} else {
							content += `<li class="list-group-item text-muted">No attachments found.</li>`;
						}
	
						content += `</ul>`;
	
						$('#adjourn-content').html(content);
					},
					error: function () {
						$('#adjourn-content').html('<div class="text-danger text-center">Error loading adjourn details.</div>');
					}
				});
			}

				else if (parts[1] === 'pretrial') {
				    $('#viewPreTrialModal').modal('show');
				    $('#pretrial-content').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading details...</div>');

				    $.ajax({
				        url: `${window.AppData.baseUrl}/pretrials/cases/pretrial/${eventId}`,
				        type: "GET",
				        success: function (response) {
				            const pretrial = response.pretrial;
				            const caseName = response.case_name;
				            const caseNumber = response.case_number;

				            let membersList = '';
				            if (pretrial.members && pretrial.members.length > 0) {
				                pretrial.members.forEach((m) => {
				                    membersList += `<li>${m.role_or_position}: ${m.name}</li>`;
				                });
				            } else {
				                membersList = '<li>No members listed</li>';
				            }

				            let attachmentsList = '';
				            if (pretrial.attachments && pretrial.attachments.length > 0) {
				                pretrial.attachments.forEach((a) => {
				                    attachmentsList += `<li><a href="${a.url}" target="_blank">${a.name}</a></li>`;
				                });
				            } else {
				                attachmentsList = '<li>No attachments</li>';
				            }

				            let content = `
				                <div class="row">
				                    <div class="col-md-6">
				                        <p><strong>Case Number:</strong> ${caseNumber}</p>
				                        <p><strong>Case Name:</strong> ${caseName}</p>
				                        <p><strong>Date:</strong> ${pretrial.pretrial_date}</p>
				                        <p><strong>Time:</strong> ${pretrial.pretrial_time}</p>
				                        <p><strong>Location:</strong> ${pretrial.location}</p>
				                    </div>
				                    <div class="col-md-6">
				                        <p><strong>Comments:</strong> ${pretrial.comments || 'N/A'}</p>
				                        <p><strong>Created At:</strong> ${pretrial.created_at}</p>
				                        <p><strong>Updated At:</strong> ${pretrial.updated_at}</p>
				                    </div>
				                </div>
				                <hr>
				                <h6>Pre-Trial Members</h6>
				                <ul>${membersList}</ul>
				                <h6>Attachments</h6>
				                <ul>${attachmentsList}</ul>
				            `;

				            $('#viewPreTrialModalLabel').text("Pre-Trial Details");
				            $('#pretrial-content').html(content);
				        },
				        error: function () {
				            $('#pretrial-content').html('<div class="text-danger text-center">Error loading Pre-Trial details.</div>');
				        }
				    });
				}



			else if (parts[1]==='preparation') {
				let preparationId = eventId;
				$('#viewPreparationModal').modal('show');

				// Display a loading indicator
				$('#preparation-content').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading details...</div>');
	
				// Fetch preparation details
				$.ajax({
					url: `${window.AppData.baseUrl}/preparations/show/${preparationId}`,
					type: "GET",
					success: function (response) {
						let preparation = response.preparation;
						let attachments = response.attachments;
						let case_name = response.case_name;
	
						let content = `
						<div class="row">
							<div class="col-md-6">
								
								<strong>Case Name:</strong> ${case_name ?? 'N/A'}<br>
								<strong>Preparation Date:</strong> ${preparation.preparation_date ?? 'N/A'}<br>
								<strong>Preparation Time:</strong> ${preparation.preparation_time ?? 'N/A'}<br>
							</div>
							<div class="col-md-6">
								<strong>Status:</strong> ${preparation.preparation_status ?? 'N/A'}<br>
								<strong>Briefing Notes:</strong> <p>${preparation.briefing_notes ?? 'N/A'}</p>
								<strong>Created At:</strong> ${preparation.created_at ?? 'N/A'}<br>
								<strong>Updated At:</strong> ${preparation.updated_at ?? 'N/A'}<br>
							</div>
						</div>
						<hr>
						<h5>Attachments</h5>
						<ul class="list-group">
					`;
	
	
						if (attachments.length > 0) {
							attachments.forEach(file => {
								content += `
									<li class="list-group-item d-flex justify-content-between align-items-center">
										<a href="${file.file_path}" target="_blank">${file.file_name}</a>
										<span class="badge bg-primary">${file.file_type ?? 'Unknown'}</span>
									</li>
								`;
							});
						} else {
							content += `<li class="list-group-item text-muted">No attachments found.</li>`;
						}
	
						content += `</ul>`;
	
						$('#preparation-content').html(content);
					},
					error: function () {
						$('#preparation-content').html('<div class="text-danger text-center">Error loading preparation details.</div>');
					}
				});
			}			
			else if (parts[1]==='trial') {

			let trialId = eventId;
			$('#viewTrialModal').modal('show');

            // Display a loading indicator
            $('#trial-content').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading details...</div>');

            // Fetch trial details
            $.ajax({
                url: `${window.AppData.baseUrl}/trials/show/${trialId}`,
                type: "GET",
                success: function (response) {
                    let trial = response.trial;
                    let attachments = response.attachments;
                    let case_name = response.case_name;

                    let content = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Case Name:</strong> ${case_name ?? 'N/A'}<br>
                            <strong>Trial Date:</strong> ${trial.trial_date ?? 'N/A'}<br>
                             <strong>Trial Time:</strong> ${trial.trial_time ?? 'N/A'}<br>
                            <strong>Judgement Details:</strong> <p>${trial.judgement_details ?? 'N/A'}</p>
                            <strong>Judgement Date:</strong> ${trial.judgement_date ?? 'N/A'}<br>
                             <strong>Judgement Time:</strong> ${trial.judgement_time ?? 'N/A'}<br>
                        </div>
                        <div class="col-md-6">
                            <strong>Outcome:</strong> ${trial.outcome ?? 'N/A'}<br>
                            <strong>Created At:</strong> ${trial.created_at ?? 'N/A'}<br>
                            <strong>Updated At:</strong> ${trial.updated_at ?? 'N/A'}<br>
                        </div>
                    </div>

                    <hr>
                    <h5>Attachments</h5>
                    <ul class="list-group">
                `;


                    if (attachments.length > 0) {
                        attachments.forEach(file => {
                            content += `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="${file.file_path}" target="_blank">${file.file_name}</a>
                                    <span class="badge bg-primary">${file.file_type ?? 'Unknown'}</span>
                                </li>
                            `;
                        });
                    } else {
                        content += `<li class="list-group-item text-muted">No attachments found.</li>`;
                    }

                    content += `</ul>`;

                    $('#trial-content').html(content);
                },
                error: function () {
                    $('#trial-content').html('<div class="text-danger text-center">Error loading trial details.</div>');
                }
            });

			}

			
       
		
	 		else if (parts[1]==='appointment') {
				
			let forwarding_id = eventId;
				
            // Show the modal
            $('#viewAppointmentModal').modal('show');

            // Display a loading indicator
            $('#appointment-content').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading details...</div>');

            // Fetch appointment details
            $.ajax({
                url: `${window.AppData.baseUrl}/dvc_appointments/show/${forwarding_id}`,
                type: "GET",
                success: function (response) {
                    let appointment = response.forwarding;
                    let case_name = response.case_name;

                    let content = `
                        <div class="row">
                            <div class="col-md-6">
                              <strong>Case Name:</strong> ${case_name ?? 'N/A'}<br>
                                <strong>Appointment Date:</strong> ${appointment.dvc_appointment_date ?? 'N/A'}<br>
                                 <strong>Appointment Date:</strong> ${appointment.dvc_appointment_time ?? 'N/A'}<br>
                                <strong>Appointment Comments:</strong> <p>${appointment.briefing_notes ?? 'N/A'}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Created At:</strong> ${appointment.created_at ?? 'N/A'}<br>
                                <strong>Updated At:</strong> ${appointment.updated_at ?? 'N/A'}<br>
                            </div>
                        </div>
                        <hr>
                      
                    `;

                    

                    content += `</ul>`;

                    $('#appointment-content').html(content);
                },
                error: function () {
                    $('#appointment-content').html('<div class="text-danger text-center">Error loading appointment details.</div>');
                }
            });

			}
		
		

			else if (parts[1]==='advice') {
				let  ag_advice_id = eventId;
				  // Show the modal
				  $('#viewAdviceModal').modal('show');

				  // Display a loading indicator
				  $('#advice-content').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading details...</div>');
	  
				  // Fetch advice details
				  $.ajax({
					  url: `${window.AppData.baseUrl}/ag_advice/show/${ag_advice_id}`,
					  type: "GET",
					  success: function (response) {
						  let advice = response.advice;
						  let case_name = response.case_name;
	  
						  let content = `
							  <div class="row">
								  <div class="col-md-6">
									<strong>Case Name:</strong> ${case_name ?? 'N/A'}<br>
									  <strong>Advice Date:</strong> ${advice.advice_date ?? 'N/A'}<br>
									  <strong>Advice Time:</strong> ${advice.advice_time ?? 'N/A'}<br>
									  <strong>Advice Comments:</strong> <p>${advice.ag_advice ?? 'N/A'}</p>
								  </div>
								  <div class="col-md-6">
									  <strong>Created At:</strong> ${advice.created_at ?? 'N/A'}<br>
									  <strong>Updated At:</strong> ${advice.updated_at ?? 'N/A'}<br>
								  </div>
							  </div>
							  <hr>
							
						  `;
	  
						  
	  
						  content += `</ul>`;
	  
						  $('#advice-content').html(content);
					  },
					  error: function () {
						  $('#advice-content').html('<div class="text-danger text-center">Error loading advice details.</div>');
					  }
				  });
			}

			else if (parts[1]==='allpayment') {
				let paymentId = eventId;

				$('#viewPaymentModal').modal('show');

				// Display a loading indicator
				$('#payment-content').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading details...</div>');
	
				// Fetch payment details
				$.ajax({
					url: `${window.AppData.baseUrl}/all_payments/show/${paymentId}`,
					type: "GET",
					success: function (response) {
						let payment = response.payment;
						let attachments = response.attachments;
						let case_name = response.case_name;
	
						let content = `
							<div class="row">
								<div class="col-md-6">
									<strong>Case Name:</strong> ${case_name ?? 'N/A'}<br>
									<strong>Payment Date:</strong> ${payment.payment_date ?? 'N/A'}<br>
									<strong>Payment Date:</strong> ${payment.payment_time ?? 'N/A'}<br>
									<strong>Amount Paid:</strong> ${payment.amount_paid ?? 'N/A'}<br>
									<strong>Payment Method:</strong> ${payment.payment_method ?? 'N/A'}<br>
									<strong>Transaction ID:</strong> ${payment.transaction ?? 'N/A'}<br>
								</div>
								<div class="col-md-6">
									<strong>Created At:</strong> ${payment.created_at ?? 'N/A'}<br>
									<strong>Updated At:</strong> ${payment.updated_at ?? 'N/A'}<br>
									<strong>Auctioneer Involvement:</strong> <p>${payment.auctioneer_involvement ?? 'N/A'}</p>
								</div>
							</div>
							<hr>
							<h5>Attachments</h5>
							<ul class="list-group">
						`;
	
	
						if (attachments.length > 0) {
							attachments.forEach(file => {
								content += `
									<li class="list-group-item d-flex justify-content-between align-items-center">
										<a href="${file.file_path}" target="_blank">${file.file_name}</a>
										<span class="badge bg-primary">${file.file_type ?? 'Unknown'}</span>
									</li>
								`;
							});
						} else {
							content += `<li class="list-group-item text-muted">No attachments found.</li>`;
						}
	
						content += `</ul>`;
	
						$('#payment-content').html(content);
					},
					error: function () {
						$('#payment-content').html('<div class="text-danger text-center">Error loading payment details.</div>');
					}
				});
			}
			else if (parts[1]==='lawyerpayment') {
					let paymentId = eventId;
					// Show the modal
					$('#viewPaymentModal').modal('show');

					// Display a loading indicator
					$('#payment-content').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading details...</div>');

					// Fetch payment details
					$.ajax({
						url: `${window.AppData.baseUrl}/lawyer_payments/show/${paymentId}`,
						type: "GET",
						success: function (response) {
							let payment = response.payment;
							let attachments = response.attachments;
							let case_name = response.case_name;
							let lawyer = response.lawyer;
		
									
												let content = `
								<div class="row">
									<div class="col-md-6">
										<strong>Case Name:</strong> ${case_name ?? 'N/A'}<br>
										<strong>Lawyer: </strong> ${lawyer ?? 'N/A'}<br>
										<strong>Payment Date:</strong> ${payment.payment_date ?? 'N/A'}<br>
										<strong>Payment Time:</strong> ${payment.payment_time ?? 'N/A'}<br>
										<strong>Amount Paid:</strong> ${payment.amount_paid ?? 'N/A'}<br>
										<strong>Payment Method:</strong> ${payment.payment_method ?? 'N/A'}<br>
										
										<strong>Payment :</strong> ${payment.lawyer_payment_status ?? 'N/A'}<br>
									</div>
									<div class="col-md-6">
										<strong>Created At:</strong> ${payment.created_at ?? 'N/A'}<br>
										<strong>Updated At:</strong> ${payment.updated_at ?? 'N/A'}<br>
										<strong>Transaction Details:</strong> <p>${payment.transaction ?? 'N/A'}</p>
									</div>
								</div>
								<hr>
								<h5>Attachments</h5>
								<ul class="list-group">
							`;


							if (attachments.length > 0) {
								attachments.forEach(file => {
									content += `
										<li class="list-group-item d-flex justify-content-between align-items-center">
											<a href="${file.file_path}" target="_blank">${file.file_name}</a>
											<span class="badge bg-primary">${file.file_type ?? 'Unknown'}</span>
										</li>
									`;
								});
							} else {
								content += `<li class="list-group-item text-muted">No attachments found.</li>`;
							}

							content += `</ul>`;

							$('#payment-content').html(content);
						},
						error: function () {
							$('#payment-content').html('<div class="text-danger text-center">Error loading payment details.</div>');
						}
					});
			}

			else if (parts[1]==='negotiate') {
				let negotiationId = eventId; // Replace this with the actual negotiation ID dynamically if necessary

    // Redirect to the "negotiations/show/{id}" URL
    		window.location.href = `${window.AppData.baseUrl}/negotiations/show/${negotiationId}`;
			}
		
		}


		

		
    });

    calendar.render();
};
  
  // Initialize on document ready
  var Calendar = function () {
	"use strict";
	return {
	  init: function () {
		handleCalendarDemo();
	  }
	};
  }();
  
  $(document).ready(function () {
	Calendar.init();
  });
  
/*
var handleCalendarDemo = function() {
	// external events
	var containerEl = document.getElementById('external-events');
  var Draggable = FullCalendar.Interaction.Draggable;
	new Draggable(containerEl, {
    itemSelector: '.fc-event',
    eventData: function(eventEl) {
      return {
        title: eventEl.innerText,
        color: eventEl.getAttribute('data-color')
      };
    }
  });
  
  // fullcalendar
  
  var d = new Date();
	var month = d.getMonth() + 1;
	    month = (month < 10) ? '0' + month : month;
	var year = d.getFullYear();
	var day = d.getDate();
	var today = moment().startOf('day');
  var calendarElm = document.getElementById('calendar');
	var calendar = new FullCalendar.Calendar(calendarElm, {
    headerToolbar: {
      left: 'dayGridMonth,timeGridWeek,timeGridDay',
      center: 'title',
      right: 'prev,next today'
    },
    buttonText: {
    	today:    'Today',
			month:    'Month',
			week:     'Week',
			day:      'Day'
    },
    initialView: 'dayGridMonth',
    editable: true,
    droppable: true,
  	themeSystem: 'bootstrap',
  	events: [{
			title: 'Trip to Kenya',
			start: year + '-'+ month +'-01',
			end: year + '-'+ month +'-05',
			color: app.color.success
		},{
			title: 'Meet with Irene Wong',
			start: year + '-'+ month +'-02T06:00:00',
			color: app.color.blue
		},{
			title: 'Mobile Apps Brainstorming: uurj',
			start: year + '-'+ month +'-10',
			end: year + '-'+ month +'-12',
			color: app.color.pink
		},{
			title: 'Stonehenge, Windsor Castle, Oxford',
			start: year + '-'+ month +'-05T08:45:00',
			end: year + '-'+ month +'-06T18:00',
			color: app.color.indigo
		},{
			title: 'Paris Trip. Good',
			start: year + '-'+ month +'-12',
			end: year + '-'+ month +'-16'
		},{
			title: 'Domain name due. Really',
			start: year + '-'+ month +'-15',
			color: app.color.blue
		},{
			title: 'Cambridge Trip',
			start: year + '-'+ month +'-19'
		},{
			title: 'Visit Apple Company',
			start: year + '-'+ month +'-22T05:00:00',
			color: app.color.success
		},{
			title: 'Exercise Class',
			start: year + '-'+ month +'-22T07:30:00',
			color: app.color.orange
		},{
			title: 'Live Recording',
			start: year + '-'+ month +'-22T03:00:00',
			color: app.color.blue
		},{
			title: 'Announcement',
			start: year + '-'+ month +'-22T15:00:00',
			color: app.color.red
		},{
			title: 'Dinner',
			start: year + '-'+ month +'-22T18:00:00'
		},{
			title: 'New Android App Discussion',
			start: year + '-'+ month +'-25T08:00:00',
			end: year + '-'+ month +'-25T10:00:00',
			color: app.color.red
		},{
			title: 'Marketing Plan Presentation',
			start: year + '-'+ month +'-25T12:00:00',
			end: year + '-'+ month +'-25T14:00:00',
			color: app.color.blue
		},{
			title: 'Chase due',
			start: year + '-'+ month +'-26T12:00:00',
			color: app.color.orange
		},{
			title: 'Heartguard',
			start: year + '-'+ month +'-26T08:00:00',
			color: app.color.orange
		},{
			title: 'Lunch with Richard',
			start: year + '-'+ month +'-28T14:00:00',
			color: app.color.blue
		},{
			title: 'Web Hosting due. Really',
			start: year + '-'+ month +'-30',
			color: app.color.blue
		}]
  });
  
	calendar.render();
};

var Calendar = function () {
	"use strict";
	return {
		//main function
		init: function () {
			handleCalendarDemo();
		}
	};
}();

$(document).ready(function() {
	Calendar.init();
});

*/