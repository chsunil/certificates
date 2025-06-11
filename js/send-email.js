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
                    post_id: postId,
                    nonce: wp_vars.get_client_email_nonce
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Data fetched:', data);

                    // Check if the response is successful
                    if (data.success && data.data) {
                        const clientData = data.data;
                        console.log('Client Data:', clientData);
                        // Fill the modal fields with the fetched data
                        document.getElementById('toEmail').value = clientData.contact_email;
                        document.getElementById('pdfAttachment').value = clientData.pdf_url;
                        document.getElementById('pdfFilename').innerText = clientData.pdf_filename; // Display the PDF filename
                        document.getElementById('clientname').innerText = clientData.client_name;  // Display the client name in modal header

                        // Initialize Froala WYSIWYG Editor for the message field
                        // new FroalaEditor('#message', {
                        //     theme: 'royal',  // You can customize this if needed
                        //     height: 250,
                        //     toolbarButtons: [
                        //         ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript'],
                        //         ['fontFamily', 'fontSize', 'textColor', 'backgroundColor'],
                        //         ['inlineClass', 'inlineStyle', 'clearFormatting']
                        //     ]
                        // });

                        // Open the modal
                        const myModal = new bootstrap.Modal(document.getElementById('sendEmailModal'));
                        myModal.show();
                    } else {
                        console.error('Error fetching email data:', data.message || 'No data received');
                        alert('Error fetching email data.');
                    }
                })
                .catch(error => {
                    console.error("Error fetching email data:", error);
                    alert('An error occurred while fetching email data.');
                });
        });
    });

    // Handle the Send Email Form submission
    document.getElementById('sendEmailForm').addEventListener('submit', function (e) {
        e.preventDefault();  // Prevent form submission

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
                nonce: wp_vars.send_pdf_email_nonce // Nonce for security
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Email sent successfully!");
                    // 1) Get the modal element & instance
                    const modalEl = document.getElementById('sendEmailModal');
                    let modal = bootstrap.Modal.getInstance(modalEl);
                    if (!modal) {
                        modal = new bootstrap.Modal(modalEl);
                    }

                    // 2) Hide and dispose of the modal
                    modal.hide();
                    modal.dispose();

                    // 3) Remove any leftover backdrops
                    document.querySelectorAll('.modal-backdrop')
                        .forEach(el => el.parentNode.removeChild(el));

                    // 4) Remove the Bootstrap “modal-open” class from <body>
                    document.body.classList.remove('modal-open');
                } else {
                    // First look in json.data.message, then json.message, then fallback
                    const err = (json.data && json.data.message)
                        || json.message
                        || 'Unknown error';
                    alert("Error: " + err);
                }
            })
            .catch(error => {
                console.error("Error sending email:", error);
                alert("An error occurred while sending the email.");
            });
    });
});
