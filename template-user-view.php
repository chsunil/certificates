<?php
/*
Template Name: User View
*/
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
                        <h3><?php echo esc_html($user->display_name); ?></h3>
                        <p><strong>Email:</strong> <?php echo esc_html($user->user_email); ?></p>
                        <p><strong>Username:</strong> <?php echo esc_html($user->user_login); ?></p>
                        <p><strong>Registered:</strong> <?php echo esc_html($user->user_registered); ?></p>
                        <p><strong>Role:</strong> <?php echo implode(', ', $user->roles); ?></p>
                        <p><strong>Support Type (NAC):</strong> 
					<?php
$nac_support = get_field('nac_support', 'user_' . $user_id);

if (!empty($nac_support) && is_array($nac_support)) {
    echo '<p><strong>NAC Support:</strong> ' . implode(', ', array_map('esc_html', $nac_support)) . '</p>';
} else {
    echo '<p><strong>NAC Support:</strong> None</p>';
}
?></p>
                        <p><strong>Profile Picture:</strong><br>
                            <?php echo get_avatar($user_id, 96); ?>
                        </p>
                        <a href="<?php echo add_query_arg(['user_id' => $user_id], get_permalink(get_page_by_path('user-edit'))); ?>" class="btn btn-primary">Edit</a>
                    <?php else: ?>
                        <p>User not found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php get_footer(); ?>
