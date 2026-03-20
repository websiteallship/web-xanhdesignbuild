<?php
/**
 * Taxonomy Archive Template: project_type (Loại Dự Án).
 *
 * Displays projects filtered by project type taxonomy term.
 * Reuses the portfolio page design:
 * Hero (with term info) → Filter Bar → Grid → CTA.
 *
 * URL: /loai-du-an/{term-slug}/
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

	<?php // Section 3: Project Grid ?>
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
