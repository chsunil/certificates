document.addEventListener("DOMContentLoaded", function () {
    // Open the Send Email Modal
    const sendEmailButtons = document.querySelectorAll('.send-email-btn');

    sendEmailButtons.forEach(sendEmailButton => {
        sendEmailButton.addEventListener('click', function () {
            const postId = this.getAttribute('data-post-id');  // Get the post ID from the button's data attribute
            console.log('Post ID:', postId);

            // Fetch ACF 'contact_person_contact_email' and PDF URL from the current client post
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
                        document.getElementById('toEmail').value = data.data.contact_email;

                        // Pass the PDF URL for attachment
                        document.getElementById('pdfAttachment').value = data.data.pdf_url;

                        // Display the PDF filename
                        document.getElementById('pdfFilename').innerText = data.data.pdf_filename;

                        // Initialize Froala WYSIWYG Editor for the message field

                        new FroalaEditor('#message', {
                            theme: 'royal',  // You can customize this if needed
                            height: 250,
                            toolbarButtons: [['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript'], ['fontFamily', 'fontSize', 'textColor', 'backgroundColor'], ['inlineClass', 'inlineStyle', 'clearFormatting']]
                        });

                        // Open modal using Bootstrap's Modal class (no jQuery)
                        const myModal = new bootstrap.Modal(document.getElementById('sendEmailModal'));
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

    // Handle the Send Email Form submission
    document.getElementById('sendEmailForm').addEventListener('submit', function (e) {
        e.preventDefault();  // Prevent form submission to handle it via AJAX

        const toEmail = document.getElementById('toEmail').value;
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;
        const pdfAttachment = document.getElementById('pdfAttachment').value;

        console.log('Sending email to:', toEmail);
        console.log('Subject:', subject);
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
                subject: subject,
                message: message,
                pdf_attachment: pdfAttachment,
                nonce: wp_vars.send_email_nonce // Nonce for security
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Email sent successfully!");
                    const myModal = bootstrap.Modal.getInstance(document.getElementById('sendEmailModal'));
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
