document.addEventListener("DOMContentLoaded", function () {
    // Open the Send Email Modal for each client
    const sendEmailButtons = document.querySelectorAll('.send-email-btn');

    sendEmailButtons.forEach(sendEmailButton => {
        sendEmailButton.addEventListener('click', function () {
            const postId = this.getAttribute('data-post-id');  // Get the post ID from the button's data attribute
            console.log('Post ID:', postId);

            // Fetch ACF 'contact_email' and PDF URL from the current client post
            fetch(wp_vars.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'get_client_email', // PHP action to fetch the contact_email
                    post_id: postId
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Data fetched:', data);
                    if (data.success) {
                        // Fill the 'To' field with the contact_email
                        document.getElementById('toEmail' + postId).value = data.contact_email;
                        // Fill the message field with some default text or empty
                        document.getElementById('message' + postId).value = "Dear Client, \n\nPlease find the attached certification agreement.";

                        // Pass the PDF URL for attachment
                        document.getElementById('pdfAttachment' + postId).value = data.pdf_url;

                        // Show the modal using Bootstrap's modal class (no jQuery)
                        const myModal = new bootstrap.Modal(document.getElementById('sendEmailModal' + postId));
                        myModal.show();
                    } else {
                        console.log('Error fetching email data:', data.message);
                    }
                })
                .catch(error => {
                    console.error("Error fetching email data:", error);
                });
        });
    });

    // Handle the Send Email Form submission for each client
    document.querySelectorAll('form[id^="sendEmailForm"]').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();  // Prevent form submission to handle it via AJAX

            const postId = this.id.replace('sendEmailForm', '');  // Get the post ID from the form ID
            const toEmail = document.getElementById('toEmail' + postId).value;
            const message = document.getElementById('message' + postId).value;
            const pdfAttachment = document.getElementById('pdfAttachment' + postId).value;

            console.log('Sending email to:', toEmail);
            console.log('Message:', message);
            console.log('PDF Attachment:', pdfAttachment);

            // Send the email via AJAX
            fetch(wp_vars.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'send_pdf_email', // The PHP action to send the email
                    to_email: toEmail,
                    message: message,
                    pdf_attachment: pdfAttachment,
                    nonce: wp_vars.send_email_nonce // Nonce for security
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Email sent successfully!");
                        const myModal = bootstrap.Modal.getInstance(document.getElementById('sendEmailModal' + postId));
                        myModal.hide();  // Close the modal
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error sending email:", error);
                });
        });
    });
});
