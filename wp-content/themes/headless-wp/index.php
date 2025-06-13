<?php get_header(); ?>
<main>
    <?php
    if (have_posts()) {
        // @phpstan-ignore-next-line
        while (have_posts()) {
            the_post();
            $post_format = get_post_format();
            if ($post_format === false) {
                $post_format = null;
            }
            get_template_part('template-parts/content', $post_format);
            
            // Ensure the global $post variable is accessible
            global $post;
            if (isset($post->post_content)) {
                echo apply_filters('the_content', $post->post_content);
            }
        }
    } else {
        get_template_part('template-pparts/content', 'none');
    }
    ?>
</main>
<?php get_footer();
