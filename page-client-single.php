<?php
/*
Template Name: Multi-Step ACF Form with Tabs
*/

// ─── 1) ENSURE WE HAVE A VALID client POST ID VIA URL ────────────────────────

// Grab ?new_post_id= from the URL (or default to zero)
$post_id = isset($_GET['new_post_id']) ? intval($_GET['new_post_id']) : 0;


// If it’s missing or not actually a published/draft client, we must create one:
if (! $post_id || get_post_type($post_id) !== 'client') {

    // Try to pull the “Organization Name” from the ACF POST payload (if they just clicked Save).
    // Replace 'organization_name' with your real ACF field key for organization_name.
    $org_name = '';
    if (! empty($_POST['acf']) && is_array($_POST['acf'])) {
        $org_name = sanitize_text_field($_POST['acf']['organization_name'] ?? '');
    }

    // Fallback to a timestamped placeholder
    if (! $org_name) {
        $org_name = 'Draft Client – ' . current_time('Y-m-d H:i:s');
    }

    // Insert a brand-new client draft
    $new_id = wp_insert_post([
        'post_type'   => 'client',
        'post_status' => 'publish',
        'post_title'  => $org_name,
    ]);

    // If insertion succeeded, redirect back to ourselves with the ID & stage=draft
    if (! is_wp_error($new_id)) {
        wp_safe_redirect(add_query_arg([
            'new_post_id' => $new_id,
            'stage'       => 'draft',
        ], get_permalink()));
        exit;
    }

    // (If WP_Error, fall through and let ACF show its own error)
}

// At this point, $post_id is guaranteed to be a real client ID
$post_id = intval($_GET['new_post_id']);

// ─── 2) LOAD STAGES & FIGURE OUT CURRENT STAGE ───────────────────────────────
require_once get_stylesheet_directory() . '/certification-stages.php';

$scheme      = 'ems';
$all_stages  = get_certification_stages();
$stages      = $all_stages[$scheme] ?? [];



// ─── 3) LET ACF & WP OUTPUT THE HEADER & START YOUR FORM ───────────────────
acf_form_head();
get_header();
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row wrapper">
        <aside class="sidebar p-0 rounded shadow-sm position-sticky">
            <?php get_sidebar('custom'); ?>
        </aside>

        <main id="content" class="flex-fill my-4">
            <div class="card shadow-sm">
                <div class="card-body">

                    <!-- Tabs -->
                    <?php
                    $current_stage = get_field('client_stage', $post_id) ?: 'draft';
                    $stage_keys    = array_keys($stages);
                    $current_index = array_search($current_stage, $stage_keys, true);
                    $stagefrom_url = isset($_GET['stage']) && in_array($_GET['stage'], $stage_keys, true) ? $_GET['stage'] : $current_stage; // Ensure valid stage

                    ?>

                    <!-- Tabs -->
                    <ul class="nav nav-tabs m-0">
                        <?php foreach ($stages as $key => $step): ?>
                            <?php
                            $tab_index = array_search($key, $stage_keys);
                            $is_visible = $tab_index <= $current_index;
                            $is_active = ($key === $stagefrom_url) ? ' active' : ''; // Fix active class logic
                            ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $is_active; ?> <?php echo (!$is_visible ? ' d-none' : ''); ?>"
                                    href="<?php echo esc_url(site_url('/create-client/')); ?>?new_post_id=<?php echo esc_attr($post_id); ?>&stage=<?php echo esc_attr($key); ?>"
                                    id="<?php echo esc_attr($key); ?>-tab"
                                    <?php echo (!$is_visible ? 'tabindex="-1"' : ''); ?>>
                                    <strong class="text-uppercase"><?php echo esc_html($key); ?></strong><br>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>


                    <!-- Tab Content -->
                    <div class="tab-content">
                        <?php foreach ($stages as $key => $step): ?>
                            <?php
                            $tab_index = array_search($key, $stage_keys);
                            $is_visible = $tab_index <= $current_index;
                            ?>

                            <div id="<?php echo esc_attr($key); ?>"
                                class="tab-pane fade <?php echo ($key === $current_stage) ? 'show active' : ''; ?> <?php echo (!$is_visible ? 'd-none' : ''); ?>"
                                style="padding-top: 20px;">
                                <h4>Stage: <?php echo esc_html($step['title']); ?></h4>

                                <?php
                                // Check if the current stage is F-03
                                if ($current_stage === 'f03') {
                                    // Get the PDF URL from ACF field
                                    $pdf_url = get_field('generated_pdf_url', $post_id);

                                    // If the PDF URL exists, embed it in an iframe
                                    if ($pdf_url) {
                                        echo '<iframe src="' . esc_url($pdf_url) . '" width="100%" height="600px"></iframe>';
                                    } else {
                                        echo '<p>No PDF available. Please generate the PDF first.</p>';
                                    }
                                }
                                ?>

                                <form method="post" class="acf-step-form" data-next-stage="<?php echo esc_attr($step['next']); ?>">
                                    <?php
                                    $return_url = add_query_arg('new_post_id', '%post_id%', get_permalink());
                                    acf_form([
                                        'post_id'      => ($post_id == 'new_post' ? 'new_post' : $post_id),
                                        'new_post'     => ($post_id == 'new_post' ? [
                                            'post_type'   => 'client',
                                            'post_status' => 'publish',
                                        ] : null),
                                        'field_groups' => ($step['group']) ? [$step['group']] : [],
                                        'form'         => false,
                                        'return'       => $return_url,
                                    ]);
                                    ?>

                                    <div class="mt-4 d-flex gap-2">
                                        <?php if ($current_stage != 'f03') : ?>
                                            <button type="submit" name="acf_save_only" value="1" class="btn btn-primary save-button">Save</button>
                                        <?php elseif (($current_stage == 'draft') || ($current_stage == '')) :   ?>
                                            <button type="submit" class="btn btn-outline-success next-button"> Save &Next</button>

                                        <?php else :
                                        ?>
                                            <!-- Add Send Email Button -->
                                            <!-- Button to trigger modal -->
                                            <button type="button" class="btn btn-warning send-email-btn" data-bs-toggle="modal" data-bs-target="#send-email-btn">
                                                Send Email
                                            </button>

                                        <?php endif; ?>
                                        <button type="submit" class="btn btn-outline-success next-button d-none">Next</button>
                                    </div>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php get_footer(); ?>


<!-- Modal -->
<div class="modal fade" id="send-email-btn" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
        </div>
    </div>
</div>