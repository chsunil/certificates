<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <?php
            $user = wp_get_current_user();
            $avatar_url = get_avatar_url($user->ID, ['default' => '']);

            // Check if user has a custom avatar
            if (!empty($avatar_url)) {
                echo '<img src="' . esc_url($avatar_url) . '" alt="User Avatar">';
            } else {
                // Fallback to site logo
                if (function_exists('astra_logo')) {
                    astra_logo();
                } else {
                    // Optional: fallback image if no astra_logo exists
                    // echo '<img src="https://via.placeholder.com/60" alt="Default Avatar">';
                }
            }
            ?>

            <h5></h5>

            <ul class="list-unstyled m-0">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> <?php echo esc_html($user->display_name ?: 'Welcome'); ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/profile">Profile</a></li>
                        <li><a class="dropdown-item" href="<?php echo wp_logout_url(home_url()) ?>">Logout</a></li>
                    </ul>
                </li>
            </ul>
            <button type="button" id="sidebarCollapse" class="btn btn-dark">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <!-- Button trigger modal -->
        <?php
        if (has_nav_menu('primary')) {
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class'     => 'components',
                'container'      => false,
                'walker'         => new Astra_Custom_Walker(), // Make sure this is defined
                'fallback_cb'    => false,
            ]);
        }
        ?>
    </nav>


</div>