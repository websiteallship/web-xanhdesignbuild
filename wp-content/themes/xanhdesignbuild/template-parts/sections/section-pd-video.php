<?php
/**
 * Template Part: Section PD Video (D5b).
 *
 * Video hero banner with GLightbox play button.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$video_title   = function_exists( 'get_field' ) ? get_field( 'pd_video_title' ) : '';
$video_url     = function_exists( 'get_field' ) ? get_field( 'pd_video_url' ) : '';
$video_subtext = function_exists( 'get_field' ) ? get_field( 'pd_video_subtext' ) : '';
$video_bg      = function_exists( 'get_field' ) ? get_field( 'pd_video_bg' ) : null;

if ( ! $video_url ) {
	return;
}

$bg_url = '';
$bg_alt = esc_attr( get_the_title() ) . ' — XANH Design & Build';
if ( $video_bg && isset( $video_bg['url'] ) ) {
	$bg_url = $video_bg['url'];
	if ( ! empty( $video_bg['alt'] ) ) {
		$bg_alt = $video_bg['alt'];
	}
}
?>

<!-- ═════════════════════════════════════════════════
     D5b: VIDEO HERO BANNER
     ═════════════════════════════════════════════════ -->
<section id="d5b-video" class="video-hero" aria-label="<?php esc_attr_e( 'Video Thực Tế Dự Án', 'xanh' ); ?>">

  <!-- Background Image -->
  <?php if ( $bg_url ) : ?>
    <div class="video-hero__bg">
      <img src="<?php echo esc_url( $bg_url ); ?>"
           alt="<?php echo esc_attr( $bg_alt ); ?>"
           class="video-hero__bg-img" width="1920" height="1080" loading="lazy" />
    </div>
  <?php endif; ?>

  <!-- Overlay Gradient -->
  <div class="video-hero__overlay"></div>

  <!-- Content -->
  <div class="video-hero__content site-container anim-fade-up">
    <?php if ( $video_title ) : ?>
      <h2 class="video-hero__title"><?php echo esc_html( $video_title ); ?></h2>
    <?php endif; ?>

    <!-- Video Play Button -->
    <a href="<?php echo esc_url( $video_url ); ?>"
       class="video-hero__play-btn glightbox-video group"
       data-glightbox="type: video"
       aria-label="<?php esc_attr_e( 'Xem video thực tế dự án', 'xanh' ); ?>">
      <span class="video-hero__play-circle">
        <svg class="video-hero__play-icon" viewBox="0 0 24 24" fill="currentColor">
          <path d="M8 5v14l11-7z" />
        </svg>
      </span>
    </a>

    <?php if ( $video_subtext ) : ?>
      <p class="video-hero__subtext"><?php echo esc_html( $video_subtext ); ?></p>
    <?php endif; ?>
  </div>
</section>
