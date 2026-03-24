<?php
/**
 * Template Part: Section SV CTA (S8).
 *
 * Full-width call-to-action with background image + overlay.
 * Uses .btn, .btn--primary, .btn--ghost from components.css.
 * Uses .anim-fade-up from components.css.
 *
 * ACF fields: sv_cta_title, sv_cta_subtitle, sv_cta_bg_image,
 *             sv_cta_btn_text, sv_cta_btn_link,
 *             sv_cta_ghost_text, sv_cta_ghost_link.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cta_title    = get_field( 'sv_cta_title' ) ?: 'Bạn Cũng Muốn Một Không Gian Sống<br /> Trọn Vẹn Và Minh Bạch?';
$cta_subtitle = get_field( 'sv_cta_subtitle' ) ?: 'Hãy bắt đầu hành trình kiến tạo tổ ấm cùng đội ngũ XANH — minh bạch từ dự toán đến bàn giao.';
$bg_image     = xanh_get_image( 'sv_cta_bg_image' );
$btn_text     = get_field( 'sv_cta_btn_text' ) ?: 'Khám Phá Dự Toán Của Bạn';
$btn_link     = get_field( 'sv_cta_btn_link' ) ?: home_url( '/lien-he/' );
$ghost_text   = get_field( 'sv_cta_ghost_text' ) ?: 'Chat Với Kỹ Sư Trưởng';
$ghost_link   = get_field( 'sv_cta_ghost_link' ) ?: home_url( '/lien-he/' );

// Fallback bg image
$bg_url = '';
if ( $bg_image ) {
	$bg_url = $bg_image['url'];
} else {
	$bg_url = site_url( '/wp-content/uploads/2026/03/project-after-1.png' );
}
?>

<section id="service-cta" class="s8-cta">
	<div class="s8-cta__bg">
		<?php if ( $bg_image ) : ?>
			<?php
			echo wp_get_attachment_image( $bg_image['ID'], 'large', false, [
				'alt'     => esc_attr( wp_strip_all_tags( $cta_title ) ),
				'loading' => 'lazy',
			] );
			?>
		<?php else : ?>
			<img src="<?php echo esc_url( $bg_url ); ?>"
				alt="Bắt đầu hành trình kiến tạo tổ ấm cùng XANH" loading="lazy" />
		<?php endif; ?>
		<div class="s8-cta__overlay"></div>
	</div>

	<div class="site-container s8-cta__content anim-fade-up">
		<h2 class="section-title section-title--light s8-cta__title">
			<?php echo wp_kses_post( $cta_title ); ?>
		</h2>
		<p class="s8-cta__subtitle">
			<?php echo esc_html( $cta_subtitle ); ?>
		</p>

		<div class="s8-cta__actions">
			<a href="<?php echo esc_url( $btn_link ); ?>" class="btn btn--primary s8-cta__btn">
				<span class="btn__text"><?php echo esc_html( $btn_text ); ?></span>
				<i data-lucide="calculator" class="w-4 h-4 ml-2"></i>
			</a>
			<a href="<?php echo esc_url( $ghost_link ); ?>" class="btn btn--ghost s8-cta__btn-ghost">
				<span class="btn__text"><?php echo esc_html( $ghost_text ); ?></span>
				<i data-lucide="message-circle" class="w-5 h-5 ml-2"></i>
			</a>
		</div>
	</div>
</section>
