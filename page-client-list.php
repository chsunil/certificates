<?php

/**
 * Template Name: Client List (Bootstrap Flex Layout)
 */
get_header();
?>



<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row ">

        <!-- Sticky LEFT Sidebar -->
        <aside class="sidebar  p-0 rounded shadow-sm position-sticky">

            <?php get_sidebar('custom'); ?>

        </aside>

        <!-- Main Content -->
        <main id="content" class="flex-fill my-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php
                    if (have_posts()) :
                        while (have_posts()) : the_post();
                            // the_title('<h2 class="card-title mb-4">', '</h2>');
                            echo '<div class="card-text">';
                            the_content(); // Ninja Tables shortcode in page content
                            echo '</div>';
                        endwhile;
                    else :
                        echo '<p>No content found.</p>';
                    endif;
                    ?>
                </div>
            </div>
        </main>

    </div>
</div>

<?php get_footer(); ?>

<style>
    .sidebar {
        max-width: 250px;
        flex-shrink: 0;
    }

    .ninja_table_wrapper {
        font-family: inherit;
        font-size: 1rem;
    }

    .ninja_table_wrapper table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }

    .ninja_table_wrapper th,
    .ninja_table_wrapper td {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
    }

    .ninja_table_wrapper th {
        background-color: #f8f9fa;
        color: #212529;
    }

    .ninja_table_wrapper tr:hover {
        background-color: #f1f1f1;
    }
</style>