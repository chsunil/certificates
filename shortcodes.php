<?php
// Users list with search and pagination
function wpdevpro_list_auditors_with_search_pagination($atts) {
    ob_start();

    // Attributes and Defaults
    $atts = shortcode_atts(array(
        'number' => 10, // Default users per page
    ), $atts, 'auditor_list');

    // Get current page
    $paged = isset($_GET['auditor_page']) ? max(1, intval($_GET['auditor_page'])) : 1;
    $offset = ($paged - 1) * intval($atts['number']);

    // Get search term
    $search_query = isset($_GET['auditor_search']) ? sanitize_text_field($_GET['auditor_search']) : '';

    // Query arguments
    $args = array(
        'exclude'        => array(1), // Exclude admin or user ID 1
        'number'         => intval($atts['number']),
        'offset'         => $offset,
        'order'          => 'ASC',
        'orderby'        => 'user_registered',
        'count_total'    => true,
        'fields'         => 'all_with_meta',
        'role'           => 'auditor', // Ensure this role exists
    );

    // Add search if present
    if (!empty($search_query)) {
        $args['search'] = '*' . esc_attr($search_query) . '*';
        $args['search_columns'] = array('user_login', 'user_email', 'display_name');
    }

    // The User Query
    $user_query = new WP_User_Query($args);

    // Bootstrap Container and Row
    echo '<div class="container">';
    echo '<div class="row mb-4">';
    echo '<div class="col-md-6">';
    echo '<h2 class="mb-3">Auditors List</h2>';
    echo '</div>'; // End of col-md-9
    echo '<div class="col-md-3 ms-auto">';
    echo '<a href="' . site_url('/user-add') . '" class="btn btn-success float-end">Add New Auditor</a>';
    echo '</div>'; // End of col-md-3

    // Search Form (right-aligned)
    echo '<div class="col-md-3 ms-auto">';
?>
    <form method="get" class="d-flex">
        <input type="text" name="auditor_search" value="<?php echo esc_attr($search_query); ?>" placeholder="Search users..." class="form-control me-2" />
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if (isset($_GET['auditor_page'])) : ?>
            <input type="hidden" name="auditor_page" value="1" />
        <?php endif; ?>
    </form>
    <?php
    echo '</div>'; // End of col-md-3
    echo '</div>'; // End of row

    // User List in Bootstrap Table
    if (!empty($user_query->results)) {
    ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Display Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Registered</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user_query->results as $user) : ?>
                    <tr>
                        <td><?php echo esc_html($user->display_name); ?></td>
                        <td><?php echo esc_html($user->user_email); ?></td>
                        <td><?php echo esc_html($user->user_login); ?></td>
                        <td><?php echo esc_html($user->user_registered); ?></td>
                        <td>
                            <?php echo esc_html(implode(', ', $user->roles)); ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('user-edit?id=' . $user->ID)); ?>" class="btn btn-sm btn-primary">Edit</a>
                            <!-- <a href="<?php echo esc_url(admin_url('users.php?action=delete&user_id=' . $user->ID)); ?>" class="btn btn-sm btn-danger delete-auditor" data-user="<?php echo $user->ID; ?>">Delete</a> -->

                            <button class="btn btn-sm btn-danger delete-auditor" data-user="<?php echo $user->ID; ?>">Delete</button>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.delete-auditor').forEach(button => {
                    button.addEventListener('click', function() {
                        if (!confirm('Are you sure you want to delete this user?')) return;

                        let userId = this.getAttribute('data-user');

                        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({
                                    action: 'delete_auditor',
                                    user_id: userId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('User deleted successfully');
                                    location.reload();
                                } else {
                                    alert('Error: ' + data.data);
                                }
                            });
                    });
                });
            });
        </script>
<?php
    } else {
        echo '<p>No users found.</p>';
    }

    // Pagination
    $total_users = $user_query->get_total();
    $total_pages = ceil($total_users / intval($atts['number']));

    if ($total_pages > 1) {
        echo '<nav aria-label="Page navigation example">';
        echo '<ul class="pagination justify-content-end">';

        // Previous button
        echo '<li class="page-item' . ($paged == 1 ? ' disabled' : '') . '">';
        echo '<a class="page-link" href="' . esc_url(get_pagenum_link(1)) . '" tabindex="-1">Previous</a>';
        echo '</li>';

        // Page numbers
        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<li class="page-item' . ($i == $paged ? ' active' : '') . '">';
            echo '<a class="page-link" href="' . esc_url(add_query_arg(array('auditor_page' => $i, 'auditor_search' => $search_query))) . '">' . $i . '</a>';
            echo '</li>';
        }

        // Next button
        echo '<li class="page-item' . ($paged == $total_pages ? ' disabled' : '') . '">';
        echo '<a class="page-link" href="' . esc_url(add_query_arg(array('auditor_page' => $total_pages, 'auditor_search' => $search_query))) . '">Next</a>';
        echo '</li>';

        echo '</ul>';
        echo '</nav>';
    }

    echo '</div>'; // End of container

    return ob_get_clean();
}
add_shortcode('auditor_list', 'wpdevpro_list_auditors_with_search_pagination');
