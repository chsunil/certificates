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
$per_page = 8; // Number of clients per page
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
            'key'   => 'assigned_employee',
            'value' => $user_id,
            'compare' => '='
        )
    );
}

// If there is a search query, add it to the query
$search_query = isset($_GET['search_query']) ? sanitize_text_field($_GET['search_query']) : '';

if ($search_query) {
    $args['s'] = $search_query;
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
                <div class="card-body px-2">
                    <!-- Search Form -->
                    <div class="p-2">
                        <div class="container mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <h3 class="mb-3">Client List</h3>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-5 ms-auto">
                                    <a href="<?php echo esc_url(site_url('/create-client?new_post_id=create&stage=draft')); ?> " class="btn btn-success float-start mx-3"><span class="fas fa-plus" style="padding-right: 10px;"></span>Create New Client</a>

                                    <form method="get" action="<?php echo esc_url(get_permalink()); ?>" class="d-flex">
                                        <input type="text" name="search_query" value="<?php echo esc_attr($search_query); ?>" placeholder="Search Clients..." class="form-control me-2">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Client Table -->
                    <?php
                    if ($clients->have_posts()) {
                        echo '<table class="table table-bordered table-striped table-hover align-middle footable" data-paging="true" data-filtering="true" data-sorting="true" data-page-size="10">';
                        echo '<thead><tr>
                           
                            <th>Client Name</th>
                            <th>Certification Type</th>
                            <th data-breakpoints="xs">Assigned Employee</th>
                            <th>Client Status</th>
                            <th data-breakpoints="xs">Created Date</th>
                            </tr></thead>';
                        echo '<tbody>';

                        while ($clients->have_posts()) {
                            $clients->the_post();
                            $post_id = get_the_ID();
                            $client_name = get_the_title();
                            $certification_type =  get_field('certification_type', $post_id);
                            $assigned_employee_name = do_shortcode('[assigned_employee_name post_id="' . $post_id . '"]');
                            $client_status = get_field('client_stage', $post_id);
                            $created_date = get_the_date('d M Y', $post_id);
                            // Get the generated PDF URL if available
                            $pdf_url = get_field('f03_pdf', $post_id);
                            $pdf_button = '';
                            if ($pdf_url) {
                                $pdf_button =   '<a href="' . esc_url($pdf_url) . '" target="_blank" class="btn btn-primary btn-sm"><i class="fa-regular fa-file-pdf"></i></a>';
                                $pdf_button .= ' <button class="btn btn-info btn-sm send-email-btn" data-post-id="' . $post_id . '" data-pdf-url="' . $pdf_url . '" data-email="' . get_field('contact_email', $post_id) . '"><i class="fa-regular fa-envelope"></i> Send Email</button>';
                            } else {
                                $pdf_button =   '<button class="btn btn-success btn-sm generate-pdf" data-post-id="' . $post_id . '"><i class="fa-solid fa-file-circle-plus"></i>Generate PDF</button>';
                                // need to remove here
                            }

                            echo '<tr>';

                            echo '<td> <a href="/create-client/?new_post_id=' . $post_id . '">' . esc_html($client_name) . '</a></td>';
                            echo '<td>' . esc_html($certification_type) . '</td>';
                            echo '<td>' . esc_html($assigned_employee_name) . '</td>';
                            echo '<td class="text-uppercase">' . esc_html($client_status) . '</td>';
                            echo '<td>' . esc_html($created_date) . '</td>';

                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';
                        // Add Bootstrap-style pagination
                        $big = 999999999;
                        // Pagination
                        echo '<nav aria-label="Page navigation">';
                        echo '<ul class="pagination justify-content-end">';

                        // Previous link
                        echo '<li class="page-item' . ($paged == 1 ? ' disabled' : '') . '">';
                        echo '<a class="page-link" href="' . esc_url(add_query_arg('paged', max(1, $paged - 1))) . '">Previous</a>';
                        echo '</li>';

                        // Page numbers
                        for ($i = 1; $i <= $clients->max_num_pages; $i++) {
                            echo '<li class="page-item' . ($i == $paged ? ' active' : '') . '">';
                            echo '<a class="page-link" href="' . esc_url(add_query_arg('paged', $i)) . '">' . $i . '</a>';
                            echo '</li>';
                        }

                        // Next link
                        echo '<li class="page-item' . ($paged == $clients->max_num_pages ? ' disabled' : '') . '">';
                        echo '<a class="page-link" href="' . esc_url(add_query_arg('paged', min($clients->max_num_pages, $paged + 1))) . '">Next</a>';
                        echo '</li>';

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

<!-- Modal for Sending Email -->
<!-- ... (keep existing modal code unchanged) ... -->

<?php get_footer(); ?>