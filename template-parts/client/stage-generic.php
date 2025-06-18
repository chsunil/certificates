<?php
/**
 * /template-parts/client/stage-generic.php
 */
/**
 * Expects query var 'client_stage_args' => [
 *   'acf_args'     => array for acf_form(),
 *   'group'        => ACF field‐group key,
 *   'next_stage'   => slug of the next stage,
 *   'real_post_id' => integer (0 if still creating)
 * ]
 */
$args         = get_query_var('client_stage_args', []);
$acf_args     = $args['acf_args']      ?? [];
$group        = $args['group']         ?? '';
$next_stage   = $args['next_stage']    ?? '';
$real_post_id = intval( $args['real_post_id'] ?? 0 );
// inside stage-generic.php before acf_form()
$current_url = esc_url(
  add_query_arg( $_GET,                // re-add all query params
    get_permalink()                  // onto the base permalink
  )
);

$acf_args['return'] = false;
// Add Bootstrap row to the form tag:
$acf_args['form_attributes'] = [
  'class' => 'row',        // your Bootstrap row and gutter
];
// 1) Render only the “Save” button via ACF
acf_form( $acf_args );

// 2) If we have a real post ID (i.e. the form has been saved at least once),
//    show a Next button that links to the next-stage URL.
if ( $real_post_id && $next_stage ):
    $next_url = add_query_arg(
      ['new_post_id'=> $real_post_id, 'stage'=> $next_stage],
      get_permalink()
    );
?>
  <div class="next-button-wrapper">
    <a href="<?php echo esc_url($next_url);?>" class="btn btn-primary">
      Next: <?php echo esc_html($next_stage); ?>
    </a>
  </div>
<?php endif; ?>