jQuery(document).ready(function ($) {
    $('#generate-pdf-button').on('click', function () {
        var postID = pdfButtonData.post_id;
        var nextStatus = $(this).data('next-status');

        if (nextStatus === 'F03') {
            $.post(pdfButtonData.ajax_url, {
                action: 'generate_pdf',
                post_id: postID
            }, function (response) {
                if (response.success) {
                    alert('PDF successfully generated and sent to the client!');
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Error: ' + response.data.message);
                }
            });
        } else {
            alert('This action is only allowed for transitioning to F03.');
        }
    });
});

// The code above is a JavaScript snippet that listens for a click event on the button with the ID  generate-pdf-button . When the button is clicked, it sends a POST request to the server with the action  generate_pdf  and the post ID of the current post. The server-side handler for this action will generate a PDF file and send it to the client.
// The server-side handler for this action is implemented in the PHP file  functions.php  of the theme. The handler will generate a PDF file based on the post content and send it to the client via email.