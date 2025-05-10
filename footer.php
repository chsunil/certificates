<?php

/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

?>
<?php astra_content_bottom(); ?>
</div> <!-- ast-container -->
</div><!-- #content -->
<?php
astra_content_after();

astra_footer_before();

astra_footer();

astra_footer_after();
?>
</div><!-- #page -->
<!-- Send Email Modal (shared) -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="sendEmailForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Agreement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label>To</label>
                    <input id="toEmail" class="form-control" readonly>
                </div>
                <div class="mb-2">
                    <label>Subject</label>
                    <input id="subject" class="form-control" value="Your Certification Agreement">
                </div>
                <div class="mb-2">
                    <label>Message</label>
                    <textarea id="message" class="form-control" rows="4">Dear Client,</textarea>
                </div>
                <div class="mb-2">
                    <label>Attachment</label>
                    <p id="pdfFilename"></p>
                    <input type="hidden" id="pdfAttachment">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Send Email</button>
            </div>
        </form>
    </div>
</div>

<?php
astra_body_bottom();
wp_footer();
?>
</body>

</html>