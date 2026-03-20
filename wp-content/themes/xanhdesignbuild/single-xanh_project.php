<?php
/**
 * Template: Single Portfolio Detail — xanh_project.
 *
 * Orchestrates all sections for a single project detail page.
 * Each section is a separate template-part under template-parts/sections/section-pd-*.php.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	// ── D1 + D2: Hero Image + Breadcrumb ──
	get_template_part( 'template-parts/sections/section-pd', 'hero' );

	// ── D3: Stats Bar ──
	if ( get_field( 'show_stats' ) ) :
		get_template_part( 'template-parts/sections/section-pd', 'stats' );
	endif;

	// ── D4: Project Story ──
	if ( get_field( 'show_story' ) ) :
		get_template_part( 'template-parts/sections/section-pd', 'story' );
	endif;

	// ── D5: Before/After Slider ──
	if ( get_field( 'show_before_after' ) ) :
		get_template_part( 'template-parts/sections/section-pd', 'before-after' );
	endif;

	// ── D5b: Video Hero ──
	if ( get_field( 'show_video' ) ) :
		get_template_part( 'template-parts/sections/section-pd', 'video' );
	endif;

	// ── D7: Gallery ──
	if ( get_field( 'show_gallery' ) ) :
		get_template_part( 'template-parts/sections/section-pd', 'gallery' );
	endif;

	// ── D8: Testimonial ──
	if ( get_field( 'show_testimonial' ) ) :
		get_template_part( 'template-parts/sections/section-pd', 'testimonial' );
	endif;

	// ── D10: CTA ──
	if ( get_field( 'show_cta' ) ) :
		get_template_part( 'template-parts/sections/section-pd', 'cta' );
	endif;

	// ── D9: Related Projects ──
	if ( get_field( 'show_related' ) ) :
		get_template_part( 'template-parts/sections/section-pd', 'related' );
	endif;

endwhile;

get_footer();
