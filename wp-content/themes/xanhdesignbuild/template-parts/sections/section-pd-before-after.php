<?php
/**
 * Template Part: Section PD Before/After (D5).
 *
 * Swiper + custom comparison slider with lightbox.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ba_eyebrow = function_exists( 'get_field' ) ? get_field( 'pd_ba_eyebrow' ) : '';
$ba_title   = function_exists( 'get_field' ) ? get_field( 'pd_ba_title' ) : '';
$ba_slides  = function_exists( 'get_field' ) ? get_field( 'pd_ba_slides' ) : [];

if ( empty( $ba_slides ) ) {
	return;
}
?>

<!-- ═════════════════════════════════════════════════
     D5: BEFORE / AFTER — Image Comparison
     ═════════════════════════════════════════════════ -->
<section id="before-after" class="before-after" aria-label="So sánh Concept 3D và Thực tế">
  <div class="site-container">

    <!-- Section Header -->
    <div class="section-header section-header--center anim-fade-up">
      <?php if ( $ba_eyebrow ) : ?>
        <span class="section-eyebrow"><?php echo esc_html( $ba_eyebrow ); ?></span>
      <?php endif; ?>
      <?php if ( $ba_title ) : ?>
        <h2 class="section-title text-primary"><?php echo wp_kses_post( $ba_title ); ?></h2>
      <?php endif; ?>
      <p class="ba-help-text anim-fade-up"><?php esc_html_e( 'Kéo để so sánh', 'xanh' ); ?></p>
    </div>

    <!-- ── Main Comparison Slider (Swiper) ── -->
    <div class="ba-main-wrap anim-fade-up">
      <div class="swiper ba-main-swiper" id="ba-main-swiper">
        <div class="swiper-wrapper">

          <?php foreach ( $ba_slides as $slide ) :
            $before_img = ! empty( $slide['before_img'] ) ? $slide['before_img'] : null;
            $after_img  = ! empty( $slide['after_img'] ) ? $slide['after_img'] : null;
            $room_name  = ! empty( $slide['room_name'] ) ? $slide['room_name'] : '';

            if ( ! $before_img || ! $after_img ) {
              continue;
            }

            $before_url   = $before_img['url'];
            $before_alt   = ! empty( $before_img['alt'] ) ? $before_img['alt'] : 'Concept 3D — ' . $room_name;
            $after_url    = $after_img['url'];
            $after_alt    = ! empty( $after_img['alt'] ) ? $after_img['alt'] : 'Thực tế — ' . $room_name;
            $before_full  = ! empty( $before_img['sizes']['large'] ) ? $before_img['sizes']['large'] : $before_url;
            $after_full   = ! empty( $after_img['sizes']['large'] ) ? $after_img['sizes']['large'] : $after_url;
          ?>
            <div class="swiper-slide">
              <div class="ba-slide">
                <div class="ba-custom-slider">
                  <img src="<?php echo esc_url( $after_url ); ?>" alt="<?php echo esc_attr( $after_alt ); ?>" class="ba-custom-slider__after" draggable="false" width="1200" height="700" loading="lazy" />
                  <div class="ba-custom-slider__before">
                    <img src="<?php echo esc_url( $before_url ); ?>" alt="<?php echo esc_attr( $before_alt ); ?>" draggable="false" width="1200" height="700" loading="lazy" />
                  </div>
                  <div class="ba-custom-slider__handle">
                    <div class="ba-custom-slider__handle-line"></div>
                    <div class="ba-custom-slider__handle-knob">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18-6-6 6-6" /><path d="m15 6 6 6-6 6" />
                      </svg>
                    </div>
                    <div class="ba-custom-slider__handle-line"></div>
                  </div>
                  <span class="ba-label ba-label--before"><?php esc_html_e( 'Concept 3D', 'xanh' ); ?></span>
                  <span class="ba-label ba-label--after"><?php esc_html_e( 'Thực tế nghiệm thu', 'xanh' ); ?></span>
                </div>
                <?php if ( $room_name ) : ?>
                  <span class="ba-slide-title"><?php echo esc_html( $room_name ); ?></span>
                <?php endif; ?>
                <button class="ba-zoom-btn" type="button" aria-label="<?php esc_attr_e( 'Phóng to', 'xanh' ); ?>" data-first="<?php echo esc_url( $before_url ); ?>" data-second="<?php echo esc_url( $after_url ); ?>" data-title="<?php echo esc_attr( $room_name ); ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
                </button>
              </div>
            </div>
          <?php endforeach; ?>

        </div>
      </div>
    </div>

    <!-- ── Thumbnail Navigation (Swiper Thumbs) ── -->
    <div class="ba-thumbs-wrap anim-fade-up" data-delay="200">
      <div class="swiper ba-thumbs-swiper" id="ba-thumbs-swiper">
        <div class="swiper-wrapper">
          <?php foreach ( $ba_slides as $slide ) :
            $thumb_img  = ! empty( $slide['thumb_img'] ) ? $slide['thumb_img'] : ( ! empty( $slide['after_img'] ) ? $slide['after_img'] : null );
            $room_name  = ! empty( $slide['room_name'] ) ? $slide['room_name'] : '';

            if ( ! $thumb_img ) {
              continue;
            }

            $thumb_url = ! empty( $thumb_img['sizes']['thumbnail'] ) ? $thumb_img['sizes']['thumbnail'] : $thumb_img['url'];
            $thumb_alt = ! empty( $thumb_img['alt'] ) ? $thumb_img['alt'] : $room_name;
          ?>
            <div class="swiper-slide">
              <button class="ba-thumb" type="button" aria-label="<?php echo esc_attr( $room_name ); ?>">
                <div class="ba-thumb__img">
                  <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $thumb_alt ); ?>" width="300" height="200" loading="lazy" />
                </div>
                <span class="ba-thumb__name"><?php echo esc_html( $room_name ); ?></span>
                <div class="ba-thumb__bar"></div>
              </button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <!-- Nav Arrows -->
      <button class="ba-nav-btn ba-nav-prev" aria-label="<?php esc_attr_e( 'Không gian trước', 'xanh' ); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      </button>
      <button class="ba-nav-btn ba-nav-next" aria-label="<?php esc_attr_e( 'Không gian tiếp', 'xanh' ); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
      </button>
    </div>

    <!-- Mobile bottom nav: prev/next + pagination dots -->
    <div class="ba-thumbs-nav">
      <button class="ba-nav-btn ba-thumbs-nav__prev ba-thumbs-mobile-prev" type="button" aria-label="<?php esc_attr_e( 'Không gian trước', 'xanh' ); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      </button>
      <div class="ba-thumbs-pagination"></div>
      <button class="ba-nav-btn ba-thumbs-nav__next ba-thumbs-mobile-next" type="button" aria-label="<?php esc_attr_e( 'Không gian tiếp', 'xanh' ); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
      </button>
    </div>

  </div>

  <!-- ── Lightbox Modal ── -->
  <div class="ba-lightbox" id="ba-lightbox" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Phóng to so sánh', 'xanh' ); ?>">
    <div class="ba-lightbox__backdrop"></div>
    <div class="ba-lightbox__content">
      <div class="ba-lightbox__header">
        <span class="ba-lightbox__title" id="ba-lightbox-title"></span>
        <span class="ba-lightbox__counter" id="ba-lightbox-counter"></span>
        <button class="ba-lightbox__close" id="ba-lightbox-close" type="button" aria-label="<?php esc_attr_e( 'Đóng', 'xanh' ); ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="ba-lightbox__body">
        <!-- Prev/Next Arrows -->
        <button class="ba-lightbox__arrow ba-lightbox__arrow--prev" id="ba-lb-prev" type="button" aria-label="<?php esc_attr_e( 'Ảnh trước', 'xanh' ); ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </button>
        <div class="ba-lightbox__slider" id="ba-lightbox-slider">
          <!-- Dynamically populated by JS -->
        </div>
        <button class="ba-lightbox__arrow ba-lightbox__arrow--next" id="ba-lb-next" type="button" aria-label="<?php esc_attr_e( 'Ảnh tiếp', 'xanh' ); ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </button>
        <!-- Labels -->
        <div class="ba-lightbox__labels">
          <span class="ba-label ba-label--before"><?php esc_html_e( 'Concept 3D', 'xanh' ); ?></span>
          <span class="ba-label ba-label--after"><?php esc_html_e( 'Thực tế nghiệm thu', 'xanh' ); ?></span>
        </div>
      </div>
      <!-- Lightbox Thumb Nav -->
      <div class="ba-lightbox__thumbs" id="ba-lightbox-thumbs">
        <!-- Dynamically populated by JS -->
      </div>
    </div>
  </div>
</section>
