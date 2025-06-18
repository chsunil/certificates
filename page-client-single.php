<?php
/**
 * Template Name: Multi-Step ACF Form with Tabs
 */
acf_form_head();
get_header();

// 1) URL params
$new_id      = $_GET['new_post_id'] ?? '';
$stage_param = sanitize_text_field($_GET['stage'] ?? 'draft');

// 2) All stages & current stage
$type   = get_field('certification_type');

$stages = get_certification_stages()[ $type ] ?? [];

// 3) Determine $stage
$stage = isset($stages[$stage_param]) ? $stage_param : 'draft';

// 4) Figure out post ID for ACF
if ($stage==='draft' && ! is_numeric($new_id)) {
  $acf_post_id   = 'new_post';
  $new_post_args = ['post_type'=>'client','post_status'=>'publish'];
  $real_post_id  = 0;
} else {
  $acf_post_id   = intval($new_id);
  $real_post_id  = $acf_post_id;
  $new_post_args = [];
}

// 5) Sync client_stage meta to the URL’s stage param
if ( $real_post_id && $stage ) {
    // Only update if it’s not already set
    $current = get_field('client_stage', $real_post_id);
    if ( $current !== $stage ) {
        update_field('client_stage', $stage, $real_post_id);
    }
}
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row wrapper">

    <!-- Sidebar -->
    <aside class="sidebar p-0 rounded shadow-sm position-sticky">
            <?php get_sidebar('custom'); ?>
        </aside>

    <!-- Main Content -->
      <main id="content" class="flex-fill my-4">
    <div class="container g-0">

      <!--  TAB NAV -->
      <div class="overflow-auto mb-3">
        <?php
        // Pass data to nav.php
        set_query_var('client_nav_args', [
          'stages'      => $stages,
          'stage'       => $stage,
          'real_post_id'=> $real_post_id
        ]);
        get_template_part('template-parts/client/nav');
        ?>
      </div>

      <!--  STAGE FORM -->
      <?php
     $step = $stages[$stage] ?? null;
     print_r ($step);
    
      if ($step && ! empty($step['group'])):
echo $current_url = add_query_arg(
      ['new_post_id'=> $real_post_id, 'stage'=> $stage],
      get_permalink()
    );
        // Build ACF parameters
        $acf_args = [
          'post_id'      => $acf_post_id,
          'field_groups' => [$step['group']],
          'submit_value' => 'Save',
          'return'       =>  $current_url,
        ];

        if ($acf_post_id === 'new_post') {
          $acf_args['new_post'] = $new_post_args;
        }

        // Pass data to stage-generic.php
        set_query_var('client_stage_args', [
          'acf_args'   => $acf_args,
           'group'         => $step['group'],
          'next_stage' => $step['next'] ?? '',
          'real_post_id'  => $real_post_id
        ]);
        echo '<div class="row">';
        get_template_part('template-parts/client/stage','generic');
        echo '</div>';

      else:
        echo '<p>No form configured for this stage.</p>';
      endif;
      ?>

    </div>
      </main>
  </div>
</div>

<?php get_footer(); ?>
