<?php
// attch file in child theme root directory
require_once get_stylesheet_directory() . '/tcpdf/tcpdf.php'; // Include TCPDF library

function generate_sample_pdf($post_id) {
    // Ensure it's a client post
    if (get_post_type($post_id) !== 'client') {
        error_log('Not a client post. Skipping PDF generation.');
        return false;
    }

    // Get the upload directory
    $upload_dir = wp_upload_dir();
    $pdf_dir = $upload_dir['basedir'] . '/client_pdfs';
    $pdf_url = $upload_dir['baseurl'] . '/client_pdfs';

    // Debug log: Ensure the directory exists or is created
    error_log('Checking PDF directory: ' . $pdf_dir);
    if (!file_exists($pdf_dir)) {
        error_log('Creating PDF directory: ' . $pdf_dir);
        wp_mkdir_p($pdf_dir);
    }

    // Generate filename with timestamp
    $timestamp = date('dM_Y');
    $pdf_file = $pdf_dir . "/client-{$post_id}-{$timestamp}.pdf";

    // Debug log: Check the generated file path
    error_log('Generated PDF file path: ' . $pdf_file);

    // Check if the PDF file already exists
    if (file_exists($pdf_file)) {
        error_log('PDF file already exists: ' . $pdf_file);
        return true;  // No need to regenerate
    }

    // Initialize TCPDF
    require_once get_stylesheet_directory() . '/tcpdf/tcpdf.php';
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 11);

    // Fetch client data from ACF fields
    $client_name = get_field('organization_name', $post_id);
    $client_address = get_field('address', $post_id);
    $contact_person = get_field('contact_person', $post_id);
    $contact_number = get_field('contact_number', $post_id);
    $cert_scope = get_field('certification_scope', $post_id);

    // Debug log: Log client data
    error_log('Fetched client data: ' . $client_name . ', ' . $client_address);

    // Prepare the content for the PDF
    $html = <<<HTML
<h2 style="text-align:center;">Client Certification Agreement</h2>
<p><strong>Client Name:</strong> {$client_name}</p>
<p><strong>Address:</strong> {$client_address}</p>
<p><strong>Contact Person:</strong> {$contact_person}</p>
<p><strong>Contact Number:</strong> {$contact_number}</p>
<p><strong>Scope of Certification:</strong> {$cert_scope}</p>
HTML;

    // Write the HTML content to the PDF
    $pdf->writeHTML($html);

    // Save the PDF to the server
    $pdf->Output($pdf_file, 'F');

    // Debug log: Confirm PDF was saved
    error_log('PDF saved successfully: ' . $pdf_file);

    // Update ACF field with the PDF URL
    update_field('generated_pdf_url', $pdf_url . "/client-{$post_id}-{$timestamp}.pdf", $post_id);

    // Debug log: Confirm PDF URL is updated in ACF
    error_log('Updated ACF with PDF URL: ' . $pdf_url . "/client-{$post_id}-{$timestamp}.pdf");

    return true;
}
