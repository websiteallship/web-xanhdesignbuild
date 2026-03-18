<?php
/**
 * Front Page Template.
 *
 * Displays the homepage with all sections.
 * Requires ACF field group: group_homepage.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main" role="main">

	<?php
	/**
	 * Hook: xanh_before_hero
	 *
	 * @param int $post_id Current page ID.
	 */
	do_action( 'xanh_before_hero', get_the_ID() );
	?>

	<?php get_template_part( 'template-parts/hero/hero', 'home' ); ?>
	<?php get_template_part( 'template-parts/sections/section', 'marquee' ); ?>

	<?php // B1b: ?>
	<?php get_template_part( 'template-parts/sections/section', 'empathy' ); ?>
	<?php get_template_part( 'template-parts/sections/section', '4xanh' ); ?>

	<?php // B1c: ?>
	<?php get_template_part( 'template-parts/sections/section', 'services' ); ?>
	<?php get_template_part( 'template-parts/sections/section', 'cta' ); ?>

	<?php // B1d: ?>
	<?php get_template_part( 'template-parts/sections/section', 'portfolio-featured' ); ?>

	<?php // B1e: ?>
	<?php get_template_part( 'template-parts/sections/section', 'process' ); ?>
	<?php get_template_part( 'template-parts/sections/section', 'cta-contact' ); ?>

	<?php // B1f: ?>
	<?php get_template_part( 'template-parts/sections/section', 'partners' ); ?>
	<?php get_template_part( 'template-parts/sections/section', 'blog-latest' ); ?>

	<?php
	/**
	 * Hook: xanh_after_content
	 */
	do_action( 'xanh_after_content' );
	?>

</main>

<?php
get_footer();
