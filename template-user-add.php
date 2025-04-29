<?php
/*
Template Name: User Add
*/
acf_form_head();
get_header();

$success = false;
$error = '';
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row wrapper">
        <aside class="sidebar p-0 rounded shadow-sm position-sticky">
            <?php get_sidebar('custom'); ?>
        </aside>

        <main id="content" class="flex-fill my-4">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h3>Add New User</h3>

                    <?php if ($success): ?>
                        <div class="alert alert-success">User created successfully.</div>
                    <?php elseif (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo esc_html($error); ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <?php wp_nonce_field('create_new_user'); ?>

                        <div class="mb-3">
                            <label for="user_login">Username</label>
                            <input type="text" name="user_login" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="user_email">Email</label>
                            <input type="email" name="user_email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="display_name">Display Name</label>
                            <input type="text" name="display_name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="user_pass">Password</label>
                            <input type="password" name="user_pass" class="form-control" required>
                        </div>

                        <!-- Optional role -->
                        <div class="mb-3">
                            <label for="role">Role</label>
                            <select name="role" class="form-select">
                                <option value="auditor">Auditor</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Create User</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce($_POST['_wpnonce'], 'create_new_user')) {
    $data = [
        'user_login' => sanitize_user($_POST['user_login']),
        'user_email' => sanitize_email($_POST['user_email']),
        'display_name' => sanitize_text_field($_POST['display_name']),
        'user_pass' => $_POST['user_pass'],
        'role' => sanitize_text_field($_POST['role']),
    ];

    $user_id = wp_insert_user($data);

    if (!is_wp_error($user_id)) {
        wp_redirect(add_query_arg(['id' => $user_id], get_permalink(get_page_by_path('user-edit'))));
        exit;
    } else {
        $error = $user_id->get_error_message();
    }
}
get_footer();
?>