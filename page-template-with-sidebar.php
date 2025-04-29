<?php
/*
Template Name: Left Sidebar Menu
*/
acf_form_head();
get_header();
?>

<div class="container-fluid p-0">
    <!-- Sidebar Toggle Button -->

    <div class="d-flex flex-column flex-md-row  wrapper">

        <!-- Improved Sticky LEFT Sidebar -->
        <aside class="sidebar  p-0 rounded shadow-sm position-sticky">

            <?php get_sidebar('custom'); ?>

        </aside>

        <!-- Main Content -->
        <main id="content" class="flex-fill my-4">
            <div class="shadow-sm">
                <div class="card-body">
                    <?php
                    while (have_posts()) : the_post();
                        the_content();
                    endwhile;
                    ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php get_footer(); ?>