<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
    <main role="main">
        <div class="main-content">
            <?php the_content(); ?>
        </div>
    </main>
<?php
get_footer();