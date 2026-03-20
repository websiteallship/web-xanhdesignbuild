<?php
/**
 * Blog Archive Template (home.php).
 *
 * Displays blog listing with Hero, Category Tabs, Featured Articles,
 * Article Grid with Load More, and Lead Magnet section.
 * WordPress uses home.php for the "Posts page" set in Reading Settings.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main" role="main">

	<?php get_template_part( 'template-parts/hero/hero', 'blog' ); ?>
	<?php get_template_part( 'template-parts/sections/section', 'blog-category-tabs' ); ?>
	<?php get_template_part( 'template-parts/sections/section', 'blog-featured' ); ?>
	<?php get_template_part( 'template-parts/sections/section', 'blog-grid' ); ?>
	<?php get_template_part( 'template-parts/sections/section', 'blog-lead-magnet' ); ?>


</main>

<?php
get_footer();
