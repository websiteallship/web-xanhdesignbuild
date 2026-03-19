<?php
/**
 * Archive Template: Dự Án (Portfolio Grid Page).
 *
 * Displays the portfolio listing page with Hero, Filter Bar,
 * Project Grid (WP Loop), and CTA section.
 * CPT: xanh_project | Slug: /du-an/
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
	 * @param string $context 'portfolio'.
	 */
	do_action( 'xanh_before_hero', 'portfolio' );
	?>

	<?php // Section 1: Hero Banner + Counter Strip ?>
	<?php get_template_part( 'template-parts/hero/hero', 'portfolio' ); ?>

	<?php // Section 2: Filter Bar (Sticky) ?>
	<?php get_template_part( 'template-parts/sections/section', 'portfolio-filter' ); ?>

	<?php // Section 3: Project Grid + Load More ?>
	<?php get_template_part( 'template-parts/sections/section', 'portfolio-grid' ); ?>

	<?php // Section 4: CTA ?>
	<?php get_template_part( 'template-parts/sections/section', 'portfolio-cta' ); ?>

	<?php
	/**
	 * Hook: xanh_after_content
	 */
	do_action( 'xanh_after_content' );
	?>

</main>

<?php
get_footer();
