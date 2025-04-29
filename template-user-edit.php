<?php
/*
Template Name: User Edit
*/
acf_form_head();
get_header();

$user_id = isset($_GET['id']) ? absint($_GET['id']) : 0;
$user = get_user_by('ID', $user_id);
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row wrapper">
        <aside class="sidebar p-0 rounded shadow-sm position-sticky">
            <?php get_sidebar('custom'); ?>
        </aside>

        <main id="content" class="flex-fill my-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if ($user): ?>
                        <h3>Edit User: <?php echo esc_html($user->display_name); ?></h3>

                        <form method="post">
                            <?php wp_nonce_field('update_user_' . $user_id); ?>
                            <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">

                            <div class="mb-3">
                                <label for="display_name">Display Name</label>
                                <input type="text" name="display_name" class="form-control" value="<?php echo esc_attr($user->display_name); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="user_email">Email</label>
                                <input type="email" name="user_email" class="form-control" value="<?php echo esc_attr($user->user_email); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="user_login">Username</label>
                                <input type="text" class="form-control" value="<?php echo esc_attr($user->user_login); ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="user_registered">Registered On</label>
                                <input type="text" class="form-control" value="<?php echo esc_attr($user->user_registered); ?>" readonly>
                            </div>

                            <!-- ACF Fields -->
                            <?php
                            acf_form([
                                'id' => 'acf-user-edit-form',
                                'post_id' => 'user_' . $user_id,
                                'field_groups' => ['group_67d5a040cd192'],
                                'form' => false,
                            ]);
                            ?>

                            <button type="submit" class="btn btn-success">Update User</button>
                        </form>

                    <?php else: ?>
                        <p>User not found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && wp_verify_nonce($_POST['_wpnonce'], 'update_user_' . $user_id)) {
    wp_update_user([
        'ID' => $user_id,
        'display_name' => sanitize_text_field($_POST['display_name']),
        'user_email' => sanitize_email($_POST['user_email']),
    ]);
    acf_save_post('user_' . $user_id);

    wp_redirect(add_query_arg(['id' => $user_id, 'updated' => 1], get_permalink(get_page_by_path('user-view'))));
    exit;
}
get_footer();
?>
