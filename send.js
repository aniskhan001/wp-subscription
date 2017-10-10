/*
  Description: Send AJAX request with user info to the server
  Version: 2.0
 */

jQuery('#signup-form').on('submit',function(event) {
    event.preventDefault();

    let form = $('#signup-form');
    let formMessages = $('#form-messages');

    // Serialize the form data.
    let formData = $(form).serialize();

    // Submit the form using AJAX
    $.ajax({
		type: 'POST',
		url: $(form).attr('action'),
		data: formData
    })
    .done(function(response) {
		$(formMessages).removeClass('error');
		$(formMessages).addClass('success');

		$(formMessages).text(response);

		// Clear the form.
		$('#name').val('');
		$('#email').val('');
		$('#message').val('');
		
		// Close the modal after successful input
		setTimeout(function() {
			$('#subscribe-modal .close-button').click();
		}, 2000);
    })
    .fail(function(data) {
		$(formMessages).removeClass('success');
		$(formMessages).addClass('error');

		if (data.responseText !== '') {
			$(formMessages).text(data.responseText);
		} else {
			$(formMessages).text('Oops! An error occured and your message could not be sent.');
		}
    });

});