<?php
/**
 * Header Template — Site header with desktop nav and mobile drawer.
 *
 * Converted from: wireframes/_shared/header.html
 * Preserves: Tailwind classes, GSAP selectors, data-lucide, aria-* attributes, inline SVGs.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$xanh_logo_white = XANH_THEME_URI . '/assets/images/logo-white.svg';
$xanh_logo_dark  = XANH_THEME_URI . '/assets/images/logo-dark.svg';
$xanh_hotline    = xanh_get_hotline();
$xanh_socials    = xanh_get_social_links();
$xanh_cta_text   = xanh_get_option( 'xanh_header_cta_text', __( 'Đặt Lịch Tư Vấn', 'xanh' ) );
$xanh_cta_url    = xanh_get_option( 'xanh_header_cta_url' );
$xanh_cta_url    = $xanh_cta_url ? $xanh_cta_url : home_url( '/lien-he/' );

// Navigation items — fallback when no WP menu is assigned to 'primary'.
$xanh_nav_items = [
	[ 'label' => __( 'Trang Chủ', 'xanh' ), 'url' => home_url( '/' ) ],
	[ 'label' => __( 'Giới Thiệu', 'xanh' ), 'url' => home_url( '/gioi-thieu/' ) ],
	[ 'label' => __( 'Dự Án', 'xanh' ),      'url' => get_post_type_archive_link( 'xanh_project' ) ],
	[ 'label' => __( 'Dịch Vụ', 'xanh' ),    'url' => home_url( '/dich-vu/' ) ],
	[ 'label' => __( 'Blog', 'xanh' ),        'url' => get_permalink( get_option( 'page_for_posts' ) ) ],
];
$xanh_has_wp_menu = has_nav_menu( 'primary' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php // Preloader — SVG path line drawing (ACF controlled) ?>
<?php get_template_part( 'template-parts/components/preloader' ); ?>

<!-- Skip Navigation — WCAG 2.4.1 -->
<a href="#main-content" class="skip-link">
	<?php esc_html_e( 'Chuyển đến nội dung chính', 'xanh' ); ?>
</a>

<!-- ============================================= -->
<!-- HEADER / NAVIGATION                           -->
<!-- ============================================= -->
<header id="site-header" class="fixed top-0 left-0 w-full z-50 transition-all duration-500">
	<nav class="relative flex items-center justify-between px-5 md:px-8 lg:px-12 xl:px-16 py-4 lg:py-5">

		<!-- Logo: Desktop left | Mobile centered -->
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
		   class="relative flex items-center lg:static absolute left-1/2 -translate-x-1/2 lg:translate-x-0 lg:left-auto group"
		   id="logo-link">
			<!-- White Logo (Default/transparent header) -->
			<img src="<?php echo esc_url( $xanh_logo_white ); ?>"
			     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
			     class="h-10 lg:h-12 w-auto logo-white transition-opacity duration-300"
			     width="200" height="36" />
			<!-- Normal/Dark Logo (Scrolled header) -->
			<img src="<?php echo esc_url( $xanh_logo_dark ); ?>"
			     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
			     class="h-10 lg:h-12 w-auto absolute top-1/2 -translate-y-1/2 left-0 opacity-0 logo-dark transition-opacity duration-300"
			     width="200" height="36" />
		</a>

		<!-- Desktop Menu -->
		<?php if ( $xanh_has_wp_menu ) : ?>
			<?php
			wp_nav_menu( [
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'hidden lg:flex items-center gap-10',
				'menu_id'        => 'desktop-menu',
				'depth'          => 1,
				'walker'         => new Xanh_Nav_Walker( 'desktop' ),
			] );
			?>
		<?php else : ?>
			<ul class="hidden lg:flex items-center gap-10" id="desktop-menu">
				<?php foreach ( $xanh_nav_items as $xanh_item ) : ?>
					<li>
						<a href="<?php echo esc_url( $xanh_item['url'] ); ?>"
						   class="nav-link text-white/90 text-sm font-medium uppercase tracking-[0.1em] hover:text-white transition-colors duration-300 relative">
							<?php echo esc_html( $xanh_item['label'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<!-- Desktop CTA -->
		<a href="<?php echo esc_url( $xanh_cta_url ); ?>"
		   class="hidden lg:inline-flex btn btn--primary py-2.5 px-6 min-w-0 text-sm tracking-[0.1em]"
		   id="header-cta">
			<i data-lucide="phone" class="btn__icon"></i>
			<?php echo esc_html( $xanh_cta_text ); ?>
		</a>

	</nav>
</header>

<!-- Mobile Menu Toggle — Fixed above drawer for close functionality -->
<button class="lg:hidden fixed top-4 right-5 z-[1010] w-10 h-10 flex flex-col justify-center items-center gap-[6px]"
        id="mobile-menu-btn"
        aria-label="<?php esc_attr_e( 'Toggle Menu', 'xanh' ); ?>"
        aria-expanded="false"
        aria-controls="mobile-drawer">
	<span class="hamburger-line block w-5 h-[1.5px] bg-white transition-all duration-300 ease-out origin-center"></span>
	<span class="hamburger-line block w-5 h-[1.5px] bg-white transition-all duration-300 ease-out origin-center"></span>
	<span class="hamburger-line block w-5 h-[1.5px] bg-white transition-all duration-300 ease-out origin-center"></span>
</button>

<!-- ============================================= -->
<!-- MOBILE DRAWER MENU                            -->
<!-- ============================================= -->
<!-- Overlay backdrop -->
<div id="mobile-overlay"
     class="fixed inset-0 bg-black/60 z-[998] opacity-0 pointer-events-none transition-opacity duration-400"></div>

<!-- Drawer panel -->
<aside id="mobile-drawer"
       class="fixed top-0 right-0 h-full w-[280px] sm:w-[320px] bg-primary z-[999] flex flex-col translate-x-full transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
       aria-label="<?php esc_attr_e( 'Mobile navigation', 'xanh' ); ?>">

	<!-- Drawer Header -->
	<div class="flex items-center justify-start px-6 py-5 border-b-[0.5px] border-white/30">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block">
			<img src="<?php echo esc_url( $xanh_logo_white ); ?>"
			     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
			     class="h-9 w-auto"
			     width="180" height="28" />
		</a>
	</div>

	<!-- Nav Items -->
	<nav class="flex-1 overflow-y-auto py-4">
		<?php if ( $xanh_has_wp_menu ) : ?>
			<?php
			wp_nav_menu( [
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'flex flex-col',
				'menu_id'        => 'mobile-menu',
				'depth'          => 1,
				'walker'         => new Xanh_Nav_Walker( 'mobile' ),
			] );
			?>
		<?php else : ?>
			<ul class="flex flex-col">
				<?php foreach ( $xanh_nav_items as $xanh_item ) : ?>
					<li>
						<a href="<?php echo esc_url( $xanh_item['url'] ); ?>"
						   class="mobile-nav-link flex items-center justify-between px-6 py-4 text-white/90 text-[11px] md:text-xs font-semibold uppercase tracking-[0.15em] border-b-[0.5px] border-white/20 hover:bg-white/10 hover:text-white transition-colors duration-300">
							<?php echo esc_html( $xanh_item['label'] ); ?>
							<i data-lucide="chevron-right" class="w-3.5 h-3.5 text-white/40"></i>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<!-- CTA inside drawer -->
		<div class="px-6 mt-6">
			<a href="<?php echo esc_url( $xanh_cta_url ); ?>"
			   class="btn btn--primary w-full !min-w-0 !py-2.5 text-xs">
				<i data-lucide="phone" class="btn__icon"></i>
				<?php echo esc_html( $xanh_cta_text ); ?>
			</a>
		</div>
	</nav>

	<!-- Social Icons footer -->
	<div class="px-6 py-5 border-t-[0.5px] border-white/20">
		<div class="flex items-center gap-5">
			<?php if ( ! empty( $xanh_socials['facebook'] ) ) : ?>
				<a href="<?php echo esc_url( $xanh_socials['facebook'] ); ?>"
				   class="text-white/50 hover:text-white transition-colors duration-300"
				   aria-label="Facebook" target="_blank" rel="noopener noreferrer">
					<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
						<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
					</svg>
				</a>
			<?php endif; ?>
			<?php if ( ! empty( $xanh_socials['instagram'] ) ) : ?>
				<a href="<?php echo esc_url( $xanh_socials['instagram'] ); ?>"
				   class="text-white/50 hover:text-white transition-colors duration-300"
				   aria-label="Instagram" target="_blank" rel="noopener noreferrer">
					<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
						<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
					</svg>
				</a>
			<?php endif; ?>
			<?php if ( ! empty( $xanh_socials['youtube'] ) ) : ?>
				<a href="<?php echo esc_url( $xanh_socials['youtube'] ); ?>"
				   class="text-white/50 hover:text-white transition-colors duration-300"
				   aria-label="YouTube" target="_blank" rel="noopener noreferrer">
					<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
						<path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
					</svg>
				</a>
			<?php endif; ?>
		</div>
		<p class="text-white/30 text-xs mt-3 tracking-wide">
			&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
		</p>
	</div>
</aside>
