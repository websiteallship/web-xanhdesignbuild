<?php
/**
 * Template Part: Hero — Contact Page.
 *
 * Full-viewport hero with background image, gradient overlay,
 * breadcrumb, heading, and subtitle. Mirrors wireframe exactly.
 *
 * ACF fields: contact_hero_image, contact_hero_title, contact_hero_subtitle.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$hero_title    = get_field( 'contact_hero_title' ) ?: 'Mọi Công Trình Bền Vững Đều Bắt Đầu<br>Từ Một <em>Cuộc Trò Chuyện.</em>';
$hero_subtitle = get_field( 'contact_hero_subtitle' ) ?: 'Bạn đang ấp ủ một không gian sống mới nhưng còn nhiều trăn trở? Hãy chia sẻ với chúng tôi — kỹ sư trưởng XANH sẽ đồng hành cùng bạn từ những bước đầu tiên.';
$hero_image    = xanh_get_image( 'contact_hero_image' );

// Fallback image.
$img_url = $hero_image['url'] ?? '/wp-content/uploads/2026/03/cta-bg.png';
$img_alt = $hero_image['alt'] ?? 'Buổi tư vấn kiến trúc tại XANH Design & Build';
$img_id  = $hero_image['ID'] ?? null;
?>

<section class="contact-hero" id="contact-hero">
	<!-- Background image -->
	<div class="contact-hero__bg" id="hero-bg">
		<?php if ( $img_id ) :
			echo wp_get_attachment_image( $img_id, 'full', false, [
				'class'         => 'w-full h-full object-cover',
				'loading'       => 'eager',
				'fetchpriority' => 'high',
				'decoding'      => 'async',
				'sizes'         => '100vw',
			] );
		else : ?>
			<img src="<?php echo esc_url( $img_url ); ?>"
				alt="<?php echo esc_attr( $img_alt ); ?>"
				width="1920" height="960"
				loading="eager"
				fetchpriority="high"
				decoding="async">
		<?php endif; ?>
	</div>
	<!-- Gradient overlay (portfolio style) -->
	<div class="contact-hero__overlay"></div>

	<!-- Centered content -->
	<div class="contact-hero__center">
		<div class="contact-hero__content-center">
			<!-- Breadcrumb -->
			<nav class="contact-hero__breadcrumb contact-hero-el" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Trang Chủ', 'xanh' ); ?></a>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
				<span><?php esc_html_e( 'Liên Hệ', 'xanh' ); ?></span>
			</nav>
			<h1 class="section-title contact-hero__title contact-hero-el">
				<?php echo wp_kses_post( $hero_title ); ?>
			</h1>
			<p class="contact-hero__subtitle contact-hero-el">
				<?php echo esc_html( wp_strip_all_tags( $hero_subtitle ) ); ?>
			</p>
		</div>
	</div>

</section>
