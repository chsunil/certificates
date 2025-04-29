<?php

/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

?>
<?php astra_content_bottom(); ?>
</div> <!-- ast-container -->
</div><!-- #content -->
<?php
astra_content_after();

astra_footer_before();

astra_footer();

astra_footer_after();
?>
</div><!-- #page -->
<?php
astra_body_bottom();
wp_footer();
?>
</body>
<script type="text/javascript">
    // Get the button element by its ID
    const button = document.getElementById('sendemailbtn');

    // Add an event listener to the button for the 'click' event
    button.addEventListener('click', function() {
        // Log a message to the console when the button is clicked
        console.log('Button was clicked!');
    });
</script>

</html>