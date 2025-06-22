<?php
/**
 * Partial: Send Email Modal
 *
 * Expects query var 'send_email_args' => [
 *   'post_id'       => int,
 *   'pdf_url'       => string,
 *   'contact_email' => string,
 *   'client_name'   => string,
 * ]
 */
$args          = get_query_var('send_email_args', []);
$post_id       = intval(   $args['post_id']       ?? 0 );
$pdf_url       = esc_url(  $args['pdf_url']       ?? '' );
$contact_email = sanitize_email( $args['contact_email'] ?? '' );
$client_name   = sanitize_text_field( $args['client_name'] ?? '' );
?>
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sendEmailModalLabel">
          Send PDF to Client <span class="text-primary"><?php echo $client_name; ?></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post">
          <?php wp_nonce_field('send_email_action','send_email_nonce'); ?>
          <input type="hidden" name="send_email" value="1">
          <div class="mb-3">
            <label class="form-label">To (Client’s Email)</label>
            <input type="email" name="to_email" class="form-control" required
                   value="<?php echo esc_attr( $contact_email ); ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" required
                   value="Your certificate is ready">
          </div>
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="4" required><?php
              echo "Hi {$client_name},\n\nPlease find your certificate attached.\n\nRegards,";
            ?></textarea>
          </div>
          <input type="hidden" name="pdf_attachment" value="<?php echo $pdf_url; ?>">
          <div class="mb-3">
            <label class="form-label">PDF Filename</label>
            <p><?php echo esc_html( basename( $pdf_url ) ); ?></p>
          </div>
          <button type="submit" class="btn btn-primary">Send Email</button>
        </form>
      </div>
    </div>
  </div>
</div>
