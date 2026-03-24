<?php
/**
 * Template Part: Section PD Gallery (D7).
 *
 * Masonry gallery grid with custom lightbox.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gallery_eyebrow  = function_exists( 'get_field' ) ? get_field( 'pd_gallery_eyebrow' ) : '';
$gallery_title    = function_exists( 'get_field' ) ? get_field( 'pd_gallery_title' ) : '';
$gallery_subtitle = function_exists( 'get_field' ) ? get_field( 'pd_gallery_subtitle' ) : '';
$gallery_images   = function_exists( 'get_field' ) ? get_field( 'pd_gallery_images' ) : [];

if ( empty( $gallery_images ) ) {
	return;
}
?>

<!-- ═════════════════════════════════════════════════
     D7: REAL GALLERY + VIDEO
     ═════════════════════════════════════════════════ -->
<section id="d7-gallery" class="gallery-section">
  <div class="site-container">

    <!-- Section Header -->
    <div class="section-header section-header--center anim-fade-up">
      <?php if ( $gallery_eyebrow ) : ?>
        <span class="section-eyebrow text-primary/50"><?php echo esc_html( $gallery_eyebrow ); ?></span>
      <?php endif; ?>
      <?php if ( $gallery_title ) : ?>
        <h2 class="section-title text-primary"><?php echo esc_html( $gallery_title ); ?></h2>
      <?php endif; ?>
      <?php if ( $gallery_subtitle ) : ?>
        <p class="section-subtitle text-dark/80"><?php echo esc_html( $gallery_subtitle ); ?></p>
      <?php endif; ?>
    </div>

    <!-- Gallery Grid -->
    <div class="gallery-grid" id="gallery-grid">

      <?php foreach ( $gallery_images as $item ) :
        $image   = ! empty( $item['image'] ) ? $item['image'] : null;
        $caption = ! empty( $item['caption'] ) ? $item['caption'] : '';
        $layout  = ! empty( $item['layout'] ) ? $item['layout'] : 'normal';

        if ( ! $image ) {
          continue;
        }

        $full_url  = $image['url'];
        $thumb_url = ! empty( $image['sizes']['medium_large'] ) ? $image['sizes']['medium_large'] : $full_url;
        $alt       = ! empty( $image['alt'] ) ? $image['alt'] : $caption;

        // CSS class modifier
        $item_class = 'gallery-grid__item anim-fade-up';
        if ( $layout === 'wide' ) {
          $item_class .= ' gallery-grid__item--wide';
        } elseif ( $layout === 'tall' ) {
          $item_class .= ' gallery-grid__item--tall';
        }
      ?>
        <a href="<?php echo esc_url( $full_url ); ?>"
           class="<?php echo esc_attr( $item_class ); ?>"
           data-gallery-lb
           data-gallery-title="<?php echo esc_attr( $caption ); ?>"
           aria-label="<?php echo esc_attr( sprintf( __( 'Xem ảnh %s', 'xanh' ), $caption ) ); ?>">
          <?php if ( ! empty( $image['ID'] ) ) :
            echo wp_get_attachment_image( $image['ID'], 'medium_large', false, [
              'alt'     => esc_attr( $alt ),
              'loading' => 'lazy',
            ] );
          else : ?>
            <img src="<?php echo esc_url( $thumb_url ); ?>"
                 alt="<?php echo esc_attr( $alt ); ?>"
                 width="600" height="400" loading="lazy" />
          <?php endif; ?>
          <?php if ( $caption ) : ?>
            <span class="gallery-grid__caption"><?php echo esc_html( $caption ); ?></span>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>

    </div><!-- /.gallery-grid -->

    <!-- Gallery Load More -->
    <div class="gallery-load-more-wrap anim-fade-up" id="gallery-load-more-wrap">
      <button id="gallery-load-more" class="btn btn--outline group" type="button">
        <span><?php esc_html_e( 'Xem Thêm Ảnh Dự Án', 'xanh' ); ?></span>
        <i data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300 group-hover:translate-y-1"></i>
      </button>
    </div>

    <!-- ── Gallery Custom Lightbox (matching BA lightbox layout) ── -->
    <div class="gallery-lb" id="gallery-lb" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Phóng to ảnh dự án', 'xanh' ); ?>">
      <div class="gallery-lb__backdrop"></div>
      <div class="gallery-lb__content">
        <div class="gallery-lb__header">
          <span class="gallery-lb__title" id="gallery-lb-title"></span>
          <span class="gallery-lb__counter" id="gallery-lb-counter"></span>
          <button class="gallery-lb__close" id="gallery-lb-close" type="button" aria-label="<?php esc_attr_e( 'Đóng', 'xanh' ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        <div class="gallery-lb__body">
          <!-- Prev/Next Arrows -->
          <button class="gallery-lb__arrow gallery-lb__arrow--prev" id="gallery-lb-prev" type="button" aria-label="<?php esc_attr_e( 'Ảnh trước', 'xanh' ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
          </button>
          <div class="gallery-lb__image-wrap" id="gallery-lb-image-wrap">
            <img id="gallery-lb-img" src="" alt="" />
          </div>
          <button class="gallery-lb__arrow gallery-lb__arrow--next" id="gallery-lb-next" type="button" aria-label="<?php esc_attr_e( 'Ảnh tiếp', 'xanh' ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
          </button>
        </div>
        <!-- Lightbox Thumb Nav -->
        <div class="gallery-lb__thumbs" id="gallery-lb-thumbs">
          <!-- Dynamically populated by JS -->
        </div>
      </div>
    </div>

  </div><!-- /.site-container -->
</section><!-- /#d7-gallery -->
