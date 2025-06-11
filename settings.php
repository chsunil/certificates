<?php
/*
Template Name: Client Settings
*/
acf_form_head();
get_header();
?>

<div class="container-fluid p-0">
    <!-- Sidebar Toggle Button -->

    <div class="d-flex flex-column flex-md-row  wrapper">

        <!-- Improved Sticky LEFT Sidebar -->
        <aside class="sidebar  p-0 rounded shadow-sm position-sticky">

            <?php get_sidebar('custom'); ?>

        </aside>

        <!-- Main Content -->
        <main id="content" class="flex-fill my-4">

    <?php
    // 3) Capability check
    if ( ! is_user_logged_in() || ! current_user_can('manage_options') ) {
        echo '<p>You do not have permission to view this page.</p>';
        get_footer();
        exit;
    }

    // 4) Determine ACF “post” for current user
    $user_id = get_current_user_id();
    $post_id = 'user_' . $user_id;

    // 5) Try loading existing repeater data
    $nace_rows     = get_field('nace_codes',  $post_id) ?: [];
    $man_days_rows = get_field('man_days',    $post_id) ?: [];

    // 6) If no data, show the ACF form to populate it
    if ( empty($nace_rows) && empty($man_days_rows) ) {

        // Replace 'group_client_settings' with your actual field‐group key
        acf_form([
            'post_id'      => $post_id,
            'field_groups' => ['group_client_settings'],
            'submit_value' => 'Save Settings',
            // reload page on save so we pick up the new data
            'return'       => add_query_arg('saved','1', get_permalink())
        ]);

    } else {
        // 7) Otherwise, render the two tables

        // Success message after save
        if ( isset($_GET['saved']) ) {
            echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
        }
        ?>

        <div class="client-tables-wrapper">

          <?php if ( $nace_rows ): ?>
          <h2>NACE Code Categories</h2>
          <table class="widefat striped">
            <thead>
              <tr>
                <th>IAF No.</th><th>Description</th><th>Q</th><th>E</th><th>O</th>
                <th>A1</th><th>A2</th><th>A3</th><th>A4</th><th>A5</th><th>T.E</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ( $nace_rows as $row ): ?>
              <tr>
                <td><?= esc_html( $row['iaf_no'] ) ?></td>
                <td><?= esc_html( $row['description'] ) ?></td>
                <td><?= esc_html( $row['category_q'] ) ?></td>
                <td><?= esc_html( $row['category_e'] ) ?></td>
                <td><?= esc_html( $row['category_o'] ) ?></td>
                <td><?= $row['a1'] ? '✔️' : '' ?></td>
                <td><?= $row['a2'] ? '✔️' : '' ?></td>
                <td><?= $row['a3'] ? '✔️' : '' ?></td>
                <td><?= $row['a4'] ? '✔️' : '' ?></td>
                <td><?= $row['a5'] ? '✔️' : '' ?></td>
                <td><?= $row['te'] ? '✔️' : '' ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>


          <?php if ( $man_days_rows ): ?>
          <h2>Man-Days by Employee Range</h2>
          <table class="widefat striped">
            <thead>
              <tr>
                <th>No. of employees</th>
                <th>QMS</th><th>EMS H</th><th>EMS M</th>
                <th>EMS L</th><th>EMS Ltd</th><th>ISMS</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ( $man_days_rows as $row ): ?>
              <tr>
                <td><?= esc_html( $row['employee_range'] ) ?></td>
                <td><?= esc_html( $row['qms'] ) ?></td>
                <td><?= esc_html( $row['ems_h'] ) ?></td>
                <td><?= esc_html( $row['ems_m'] ) ?></td>
                <td><?= esc_html( $row['ems_l'] ) ?></td>
                <td><?= esc_html( $row['ems_limited'] ) ?></td>
                <td><?= esc_html( $row['isms'] ) ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>

        </div>

    <?php } // end if/else ?>

        </main>
    </div>
</div>

<?php get_footer(); ?>