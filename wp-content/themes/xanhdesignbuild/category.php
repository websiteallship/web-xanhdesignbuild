<?php
/**
 * Category Archive Template (category.php).
 *
 * Displays category-specific blog listing reusing the blog page design:
 * Hero (with category info), Category Tabs, Article Grid, and Lead Magnet.
 * Skips the "Featured" section since it is blog-wide, not per-category.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main" role="main">

	<?php get_template_part( 'template-parts/hero/hero', 'blog' ); ?>
	<?php get_template_part( 'template-parts/sections/section', 'blog-category-tabs' ); ?>
	<?php get_template_part( 'template-parts/sections/section', 'blog-grid' ); ?>
	<?php get_template_part( 'template-parts/sections/section', 'blog-lead-magnet' ); ?>


</main>

<?php
get_footer();
