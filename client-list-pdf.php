<?php
/*
Template Name: Client List with Send Email
*/
acf_form_head();
get_header();

// Ensure the current user is logged in
if (!is_user_logged_in()) {
    echo '<p>You must be logged in to view your clients.</p>';
    get_footer();
    exit;
}

// Get the current logged-in user's ID and roles
$user_id = get_current_user_id();
$user = wp_get_current_user();
$roles = $user->roles; // Current user roles

// Check if the user is an admin or manager
$is_admin_or_manager = in_array('administrator', $roles) || in_array('manager', $roles);

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
?>
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row wrapper">

        <!-- Sidebar -->
        <aside class="sidebar p-0 rounded shadow-sm position-sticky">
            <?php get_sidebar('custom'); ?>
        </aside>

        <!-- Main Content -->
        <main id="content" class="flex-fill my-4">
            <div class="shadow-sm">
                <div class="card-body">
                    <!-- Search Form -->
                    <div class="p-2">
                        <div class="container mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="mb-3">Client List</h3>
                                    <p>Welcome, <?php echo esc_html($user->display_name); ?>! Here are your clients:</p>
                                    <!-- create new client with parms new_post_id=xxx&stage=draft -->

                                </div>
                                <div class="col-md-3 ms-auto">
                                    <a href="<?php echo site_url('/create-client?new_post_id=xxx&stage=draft'); ?> " class="btn btn-primary float-end">Create New Client</a>
                                </div>
                                <div class="col-md-3 ms-auto">

                                    <form method="get" action="<?php echo esc_url(get_permalink()); ?>" class="d-flex">
                                        <input type="text" name="search_query" value="<?php echo esc_attr($search_query); ?>" placeholder="Search clients..." class="form-control me-2">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Client Table -->
                    <?php
                    if ($clients->have_posts()) {
                        echo '<table class="table table-bordered table-striped table-hover align-middle">';
                        echo '<thead><tr><th>Client ID</th><th>Client Name</th><th>Assigned Employee</th><th>Client Status</th><th>Created Date</th><th>Actions</th></tr></thead>';
                        echo '<tbody>';

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
                            $pdf_button = '';
                            if ($pdf_url) {
                                $pdf_button =   '<a href="' . esc_url($pdf_url) . '" target="_blank" class="btn btn-primary btn-sm"><i class="fa-regular fa-file-pdf"></i></a>';
                                $pdf_button .= ' <button class="btn btn-info btn-sm send-email-btn" data-post-id="' . $post_id . '" data-pdf-url="' . $pdf_url . '" data-email="' . get_field('contact_email', $post_id) . '"><i class="fa-regular fa-envelope"></i> Send Email</button>';
                            } else {
                                $pdf_button =   '<button class="btn btn-success btn-sm generate-pdf" data-post-id="' . $post_id . '"><i class="fa-solid fa-file-circle-plus"></i></button>';
                            }

                            // Add client details and PDF button to the table row
                            echo '<tr>';
                            echo '<td>' . esc_html($post_id) . '</td>';
                            echo '<td> <a href="/create-client/?new_post_id=' . $post_id . '">' . esc_html($client_name) . '</a></td>';
                            echo '<td>' . esc_html($assigned_employee_name) . '</td>';
                            echo '<td class="text-uppercase">' . esc_html($client_status) . '</td>';
                            echo '<td>' . esc_html($created_date) . '</td>';
                            echo '<td>' . $pdf_button . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';

                        // Add Bootstrap-style pagination
                        $big = 999999999;
                        echo '<nav aria-label="Page navigation example">';
                        echo '<ul class="pagination justify-content-end">';
                        echo '<li class="page-item' . ($paged == 1 ? ' disabled' : '') . '"><a class="page-link" href="' . esc_url(get_pagenum_link(1)) . '" tabindex="-1">Previous</a></li>';

                        for ($i = 1; $i <= $clients->max_num_pages; $i++) {
                            echo '<li class="page-item' . ($i == $paged ? ' active' : '') . '"><a class="page-link" href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a></li>';
                        }

                        echo '<li class="page-item' . ($paged == $clients->max_num_pages ? ' disabled' : '') . '"><a class="page-link" href="' . esc_url(get_pagenum_link($clients->max_num_pages)) . '">Next</a></li>';
                        echo '</ul>';
                        echo '</nav>';
                    } else {
                        echo '<p>No clients found.</p>';
                    }

                    wp_reset_postdata();
                    ?>

                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal for Sending Email (Single Modal) -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendEmailModalLabel">Send PDF to Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sendEmailForm">
                    <div class="form-group">
                        <label for="toEmail">To (Client's Email)</label>
                        <input type="email" class="form-control" id="toEmail" name="toEmail" readonly>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="pdfAttachment">PDF Attachment</label>
                        <input type="hidden" id="pdfAttachment" name="pdfAttachment">
                    </div>
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>

<script>
    // Your updated send email JavaScript logic here
</script>