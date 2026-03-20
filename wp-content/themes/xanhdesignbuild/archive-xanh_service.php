<?php
/**
 * Archive Template: Dịch Vụ (Services Grid Page).
 *
 * Displays the services listing page with Hero, Service Grid
 * (WP Loop + Load More), and CTA section.
 * CPT: xanh_service | Slug: /dich-vu/
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
	 * @param string $context 'services'.
	 */
	do_action( 'xanh_before_hero', 'services' );
	?>

	<?php // Section 1: Hero Banner + Counter Strip ?>
	<?php get_template_part( 'template-parts/hero/hero', 'services' ); ?>

	<?php // Section 2: Service Grid + Load More ?>
	<?php get_template_part( 'template-parts/sections/section', 'services-grid' ); ?>

	<?php // Section 3: CTA ?>
	<?php get_template_part( 'template-parts/sections/section', 'services-cta' ); ?>

	<?php
	/**
	 * Hook: xanh_after_content
	 */
	do_action( 'xanh_after_content' );
	?>

</main>

<?php
get_footer();
