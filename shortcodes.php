<?php
// Shortcode to display client list with search and pagination
function client_pdf_list_shortcode($atts) {
    // Check if the user is logged in
    if (!is_user_logged_in()) {
        return '<p>You must be logged in to view your clients.</p>';
    }

    // Get the current logged-in user's ID and roles
    $user_id = get_current_user_id();
    $user = wp_get_current_user();
    $roles = $user->roles; // Current user roles

    // Check if the user is an admin or manager
    $is_admin_or_manager = in_array('administrator', $roles) || in_array('manager', $roles);

    // Display the current user roles
    $roles_output = '<p>Your roles: ' . implode(', ', $roles) . '</p>';

    // Pagination and search parameters
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Default to page 1 if not set
    $per_page = 10; // Number of clients per page

    // Get search query if available
    $search_query = isset($_GET['search_query']) ? sanitize_text_field($_GET['search_query']) : '';

    // Query clients assigned to the logged-in user or show all clients if admin/manager
    $args = array(
        'post_type'      => 'client',  // Assuming 'client' is the post type
        'posts_per_page' => $per_page,
        'paged'          => $paged,  // Add pagination
    );

    // If the user is an admin or manager, show all clients; otherwise, only their clients
    if (!$is_admin_or_manager) {
        $args['meta_query'] = array(
            array(
                'key'   => 'assigned_employee', // ACF field linking clients to employees
                'value' => $user_id,
                'compare' => '='
            )
        );
    }

    // If there is a search query, add it to the query
    if ($search_query) {
        $args['s'] = $search_query; // Add search term to the query
    }

    // Fetch the clients based on the query
    $clients = new WP_Query($args);

    // Search form
    $output = '<div class="p-2">';
    $output .= '<div class="container mb-3">';
    $output .= '<div class="row">';
    $output .= '<div class="col-md-3 ms-auto">'; // Ensure alignment to the right
    $output .= '<form method="get" action="' . esc_url(get_permalink()) . '" class="d-flex">'; // Use d-flex for horizontal alignment
    $output .= '<input type="text" name="search_query" value="' . esc_attr($search_query) . '" placeholder="Search clients..." class="form-control me-2">'; // Add margin with me-2
    $output .= '<button type="submit" class="btn btn-primary">Search</button>'; // Button follows input
    $output .= '</form>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    if ($clients->have_posts()) {
        $output .= '<table class="table table-bordered table-striped">';
        $output .= '<thead><tr><th>Client ID</th><th>Client Name</th><th>Assigned Employee</th><th>Client Status</th><th>Created Date</th><th>Actions</th></tr></thead>';
        $output .= '<tbody>';

        while ($clients->have_posts()) {
            $clients->the_post();
            $post_id = get_the_ID();
            $client_name = get_the_title();

            // Use the [assigned_employee_name] shortcode to fetch the employee's name
            $assigned_employee_name = do_shortcode('[assigned_employee_name post_id="' . $post_id . '"]');

            // Get the client status ACF field
            $client_status = get_field('client_stage', $post_id);

            // Get the created date
            $created_date = get_the_date('d M Y', $post_id);

            // Get the generated PDF URL if available
            $pdf_url = get_field('generated_pdf_url', $post_id);
            // $pdf_button = $pdf_url ?
            //     '<a href="' . esc_url($pdf_url) . '" target="_blank" class="btn btn-primary btn-sm">View Agreement</a>' :
            //     '<button class="btn btn-success btn-sm generate-pdf" data-post-id="' . $post_id . '">Generate PDF</button>';

            $pdf_button = '';
            if ($pdf_url) {
                $pdf_button .= '<a href="' . esc_url($pdf_url) . '" target="_blank" class="btn btn-primary btn-sm">View Agreement</a>';
                $pdf_button .= ' <button class="btn btn-info btn-sm send-email-btn" data-post-id="' . $post_id . ' data-bs-target="sendEmailModal">Send Email</button>'; // Added Send Email button

            } else {
                $pdf_button .= '<button class="btn btn-success btn-sm generate-pdf" data-post-id="' . $post_id . '">Generate PDF</button>';
            }

            // Add client details and PDF button to the table row
            $output .= '<tr>';
            $output .= '<td>' . $post_id . '</td>';
            $output .= '<td>' . esc_html($client_name) . '</td>';
            $output .= '<td>' . esc_html($assigned_employee_name) . '</td>';
            $output .= '<td>' . esc_html($client_status) . '</td>';
            $output .= '<td>' . esc_html($created_date) . '</td>';
            $output .= '<td>' . $pdf_button . '</td>'; // Display Generate PDF button or View Agreement button
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';

        // Add Bootstrap-style pagination
        $big = 999999999; // Need an unlikely integer for pagination
        $output .= '<nav aria-label="Page navigation example">';
        $output .= '<ul class="pagination justify-content-end">';

        // Previous Page Button
        $output .= '<li class="page-item' . ($paged == 1 ? ' disabled' : '') . '">';
        $output .= '<a class="page-link" href="' . esc_url(get_pagenum_link(1)) . '" tabindex="-1">Previous</a>';
        $output .= '</li>';

        // Page Numbers
        for ($i = 1; $i <= $clients->max_num_pages; $i++) {
            $output .= '<li class="page-item' . ($i == $paged ? ' active' : '') . '">';
            $output .= '<a class="page-link" href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a>';
            $output .= '</li>';
        }

        // Next Page Button
        $output .= '<li class="page-item' . ($paged == $clients->max_num_pages ? ' disabled' : '') . '">';
        $output .= '<a class="page-link" href="' . esc_url(get_pagenum_link($clients->max_num_pages)) . '">Next</a>';
        $output .= '</li>';

        $output .= '</ul>';
        $output .= '</nav>';
        $output .= '</div>';

        wp_reset_postdata();
    } else {
        $output .= '<p>No clients found.</p>';
    }

    // Output roles and client list
    return $roles_output . $output;
}
// add_shortcode('client_pdf_list', 'client_pdf_list_shortcode');

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
    echo '<div class="container mt-5">';
    echo '<div class="row mb-4">';

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
                            <a href="<?php echo esc_url(admin_url('users.php?action=delete&user_id=' . $user->ID)); ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
