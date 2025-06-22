<?php

/**
 * /template-parts/client/stage-generic.php
 */
/**
 * Generic per-stage form + PDF + Email + Next link.
 * Expects query var 'client_stage_args' => [
 *   'acf_args'     => array for acf_form(),
 *   'step_key'     => string, e.g. 'f03',
 *   'next_stage'   => string, e.g. 'f04',
 *   'group'        => ACF field‐group key,
 *   'real_post_id' => integer (0 if still creating)
 * ]
 */
$args         = get_query_var('client_stage_args', []);
$acf_args     = $args['acf_args']      ?? [];
$group        = $args['group']         ?? '';
$next_stage   = $args['next_stage']    ?? '';
$real_post_id = intval($args['real_post_id'] ?? 0);
$scheme     = get_field('certification_type', $real_post_id) ?: 'ems';
$step_key = sanitize_text_field($_GET['stage'] ?? 'draft');


// inside stage-generic.php before acf_form()
$current_url = esc_url(
  add_query_arg(
    $_GET,                // re-add all query params
    get_permalink()                  // onto the base permalink
  )
);

$acf_args['return'] = false;
// Add Bootstrap row to the form tag:
$acf_args['form_attributes'] = [
  'class' => 'row',        // your Bootstrap row and gutter
];

// 1) Render only the “Save” button via ACF and dont show if we are doing PDF
if ($step_key != 'f03') {
  acf_form($acf_args);
}
echo '<div class="mt-4">';

// 3) PDF‐generation block
if ($real_post_id) {
  $pdf_stages = get_certification_pdf()[$scheme] ?? [];

  // print_r ($pdf_stages);
  if (in_array($step_key, $pdf_stages, true)) {
    // ACF stores URL in a field named {step_key}_pdf
    $field_key = "{$step_key}_pdf";
    $pdf_url   = get_field($field_key, $real_post_id);

    echo '<div id="pdf-content-container" class="mb-3">';
    if ($pdf_url) {
      printf(
        '<object width="100%%" height="650" data="%s#zoom=95"></object>',
        esc_url($pdf_url)
      );
    } else {
      echo '<button class="btn btn-success btn-sm generate-pdf" data-post-id="' . $real_post_id . '" data-scheme="qms"
        data-stage="' . $step_key . '"><i class="fa-solid fa-file-circle-plus"></i> Generate PDF</button>';
    }
    echo '</div>';

    // Send Email button (only if we have a template defined)
    if ($pdf_url) {
      $templates = get_certification_emails()[$scheme][$step_key] ?? null;
      $contact_email = get_field('contact_person_contact_email_new',$real_post_id);
      if ($templates) {
        ?>
      <button
  id="send-email-btn"
  class="btn btn-warning mb-3 send-email-btn"
  data-bs-toggle="modal"
  data-bs-target="#sendEmailModal"
  data-post-id="<?php echo esc_attr($real_post_id);?>"
  data-client-name="<?php echo esc_attr(get_the_title($real_post_id));?>"
  data-email="<?php echo esc_attr($contact_email);?>"
  data-pdf-url="<?php echo esc_url($pdf_url);?>"
  data-pdf-filename="<?php echo esc_attr(basename($pdf_url));?>">
  <i class="fa-regular fa-envelope"></i> Send Email
</button>
<?php
if ( $pdf_url && $templates ) {
  // gather modal data
  set_query_var('send_email_args', [
    'post_id'       => $real_post_id,
    'pdf_url'       => $pdf_url,
    'contact_email' => get_field('contact_person_contact_email_new',$real_post_id),
    'client_name'   => get_the_title($real_post_id),
  ]);
  // include the modal
  get_template_part('template-parts/client/send-email-modal');
}
?>

                   <?php 
      }
    }
  }
}



// 2) If we have a real post ID (i.e. the form has been saved at least once),
//    show a Next button that links to the next-stage URL.
if ($real_post_id && $next_stage):
  $next_url = add_query_arg(
    ['new_post_id' => $real_post_id, 'stage' => $next_stage],
    get_permalink()
  );
?>
  <div class="next-button-wrapper">
    <a href="<?php echo esc_url($next_url); ?>" class="btn btn-primary text-capitalize">
      Next: <?php echo esc_html($next_stage); ?>
    </a>
  </div>
<?php endif; ?>