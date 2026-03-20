<?php
/**
 * Theme Setup — add_theme_support, menus, image sizes, WP bloat removal.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @return void
 */
function xanh_theme_setup() {
	// Add default WP title tag support.
	add_theme_support( 'title-tag' );

	// Enable post thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Custom logo support.
	add_theme_support( 'custom-logo', [
		'height'      => 80,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	] );

	// HTML5 markup support.
	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
		'navigation-widgets',
	] );

	// Register navigation menus.
	register_nav_menus( [
		'primary'      => __( 'Menu Chính', 'xanh' ),
		'footer'       => __( 'Menu Footer', 'xanh' ),
		'footer_col_2' => __( 'Footer — Cột 2 (Khám Phá)', 'xanh' ),
		'footer_col_3' => __( 'Footer — Cột 3 (Dịch Vụ)', 'xanh' ),
	] );
}
add_action( 'after_setup_theme', 'xanh_theme_setup' );

/**
 * Register widget areas (sidebars).
 *
 * @return void
 */
function xanh_register_sidebars() {
	register_sidebar( [
		'name'          => __( 'Footer Widgets', 'xanh' ),
		'id'            => 'footer-widgets',
		'description'   => __( 'Widget area hiển thị ở footer.', 'xanh' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	] );

	register_sidebar( [
		'name'          => __( 'Blog Sidebar', 'xanh' ),
		'id'            => 'blog-sidebar',
		'description'   => __( 'Sidebar cho trang Blog.', 'xanh' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	] );
}
add_action( 'widgets_init', 'xanh_register_sidebars' );

/**
 * Register custom image sizes for the theme.
 *
 * @return void
 */
function xanh_custom_image_sizes() {
	add_image_size( 'xanh-hero', 1920, 1080, true );
	add_image_size( 'xanh-card', 640, 480, true );
	add_image_size( 'xanh-thumb', 400, 300, true );
	add_image_size( 'xanh-partner', 320, 120, false );
	add_image_size( 'xanh-team', 400, 500, true );

	// Remove default large sizes (save storage).
	remove_image_size( '1536x1536' );
	remove_image_size( '2048x2048' );
}
add_action( 'after_setup_theme', 'xanh_custom_image_sizes' );

/**
 * Remove unnecessary WP head bloat.
 *
 * Hooked to after_setup_theme for remove_action calls.
 *
 * @return void
 */
function xanh_remove_wp_bloat() {
	// Emoji — saves ~20KB.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	// oEmbed.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );

	// Meta tags — security + cleanup.
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'wp_generator' );

	// Feed links (not needed for this theme).
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );

	// Global Styles inline CSS (classic theme — WP 6.x+).
	// WP registers on both wp_enqueue_scripts AND wp_footer (priority 1).
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
}
add_action( 'after_setup_theme', 'xanh_remove_wp_bloat' );

/**
 * Send security headers via PHP (works on both Nginx and Apache).
 *
 * Complements .htaccess headers for Apache/LiteSpeed environments.
 * Only outputs on frontend to avoid interfering with wp-admin iframes.
 *
 * @return void
 */
function xanh_security_headers() {
	if ( is_admin() ) {
		return;
	}

	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'X-XSS-Protection: 1; mode=block' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(self)' );
}
add_action( 'send_headers', 'xanh_security_headers' );

/**
 * Dequeue block library styles (classic theme — no Gutenberg frontend).
 *
 * @return void
 */
function xanh_remove_block_styles() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'xanh_remove_block_styles', 100 );

/**
 * Disable XML-RPC for security + performance.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Increase Heartbeat interval to reduce AJAX load.
 *
 * @param  array $settings Heartbeat settings.
 * @return array Modified settings.
 */
function xanh_heartbeat_settings( $settings ) {
	$settings['interval'] = 60;
	return $settings;
}
add_filter( 'heartbeat_settings', 'xanh_heartbeat_settings' );

/**
 * Disable Heartbeat on frontend entirely.
 *
 * @return void
 */
function xanh_disable_frontend_heartbeat() {
	if ( ! is_admin() ) {
		wp_deregister_script( 'heartbeat' );
	}
}
add_action( 'init', 'xanh_disable_frontend_heartbeat', 1 );

/**
 * Move jQuery to footer on frontend (non-render-blocking).
 *
 * Theme uses vanilla ES6+ but Fluent Forms still needs jQuery.
 * Moving to footer keeps it out of <head> so it won't block
 * first paint — same perf benefit, zero console errors.
 *
 * @return void
 */
function xanh_move_jquery_to_footer() {
	if ( ! is_admin() && ! is_customize_preview() ) {
		wp_scripts()->add_data( 'jquery',      'group', 1 );
		wp_scripts()->add_data( 'jquery-core', 'group', 1 );
	}
}
add_action( 'wp_enqueue_scripts', 'xanh_move_jquery_to_footer', 1 );

/**
 * Lazy-load third-party widgets (Zalo) — 3 s after window.load.
 *
 * Prevents render-blocking by deferring non-critical third-party JS.
 *
 * @return void
 */
function xanh_delayed_third_party() {
	if ( is_admin() ) {
		return;
	}
	?>
	<script>
	window.addEventListener('load', function() {
		setTimeout(function() {
			var s = document.createElement('script');
			s.src = 'https://sp.zalo.me/plugins/sdk.js';
			s.async = true;
			document.body.appendChild(s);
		}, 3000);
	});
	</script>
	<?php
}
add_action( 'wp_footer', 'xanh_delayed_third_party', 99 );

/**
 * Output JSON-LD structured data in wp_head.
 *
 * - Homepage: Organization + WebSite
 * - Sub-pages: BreadcrumbList
 *
 * @return void
 */
function xanh_output_schema_jsonld() {
	if ( is_admin() ) {
		return;
	}

	$site_name = get_bloginfo( 'name' );
	$site_url  = home_url( '/' );
	$logo_url  = '';

	// Get logo from custom logo or fallback.
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
	}

	$schemas = [];

	// ── Homepage: Organization + WebSite ──
	if ( is_front_page() ) {
		$org = [
			'@context'    => 'https://schema.org',
			'@type'       => 'Organization',
			'name'        => esc_html( $site_name ),
			'legalName'   => 'CÔNG TY CỔ PHẦN ĐẦU TƯ THIẾT BỊ VÀ GIẢI PHÁP XANH',
			'url'         => esc_url( $site_url ),
			'description' => get_bloginfo( 'description' ),
			'telephone'   => '0978.303.025',
			'email'       => 'contact@xanhdesignbuild.vn',
			'taxID'       => '4202048146',
			'address'     => [
				'@type'           => 'PostalAddress',
				'streetAddress'   => '49 Nguyễn Tất Thành',
				'addressLocality' => 'Phường Phước Long',
				'addressRegion'   => 'Khánh Hòa',
				'addressCountry'  => 'VN',
			],
		];

		if ( $logo_url ) {
			$org['logo'] = esc_url( $logo_url );
		}

		// Social profiles.
		$social = [];
		$fb = get_field( 'social_facebook', 'option' );
		$ig = get_field( 'social_instagram', 'option' );
		$yt = get_field( 'social_youtube', 'option' );
		if ( $fb ) $social[] = esc_url( $fb );
		if ( $ig ) $social[] = esc_url( $ig );
		if ( $yt ) $social[] = esc_url( $yt );
		if ( $social ) {
			$org['sameAs'] = $social;
		}

		$schemas[] = $org;

		$schemas[] = [
			'@context'        => 'https://schema.org',
			'@type'           => 'WebSite',
			'name'            => esc_html( $site_name ),
			'url'             => esc_url( $site_url ),
			'potentialAction' => [
				'@type'       => 'SearchAction',
				'target'      => esc_url( $site_url ) . '?s={search_term_string}',
				'query-input' => 'required name=search_term_string',
			],
		];
	}

	// ── Sub-pages: BreadcrumbList ──
	if ( ! is_front_page() ) {
		$breadcrumb_items = [];
		$position = 1;

		$breadcrumb_items[] = [
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => 'Trang chủ',
			'item'     => esc_url( $site_url ),
		];

		if ( is_page() ) {
			$breadcrumb_items[] = [
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => get_the_title(),
				'item'     => esc_url( get_permalink() ),
			];
		} elseif ( is_singular( 'post' ) ) {
			$breadcrumb_items[] = [
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => 'Blog',
				'item'     => esc_url( get_permalink( get_option( 'page_for_posts' ) ) ),
			];
			$breadcrumb_items[] = [
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => get_the_title(),
				'item'     => esc_url( get_permalink() ),
			];
		} elseif ( is_singular( 'xanh_project' ) ) {
			$breadcrumb_items[] = [
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => 'Dự Án',
				'item'     => esc_url( get_post_type_archive_link( 'xanh_project' ) ),
			];
			$breadcrumb_items[] = [
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => get_the_title(),
				'item'     => esc_url( get_permalink() ),
			];
		} elseif ( is_post_type_archive( 'xanh_project' ) ) {
			$breadcrumb_items[] = [
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => 'Dự Án',
				'item'     => esc_url( get_post_type_archive_link( 'xanh_project' ) ),
			];
		} elseif ( is_home() ) {
			$breadcrumb_items[] = [
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => 'Blog',
				'item'     => esc_url( get_permalink( get_option( 'page_for_posts' ) ) ),
			];
		}

		if ( count( $breadcrumb_items ) > 1 ) {
			$schemas[] = [
				'@context'        => 'https://schema.org',
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $breadcrumb_items,
			];
		}
	}

	// ── Output all schemas ──
	foreach ( $schemas as $schema ) {
		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'xanh_output_schema_jsonld', 5 );

/**
 * Custom Document Title Separator
 */
function xanh_custom_title_separator( $sep ) {
	return '|';
}
add_filter( 'document_title_separator', 'xanh_custom_title_separator' );

/**
 * Custom Document Title Parts
 */
function xanh_custom_title_parts( $title ) {
	if ( isset( $title['site'] ) ) {
		$title['site'] = 'XANH - Design & Build';
	}
	if ( is_singular( 'xanh_service' ) ) {
		if ( get_the_ID() == 119 ) {
			$title['title'] = 'Dịch Vụ Thiết Kế Kiến Trúc & Nội Thất';
		} elseif ( get_the_ID() == 120 ) {
			$title['title'] = 'Dịch Vụ Thi Công Xây Dựng Trọn Gói';
		} elseif ( get_the_ID() == 121 ) {
			$title['title'] = 'Sản Xuất & Thi Công Nội Thất';
		} elseif ( get_the_ID() == 122 ) {
			$title['title'] = 'Cải Tạo & Nâng Cấp Công Trình';
		}
	}
	return $title;
}
add_filter( 'document_title_parts', 'xanh_custom_title_parts' );

/**
 * Output SEO Meta Tags (Description, Open Graph, Twitter)
 */
function xanh_seo_meta_tags() {
	if ( is_admin() ) {
		return;
	}

	if ( is_singular( 'xanh_service' ) ) {
		$post_id = get_the_ID();
		
		$seo_title = get_the_title();
		$desc = wp_trim_words( wp_strip_all_tags( get_field('sv_hero_desc', $post_id) ?: get_the_excerpt() ), 25 );
		
		if ( $post_id == 119 ) {
			$seo_title = 'Dịch Vụ Thiết Kế Kiến Trúc & Nội Thất';
			$desc = 'Dịch vụ thiết kế kiến trúc và nội thất. Cam kết phối cảnh 3D sát thực tế 98%, hồ sơ kỹ thuật triệt để. Khám Phá Dự Toán Của Bạn ngay với XANH!';
		} elseif ( $post_id == 120 ) {
			$seo_title = 'Dịch Vụ Thi Công Xây Dựng Trọn Gói';
			$desc = 'Dịch vụ thi công xây dựng trọn gói tại Khánh Hoà. Cam kết 100% đúng tiến độ, 0% phát sinh chi phí, bảo hành kết cấu 5 năm. Tìm hiểu ngay!';
		} elseif ( $post_id == 121 ) {
			$seo_title = 'Sản Xuất & Thi Công Nội Thất';
			$desc = 'Dịch vụ sản xuất và thi công nội thất tinh xảo. Xưởng mộc trực tiếp 2000m2, thi công sắc nét 98% bản vẽ, bảo hành gỗ 3 năm. Xem xưởng ngay!';
		} elseif ( $post_id == 122 ) {
			$seo_title = 'Cải Tạo & Nâng Cấp Công Trình';
			$desc = 'Dịch vụ cải tạo công trình chuyên sâu. Xử lý triệt để thấm dột, kiến tạo nét đẹp không gian Warm Luxury. Tối ưu kinh phí 40%. Xem dự án ngay!';
		}

		$title = $seo_title . ' | XANH - Design & Build';
		$url   = get_permalink();
		
		$image     = function_exists('xanh_get_image') ? xanh_get_image( 'sv_hero_image', $post_id ) : false;
		$image_url = $image ? $image['url'] : get_the_post_thumbnail_url( $post_id, 'full' );

		echo '<!-- Custom SEO Meta Tags -->'."\n";
		echo '<meta name="description" content="' . esc_attr( $desc ) . '" />'."\n";
		echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />'."\n";
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . '" />'."\n";
		echo '<meta property="og:type" content="article" />'."\n";
		echo '<meta property="og:url" content="' . esc_url( $url ) . '" />'."\n";
		if ( $image_url ) {
			echo '<meta property="og:image" content="' . esc_url( $image_url ) . '" />'."\n";
			echo '<meta name="twitter:image" content="' . esc_url( $image_url ) . '" />'."\n";
		}
		echo '<meta property="og:site_name" content="XANH - Design & Build" />'."\n";
		echo '<meta name="twitter:card" content="summary_image_large" />'."\n";
		echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />'."\n";
		echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '" />'."\n";
		echo '<!-- End Custom SEO Meta Tags -->'."\n";
	}
}
add_action( 'wp_head', 'xanh_seo_meta_tags', 1 );

