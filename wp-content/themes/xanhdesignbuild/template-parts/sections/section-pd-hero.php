<?php
/**
 * Template Part: Section PD Hero (D1 + D2).
 *
 * Full-width hero image with breadcrumb, title, eyebrow, and tagline.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Hero image: ACF → Featured Image fallback ──
$hero_img = function_exists( 'get_field' ) ? get_field( 'pd_hero_image' ) : null;
$hero_url = '';
$hero_alt = esc_attr( get_the_title() ) . ' — XANH Design & Build';

if ( $hero_img && isset( $hero_img['url'] ) ) {
	$hero_url = $hero_img['url'];
	if ( ! empty( $hero_img['alt'] ) ) {
		$hero_alt = $hero_img['alt'];
	}
} elseif ( has_post_thumbnail() ) {
	$hero_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
} else {
	$hero_url = XANH_THEME_URI . '/assets/images/placeholder-project.png';
}

// ── ACF fields ──
$eyebrow = function_exists( 'get_field' ) ? get_field( 'pd_eyebrow' ) : '';
$tagline = function_exists( 'get_field' ) ? get_field( 'pd_tagline' ) : '';

// ── Breadcrumb data ──
$project_types = get_the_terms( get_the_ID(), 'project_type' );
$type_name     = '';
$type_link     = '';
if ( $project_types && ! is_wp_error( $project_types ) ) {
	$first_type = $project_types[0];
	$type_name  = $first_type->name;
	$type_link  = get_term_link( $first_type );
}
?>

<!-- ═════════════════════════════════════════════════
     D1 + D2: HERO IMAGE + BREADCRUMB
     ═════════════════════════════════════════════════ -->
<section id="detail-hero" class="detail-hero">

  <!-- Background image -->
  <div class="detail-hero__bg">
    <?php if ( $hero_url ) : ?>
      <img
        src="<?php echo esc_url( $hero_url ); ?>"
        alt="<?php echo esc_attr( $hero_alt ); ?>"
        width="1920"
        height="1080"
      />
    <?php endif; ?>
    <div class="detail-hero__overlay"></div>
  </div>

  <!-- Nội dung overlay: tất cả nằm ở bottom -->
  <div class="detail-hero__inner">

    <!-- D1 + D2: Project info cluster — bottom of hero -->
    <div class="detail-hero__bottom">
      <div class="site-container--hero">

        <!-- D1: Breadcrumb — ngay trên title -->
        <nav class="breadcrumb breadcrumb--hero" aria-label="Breadcrumb">
          <ol class="breadcrumb__list">
            <li class="breadcrumb__item">
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumb__link"><?php esc_html_e( 'Trang Chủ', 'xanh' ); ?></a>
              <span class="breadcrumb__separator" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
              </span>
            </li>
            <li class="breadcrumb__item">
              <a href="<?php echo esc_url( get_post_type_archive_link( 'xanh_project' ) ); ?>" class="breadcrumb__link"><?php esc_html_e( 'Dự Án', 'xanh' ); ?></a>
              <span class="breadcrumb__separator" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
              </span>
            </li>
            <li class="breadcrumb__item">
              <span class="breadcrumb__current" aria-current="page"><?php the_title(); ?></span>
            </li>
          </ol>
        </nav>

        <!-- D2: Tên dự án — title lớn -->
        <h1 class="detail-hero__title"><?php the_title(); ?></h1>

        <!-- Eyebrow: loại hình dự án — dưới title -->
        <?php if ( $eyebrow ) : ?>
          <span class="detail-hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
        <?php endif; ?>

        <!-- Tagline: số liệu minh bạch -->
        <?php if ( $tagline ) : ?>
          <p class="detail-hero__tagline"><?php echo esc_html( $tagline ); ?></p>
        <?php endif; ?>

      </div>
    </div>

  </div>

</section>
