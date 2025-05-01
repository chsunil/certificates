<?php
/*
Template Name: Multi-Step ACF Form with Tabs
*/
acf_form_head();
get_header();
require_once get_stylesheet_directory() . '/certification-stages.php';

$scheme = 'ems';
$all_stages = get_certification_stages();
$stages = $all_stages[$scheme] ?? [];

$post_id = isset($_GET['new_post_id']) ? intval($_GET['new_post_id']) : 'new_post';
if ($post_id !== 'new_post' && get_post_status($post_id) === false) {
    $post_id = 'new_post';
}

$current_stage = ($post_id !== 'new_post')
    ? get_field('client_stage', $post_id) ?: 'draft'
    : 'draft';
$stage_keys = array_keys($stages);
$current_index = array_search($current_stage, $stage_keys);
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
                    <ul class="nav nav-tabs m-0">
                        <?php foreach ($stages as $key => $step): ?>
                            <?php
                            $tab_index = array_search($key, $stage_keys);
                            $is_visible = $tab_index <= $current_index;
                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($key === $current_stage) ? 'active' : ''; ?> <?php echo (!$is_visible ? 'd-none' : ''); ?>"
                                    href="#<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>-tab"
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