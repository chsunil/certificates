document.addEventListener("DOMContentLoaded", function () {
    const generatePdfButtons = document.querySelectorAll('.generate-pdf');

    generatePdfButtons.forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.getAttribute('data-post-id');
            console.log('Generating PDF for Post ID:', postId);

            if (!postId) {
                console.error("Error: Post ID is missing or undefined.");
                return;
            }

            // Start the AJAX request to generate the PDF
            fetch(wp_vars.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'generate_pdf',
                    post_id: postId,
                    nonce: wp_vars.generate_pdf_nonce // Send nonce with request
                })
            })
                .then(response => response.json())
                .then(data => {
                    // console.log("Response Data:", data); // Log the response data

                    if (data.success) {
                        alert("PDF generated successfully!");
                    } else {
                        alert("Error generating PDF: " + data.message); // Display error message
                    }
                })
                .catch(error => {
                    console.error("Error:", error);  // Log any AJAX errors
                    alert("An error occurred while generating the PDF.");
                });
        });
    });
});
