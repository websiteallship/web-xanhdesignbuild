<?php
/**
 * Template Part: Section PD CTA (D10).
 *
 * Call-to-action section with primary + ghost buttons.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cta_headline = function_exists( 'get_field' ) ? get_field( 'pd_cta_headline' ) : '';
$cta_btn1_text = function_exists( 'get_field' ) ? get_field( 'pd_cta_btn1_text' ) : '';
$cta_btn1_url  = function_exists( 'get_field' ) ? get_field( 'pd_cta_btn1_url' ) : '';
$cta_btn2_text = function_exists( 'get_field' ) ? get_field( 'pd_cta_btn2_text' ) : '';
$cta_btn2_url  = function_exists( 'get_field' ) ? get_field( 'pd_cta_btn2_url' ) : '';

// Defaults
if ( ! $cta_headline ) {
	$cta_headline = 'Bạn cũng muốn một không gian sống<br class="d10-cta__br" /><em>trọn vẹn và minh bạch</em> như thế này?';
}
if ( ! $cta_btn1_text ) {
	$cta_btn1_text = 'Sử Dụng Công Cụ Dự Toán Xanh';
}
if ( ! $cta_btn1_url ) {
	$cta_btn1_url = home_url( '/lien-he/' );
}
if ( ! $cta_btn2_text ) {
	$cta_btn2_text = 'Trao Đổi Riêng Với Chuyên Gia';
}
if ( ! $cta_btn2_url ) {
	$cta_btn2_url = 'https://zalo.me/your-oa-id';
}
?>

<!-- ═════════════════════════════════════════════════
     D10: CTA — Kêu Gọi Hành Động
     ═════════════════════════════════════════════════ -->
<section id="d10-cta" class="d10-cta d10-cta--with-bg">
  <div class="d10-cta__overlay" aria-hidden="true"></div>
  <div class="site-container">
    <div class="d10-cta__inner anim-fade-up">

      <h2 class="d10-cta__headline">
        <?php echo wp_kses_post( $cta_headline ); ?>
      </h2>

      <div class="d10-cta__actions">
        <!-- CTA 1: Dự Toán Xanh — accent button -->
        <a href="<?php echo esc_url( $cta_btn1_url ); ?>" class="btn btn--primary group" id="cta-du-toan">
          <span><?php echo esc_html( $cta_btn1_text ); ?></span>
          <i data-lucide="arrow-right" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5"></i>
        </a>

        <!-- CTA 2: Chat Zalo — ghost button -->
        <a href="<?php echo esc_url( $cta_btn2_url ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn--ghost group" id="cta-zalo">
          <span><?php echo esc_html( $cta_btn2_text ); ?></span>
          <i data-lucide="message-circle" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5"></i>
        </a>
      </div>

    </div>
  </div>
</section><!-- /#d10-cta -->
