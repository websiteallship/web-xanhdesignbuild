<?php
/**
 * Template Part: Section — CTA Contact (Đặt Lịch Tư Vấn).
 *
 * Full-width background image with green-tinted overlay and centered CTA.
 * ACF fields: ctac_eyebrow, ctac_heading, ctac_body,
 *             ctac_bg_image, ctac_btn_text, ctac_btn_url.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$page_id  = get_option( 'page_on_front' );
$eyebrow  = get_field( 'ctac_eyebrow', $page_id ) ?: 'Bắt Đầu Hành Trình';
$heading  = get_field( 'ctac_heading', $page_id ) ?: 'Mọi Tổ Ấm Bình Yên<br>Đều Bắt Đầu Từ<br>Một Cuộc Trò Chuyện.';
$body     = get_field( 'ctac_body', $page_id ) ?: 'Hãy để XANH lắng nghe câu chuyện của bạn — đồng hành từ những ý tưởng sơ khai nhất đến ngày trao tay chiếc chìa khóa.';
$bg_image = get_field( 'ctac_bg_image', $page_id );
$btn_text = get_field( 'ctac_btn_text', $page_id ) ?: 'Đặt Lịch Tư Vấn Riêng';
$btn_url  = get_field( 'ctac_btn_url', $page_id ) ?: '#contact';

// Background image: ACF image field → fallback to static.
$bg_url = $bg_image['url'] ?? esc_url( content_url( '/uploads/2026/03/interior-living.png' ) );
$bg_alt = $bg_image['alt'] ?? '';
$bg_id  = $bg_image['ID'] ?? null;
?>

<section id="cta-contact" class="cta-contact-section" aria-label="Đặt lịch tư vấn">
	<!-- Background Image -->
	<?php if ( $bg_id ) :
		echo wp_get_attachment_image( $bg_id, 'full', false, [
			'class'       => 'cta-contact__bg',
			'aria-hidden' => 'true',
			'loading'     => 'lazy',
		] );
	else : ?>
		<img src="<?php echo esc_url( $bg_url ); ?>"
			alt="<?php echo esc_attr( $bg_alt ); ?>"
			class="cta-contact__bg" aria-hidden="true"
			width="1920" height="1080" loading="lazy" />
	<?php endif; ?>
	<!-- Dark Overlay -->
	<div class="cta-contact__overlay"></div>

	<!-- Content -->
	<div class="site-container cta-contact__container">
		<p class="anim-fade-up cta-contact__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>

		<h2 class="anim-fade-up cta-contact__heading">
			<?php echo wp_kses_post( $heading ); ?>
		</h2>

		<?php if ( $body ) : ?>
			<p class="anim-fade-up cta-contact__body">
				<?php echo esc_html( $body ); ?>
			</p>
		<?php endif; ?>

		<a href="<?php echo esc_url( $btn_url ); ?>" class="anim-fade-up btn btn--primary cta-contact__btn group"
			<?php echo ( str_starts_with( $btn_url, 'http' ) ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
			<span><?php echo esc_html( $btn_text ); ?></span>
			<i data-lucide="phone" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5"></i>
		</a>
	</div>
</section>
