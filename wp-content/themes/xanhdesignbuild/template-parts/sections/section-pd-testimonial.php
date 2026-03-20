<?php
/**
 * Template Part: Section PD Testimonial (D8).
 *
 * Client testimonial with photo, quote, and star rating.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testi_image  = function_exists( 'get_field' ) ? get_field( 'pd_testi_image' ) : null;
$testi_quote  = function_exists( 'get_field' ) ? get_field( 'pd_testi_quote' ) : '';
$testi_name   = function_exists( 'get_field' ) ? get_field( 'pd_testi_name' ) : '';
$testi_role   = function_exists( 'get_field' ) ? get_field( 'pd_testi_role' ) : '';
$testi_rating = function_exists( 'get_field' ) ? get_field( 'pd_testi_rating' ) : 5;

if ( ! $testi_quote ) {
	return;
}

$img_url = '';
$img_alt = $testi_name ? $testi_name . ' — XANH Design & Build' : 'Khách hàng — XANH Design & Build';
if ( $testi_image && isset( $testi_image['url'] ) ) {
	$img_url = $testi_image['url'];
	if ( ! empty( $testi_image['alt'] ) ) {
		$img_alt = $testi_image['alt'];
	}
}

$rating = intval( $testi_rating );
if ( $rating < 1 ) $rating = 5;
if ( $rating > 5 ) $rating = 5;
?>

<!-- ═════════════════════════════════════════════════
     D8: TESTIMONIAL — Cảm Nhận Chủ Nhà
     ═════════════════════════════════════════════════ -->
<section id="d8-testimonial" class="d8-testimonial">
  <div class="site-container">
    <div class="testimonial-inner anim-fade-up">

      <!-- Left: Family photo -->
      <?php if ( $img_url ) : ?>
        <div class="testimonial-media">
          <div class="testimonial-media__wrap">
            <img
              src="<?php echo esc_url( $img_url ); ?>"
              alt="<?php echo esc_attr( $img_alt ); ?>"
              width="720" height="900"
              loading="lazy"
              class="testimonial-media__img"
            />
          </div>
        </div>
      <?php endif; ?>

      <!-- Right: Quote content -->
      <div class="testimonial-content">
        <div class="testimonial-quote-icon" aria-hidden="true">
          <svg viewBox="0 0 60 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 48V28.8C0 12.48 9.6 3.36 28.8 0l3.6 5.76C21.84 7.68 15.6 12.96 14.4 21.6H24V48H0ZM36 48V28.8C36 12.48 45.6 3.36 64.8 0l3.6 5.76C57.84 7.68 51.6 12.96 50.4 21.6H60V48H36Z" fill="currentColor"/>
          </svg>
        </div>

        <blockquote class="testimonial-quote">
          <p><?php echo esc_html( $testi_quote ); ?></p>
        </blockquote>

        <div class="testimonial-author">
          <?php if ( $testi_name ) : ?>
            <span class="testimonial-author__name"><?php echo esc_html( $testi_name ); ?></span>
          <?php endif; ?>
          <?php if ( $testi_role ) : ?>
            <span class="testimonial-author__role"><?php echo esc_html( $testi_role ); ?></span>
          <?php endif; ?>
        </div>

        <div class="testimonial-badge">
          <?php for ( $i = 0; $i < $rating; $i++ ) : ?>
            <svg class="testimonial-badge__icon" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 2L12.09 7.26L17.51 7.64L13.55 10.97L14.9 16.18L10 13.27L5.1 16.18L6.45 10.97L2.49 7.64L7.91 7.26L10 2Z" fill="currentColor"/>
            </svg>
          <?php endfor; ?>
          <span class="testimonial-badge__label"><?php esc_html_e( 'Được xác minh', 'xanh' ); ?></span>
        </div>
      </div>

    </div>
  </div>
</section><!-- /#d8-testimonial -->
