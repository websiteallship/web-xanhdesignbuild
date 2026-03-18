<?php
/**
 * Enqueue CSS & JS — Conditional loading.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue theme styles and scripts.
 *
 * Load order:
 * 1. CSS: Tailwind (compiled) → variables → components → page-specific
 * 2. JS: Vendor CDN (GSAP, ScrollTrigger, Lenis, Lucide) → main.js → page-specific
 * 3. Conditional: Swiper (homepage + portfolio detail), GLightbox (portfolio detail)
 *
 * @return void
 */
function xanh_enqueue_scripts() {
	$ver = XANH_THEME_VERSION;
	$uri = XANH_THEME_URI;

	// ═══════════════════════════════════════════
	// Google Fonts — Vietnamese subset (Inter)
	// ═══════════════════════════════════════════
	wp_enqueue_style(
		'xanh-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,300;1,14..32,400&display=swap',
		[],
		null
	);

	// ═══════════════════════════════════════════
	// CSS: Tailwind (compiled) + Custom
	// ═══════════════════════════════════════════
	wp_enqueue_style( 'xanh-tailwind', "$uri/assets/css/output.css", [ 'xanh-google-fonts' ], $ver );
	wp_enqueue_style( 'xanh-variables', "$uri/assets/css/variables.css", [ 'xanh-tailwind' ], $ver );
	wp_enqueue_style( 'xanh-components', "$uri/assets/css/components.css", [ 'xanh-variables' ], $ver );

	// ═══════════════════════════════════════════
	// JS: Vendor CDN (global, defer, footer)
	// ═══════════════════════════════════════════
	wp_enqueue_script(
		'gsap',
		'https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/gsap.min.js',
		[],
		'3.12.7',
		[ 'strategy' => 'defer', 'in_footer' => true ]
	);

	wp_enqueue_script(
		'gsap-st',
		'https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/ScrollTrigger.min.js',
		[ 'gsap' ],
		'3.12.7',
		[ 'strategy' => 'defer', 'in_footer' => true ]
	);

	wp_enqueue_script(
		'lenis',
		'https://cdn.jsdelivr.net/npm/lenis@1.3.17/dist/lenis.min.js',
		[],
		'1.3.17',
		[ 'strategy' => 'defer', 'in_footer' => true ]
	);

	wp_enqueue_script(
		'lucide',
		'https://unpkg.com/lucide@0.468.0',
		[],
		'0.468.0',
		[ 'strategy' => 'defer', 'in_footer' => true ]
	);

	// ═══════════════════════════════════════════
	// JS: Custom (global)
	// ═══════════════════════════════════════════
	wp_enqueue_script(
		'xanh-main',
		"$uri/assets/js/main.js",
		[ 'gsap', 'gsap-st', 'lenis' ],
		$ver,
		[ 'strategy' => 'defer', 'in_footer' => true ]
	);

	// ═══════════════════════════════════════════
	// Conditional: Swiper (Homepage + Portfolio Detail)
	// ═══════════════════════════════════════════
	if ( is_front_page() || is_singular( 'xanh_project' ) ) {
		wp_enqueue_style(
			'swiper-css',
			'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
			[],
			null
		);
		wp_enqueue_script(
			'swiper',
			'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
			[],
			'11',
			[ 'strategy' => 'defer', 'in_footer' => true ]
		);
	}

	// ═══════════════════════════════════════════
	// Conditional: GLightbox (Portfolio Detail only)
	// ═══════════════════════════════════════════
	if ( is_singular( 'xanh_project' ) ) {
		wp_enqueue_style(
			'glightbox-css',
			'https://cdn.jsdelivr.net/npm/glightbox@3/dist/css/glightbox.min.css',
			[],
			null
		);
		wp_enqueue_script(
			'glightbox',
			'https://cdn.jsdelivr.net/npm/glightbox@3/dist/glightbox.min.js',
			[],
			'3',
			[ 'strategy' => 'defer', 'in_footer' => true ]
		);
	}

	// ═══════════════════════════════════════════
	// Conditional: AJAX Filter (Portfolio List + Blog)
	// ═══════════════════════════════════════════
	if ( is_post_type_archive( 'xanh_project' ) || is_home() ) {
		wp_enqueue_script(
			'xanh-filter',
			"$uri/assets/js/filter.js",
			[ 'xanh-main' ],
			$ver,
			[ 'strategy' => 'defer', 'in_footer' => true ]
		);
		wp_localize_script( 'xanh-filter', 'xanhAjax', [
			'url'   => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'xanh_filter_nonce' ),
		] );
	}

	// ═══════════════════════════════════════════
	// Conditional: Page-specific CSS
	// ═══════════════════════════════════════════
	xanh_enqueue_page_assets( $uri, $ver );
}
add_action( 'wp_enqueue_scripts', 'xanh_enqueue_scripts' );

/**
 * Add resource hints for CDN domains and font preload.
 *
 * @return void
 */
function xanh_resource_hints() {
	// DNS Prefetch + Preconnect for CDN domains.
	echo '<link rel="dns-prefetch" href="//cdn.jsdelivr.net">' . "\n";
	echo '<link rel="dns-prefetch" href="//unpkg.com">' . "\n";
	echo '<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'xanh_resource_hints', 1 );

/**
 * Enqueue page-specific CSS and JS based on current page.
 *
 * @param string $uri Theme URI.
 * @param string $ver Theme version.
 * @return void
 */
function xanh_enqueue_page_assets( $uri, $ver ) {
	$deps_css = [ 'xanh-components' ];
	$deps_js  = [ 'xanh-main' ];

	if ( is_front_page() ) {
		wp_enqueue_style( 'xanh-home', "$uri/assets/css/pages/home.css", $deps_css, $ver );
		wp_enqueue_script( 'xanh-home-js', "$uri/assets/js/pages/home.js", array_merge( $deps_js, [ 'swiper' ] ), $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
	}

	if ( is_page( 'gioi-thieu' ) ) {
		wp_enqueue_style( 'xanh-about', "$uri/assets/css/pages/about.css", $deps_css, $ver );
		wp_enqueue_script( 'xanh-about-js', "$uri/assets/js/pages/about.js", $deps_js, $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
	}

	if ( is_page( 'lien-he' ) ) {
		wp_enqueue_style( 'xanh-contact', "$uri/assets/css/pages/contact.css", $deps_css, $ver );
		wp_enqueue_script( 'xanh-contact-js', "$uri/assets/js/pages/contact.js", $deps_js, $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
	}

	if ( is_home() || is_archive() ) {
		wp_enqueue_style( 'xanh-blog', "$uri/assets/css/pages/blog.css", $deps_css, $ver );
		wp_enqueue_script( 'xanh-blog-js', "$uri/assets/js/pages/blog.js", $deps_js, $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
	}

	if ( is_singular( 'post' ) ) {
		wp_enqueue_style( 'xanh-blog-detail', "$uri/assets/css/pages/blog-detail.css", $deps_css, $ver );
		wp_enqueue_script( 'xanh-blog-detail-js', "$uri/assets/js/pages/blog-detail.js", $deps_js, $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
	}

	if ( is_post_type_archive( 'xanh_project' ) ) {
		wp_enqueue_style( 'xanh-portfolio', "$uri/assets/css/pages/portfolio.css", $deps_css, $ver );
		wp_enqueue_script( 'xanh-portfolio-js', "$uri/assets/js/pages/portfolio.js", $deps_js, $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
	}

	if ( is_singular( 'xanh_project' ) ) {
		wp_enqueue_style( 'xanh-portfolio-detail', "$uri/assets/css/pages/portfolio-detail.css", $deps_css, $ver );
		wp_enqueue_script( 'xanh-portfolio-detail-js', "$uri/assets/js/pages/portfolio-detail.js", $deps_js, $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
	}
}
