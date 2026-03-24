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
	wp_enqueue_style( 'xanh-preloader', "$uri/assets/css/components/preloader.css", [ 'xanh-variables' ], $ver );

	// ═══════════════════════════════════════════
	// CSS + JS: Popup Modal (global, conditional)
	// ═══════════════════════════════════════════
	$popup_count = get_transient( 'xanh_popup_count' );
	if ( false === $popup_count ) {
		$popup_count = (int) wp_count_posts( 'xanh_popup' )->publish;
		set_transient( 'xanh_popup_count', $popup_count, HOUR_IN_SECONDS );
	}

	if ( $popup_count > 0 ) {
		wp_enqueue_style( 'xanh-popup-modal', "$uri/assets/css/components/popup-modal.css", [ 'xanh-variables' ], $ver );
		wp_enqueue_script(
			'xanh-popup-modal',
			"$uri/assets/js/components/popup-modal.js",
			[],
			$ver,
			[ 'strategy' => 'defer', 'in_footer' => true ]
		);
	}

	// ═══════════════════════════════════════════
	// JS: Preloader (load early, before vendor scripts)
	// ═══════════════════════════════════════════
	wp_enqueue_script(
		'xanh-preloader',
		"$uri/assets/js/components/preloader.js",
		[ 'gsap' ],
		$ver,
		[ 'strategy' => 'defer', 'in_footer' => true ]
	);

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
		'https://cdn.jsdelivr.net/npm/lucide@0.468.0/dist/umd/lucide.min.js',
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
			'https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.0/css/glightbox.min.css',
			[],
			null
		);
		wp_enqueue_script(
			'glightbox',
			'https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.0/js/glightbox.min.js',
			[],
			'3.3.0',
			[ 'strategy' => 'defer', 'in_footer' => true ]
		);
	}

	// ═══════════════════════════════════════════
	// Conditional: AJAX data (used by portfolio.js / blog.js)
	// ═══════════════════════════════════════════
	if ( is_post_type_archive( 'xanh_project' ) || is_home() ) {
		// xanhAjax is localized on the page-specific JS handle below.
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
	echo '<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>' . "\n";
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";

	// Hero image preload for LCP (Home & About)
	$lcp_src = null;

	if ( is_front_page() ) {
		$hero_slides = function_exists( 'get_field' ) ? get_field( 'hero_slides' ) : null;
		if ( $hero_slides && isset( $hero_slides[0]['image']['ID'] ) ) {
			$lcp_src = wp_get_attachment_image_url( $hero_slides[0]['image']['ID'], 'full' );
		} else {
			$lcp_src = site_url( '/wp-content/uploads/2026/03/hero-house.png' );
		}
	} elseif ( is_page( 'gioi-thieu' ) ) {
		$about_image = function_exists( 'get_field' ) ? get_field( 'about_hero_image' ) : null;
		if ( is_array( $about_image ) && isset( $about_image['ID'] ) ) {
			$lcp_src = wp_get_attachment_image_url( $about_image['ID'], 'full' );
		} elseif ( is_numeric( $about_image ) && $about_image ) {
			$lcp_src = wp_get_attachment_image_url( $about_image, 'full' );
		} else {
			$lcp_src = site_url( '/wp-content/uploads/2026/03/about-hero-bg.png' );
		}
	} elseif ( is_post_type_archive( 'xanh_project' ) || is_tax( 'project_type' ) ) {
		$pf_image = function_exists( 'get_field' ) ? get_field( 'portfolio_hero_image', 'option' ) : null;
		if ( is_array( $pf_image ) && isset( $pf_image['ID'] ) ) {
			$lcp_src = wp_get_attachment_image_url( $pf_image['ID'], 'full' );
		} else {
			$lcp_src = site_url( '/wp-content/uploads/2026/03/about-hero-bg.png' );
		}
	} elseif ( is_post_type_archive( 'xanh_service' ) ) {
		$sv_image = function_exists( 'get_field' ) ? get_field( 'services_hero_image', 'option' ) : null;
		if ( is_array( $sv_image ) && isset( $sv_image['ID'] ) ) {
			$lcp_src = wp_get_attachment_image_url( $sv_image['ID'], 'full' );
		} else {
			$lcp_src = site_url( '/wp-content/uploads/2026/03/about-hero-bg.png' );
		}
	} elseif ( is_page( 'lien-he' ) ) {
		$ct_image = function_exists( 'get_field' ) ? get_field( 'contact_hero_image' ) : null;
		if ( is_array( $ct_image ) && isset( $ct_image['ID'] ) ) {
			$lcp_src = wp_get_attachment_image_url( $ct_image['ID'], 'full' );
		} else {
			$lcp_src = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1920&q=80';
		}
	} elseif ( is_home() || is_category() ) {
		$blog_image = function_exists( 'get_field' ) ? get_field( 'blog_hero_image', 'option' ) : null;
		if ( is_array( $blog_image ) && isset( $blog_image['ID'] ) ) {
			$lcp_src = wp_get_attachment_image_url( $blog_image['ID'], 'full' );
		} else {
			$lcp_src = site_url( '/wp-content/uploads/2026/03/interior-living.png' );
		}
	} elseif ( is_singular( 'post' ) ) {
		// Blog detail: preload the featured image.
		if ( has_post_thumbnail() ) {
			$lcp_src = get_the_post_thumbnail_url( get_the_ID(), 'full' );
		}
	} elseif ( is_singular( 'xanh_project' ) ) {
		$pd_hero = function_exists( 'get_field' ) ? get_field( 'pd_hero_image' ) : null;
		if ( is_array( $pd_hero ) && isset( $pd_hero['ID'] ) ) {
			$lcp_src = wp_get_attachment_image_url( $pd_hero['ID'], 'full' );
		} elseif ( has_post_thumbnail() ) {
			$lcp_src = get_the_post_thumbnail_url( get_the_ID(), 'full' );
		}
	} elseif ( is_singular( 'xanh_service' ) ) {
		$sv_hero = function_exists( 'get_field' ) ? get_field( 'sv_hero_image' ) : null;
		if ( is_array( $sv_hero ) && isset( $sv_hero['ID'] ) ) {
			$lcp_src = wp_get_attachment_image_url( $sv_hero['ID'], 'full' );
		} elseif ( has_post_thumbnail() ) {
			$lcp_src = get_the_post_thumbnail_url( get_the_ID(), 'full' );
		}
	}

	if ( $lcp_src ) {
		echo '<link rel="preload" as="image" href="' . esc_url( $lcp_src ) . '" fetchpriority="high">' . "\n";
	}
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

	if ( ( is_home() || is_archive() ) && ! is_post_type_archive( 'xanh_project' ) && ! is_post_type_archive( 'xanh_service' ) ) {
		wp_enqueue_style( 'xanh-blog', "$uri/assets/css/pages/blog.css", $deps_css, $ver );
		wp_enqueue_script( 'xanh-blog-js', "$uri/assets/js/pages/blog.js", $deps_js, $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
		wp_localize_script( 'xanh-blog-js', 'xanhBlogAjax', [
			'url'   => set_url_scheme( admin_url( 'admin-ajax.php' ), wp_parse_url( home_url(), PHP_URL_SCHEME ) ),
			'nonce' => wp_create_nonce( 'xanh_blog_nonce' ),
		] );
	}

	if ( is_singular( 'post' ) ) {
		wp_enqueue_style( 'xanh-blog', "$uri/assets/css/pages/blog.css", $deps_css, $ver );
		wp_enqueue_style( 'xanh-blog-detail', "$uri/assets/css/pages/blog-detail.css", [ 'xanh-blog' ], $ver );
		/* blog.js not needed on single posts — it handles search, load more,
		   and lead magnet features for the blog listing page only. */
		wp_enqueue_script( 'xanh-blog-detail-js', "$uri/assets/js/pages/blog-detail.js", $deps_js, $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
	}

	if ( is_post_type_archive( 'xanh_project' ) || is_tax( 'project_type' ) ) {
		wp_enqueue_style( 'xanh-portfolio', "$uri/assets/css/pages/portfolio.css", $deps_css, $ver );
		wp_enqueue_script( 'xanh-portfolio-js', "$uri/assets/js/pages/portfolio.js", $deps_js, $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
		wp_localize_script( 'xanh-portfolio-js', 'xanhAjax', [
			'url'   => set_url_scheme( admin_url( 'admin-ajax.php' ), wp_parse_url( home_url(), PHP_URL_SCHEME ) ),
			'nonce' => wp_create_nonce( 'xanh_filter_nonce' ),
		] );
	}

	if ( is_singular( 'xanh_project' ) ) {
		wp_enqueue_style( 'xanh-portfolio-detail', "$uri/assets/css/pages/portfolio-detail.css", $deps_css, $ver );
		wp_enqueue_script( 'xanh-portfolio-detail-js', "$uri/assets/js/pages/portfolio-detail.js", array_merge( $deps_js, [ 'swiper', 'glightbox' ] ), $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
	}

	if ( is_singular( 'xanh_service' ) ) {
		wp_enqueue_style( 'xanh-service-detail', "$uri/assets/css/pages/service-detail.css", $deps_css, $ver );
		wp_enqueue_script( 'xanh-service-detail-js', "$uri/assets/js/pages/service-detail.js", $deps_js, $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
	}

	if ( is_post_type_archive( 'xanh_service' ) ) {
		wp_enqueue_style( 'xanh-services', "$uri/assets/css/pages/services.css", $deps_css, $ver );
		wp_enqueue_script( 'xanh-services-js', "$uri/assets/js/pages/services.js", $deps_js, $ver, [ 'strategy' => 'defer', 'in_footer' => true ] );
		wp_localize_script( 'xanh-services-js', 'xanhAjax', [
			'url'   => set_url_scheme( admin_url( 'admin-ajax.php' ), wp_parse_url( home_url(), PHP_URL_SCHEME ) ),
			'nonce' => wp_create_nonce( 'xanh_services_nonce' ),
		] );
	}
}
