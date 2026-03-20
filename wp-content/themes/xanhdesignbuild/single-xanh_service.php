<?php
/**
 * Template: Single Service Detail — xanh_service.
 *
 * Orchestrates all sections for a single service detail page.
 * Each section is a separate template-part under template-parts/sections/section-sv-*.php.
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

	// ── S1: Hero Banner ──
	get_template_part( 'template-parts/hero/hero', 'service' );

	// ── S2: Sự Thấu Hiểu (Empathy) ──
	get_template_part( 'template-parts/sections/section-sv', 'empathy' );

	// ── S3: Giải Pháp & Lợi Ích (Features) ──
	get_template_part( 'template-parts/sections/section-sv', 'features' );

	// ── S4: Quy Trình Thực Hiện (Process) ──
	get_template_part( 'template-parts/sections/section-sv', 'process' );

	// ── S5: Dự Án Tiêu Biểu (Portfolio) ──
	get_template_part( 'template-parts/sections/section-sv', 'portfolio' );

	// ── S6: Khách Hàng Nói Gì (Testimonial) ──
	get_template_part( 'template-parts/sections/section-sv', 'testimonial' );

	// ── S7: Câu Hỏi Thường Gặp (FAQ) ──
	get_template_part( 'template-parts/sections/section-sv', 'faq' );

	// ── S8: Call To Action ──
	get_template_part( 'template-parts/sections/section-sv', 'cta' );

endwhile;

get_footer();
