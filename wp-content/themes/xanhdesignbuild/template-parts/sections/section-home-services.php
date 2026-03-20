<?php
/**
 * Template Part: Section — Home Services (Lĩnh Vực Hoạt Động).
 *
 * Queries the 4 parent/main xanh_service CPT posts and renders
 * a 4-card grid matching the wireframe design.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Query 4 main services (oldest first = original order) ──
$services_query = new WP_Query( [
	'post_type'      => 'xanh_service',
	'posts_per_page' => 4,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'ASC',
	'no_found_rows'  => true,
] );

if ( ! $services_query->have_posts() ) {
	return;
}

// ── Section header data (ACF with fallback) ──
$eyebrow  = 'Lĩnh Vực Hoạt Động';
$title    = 'Giải Pháp Khép Kín.<br>Dấu Ấn Độc Bản.';
$subtitle = 'Từ ý tưởng kiến trúc sơ khai đến khi trao tay chiếc chìa khóa tổ ấm — Xanh đồng hành cùng bạn trong mọi giai đoạn.';

if ( function_exists( 'get_field' ) ) {
	$page_id   = get_option( 'page_on_front' );
	$eyebrow   = get_field( 'services_eyebrow', $page_id ) ?: $eyebrow;
	$title     = get_field( 'services_headline', $page_id ) ?: $title;
	$subtitle  = get_field( 'services_subtitle', $page_id ) ?: $subtitle;
	$more_url  = get_field( 'services_more_url', $page_id ) ?: '/dich-vu/';
} else {
	$more_url  = '/dich-vu/';
}

// ── Link text (unified CTA label) ──
$link_label_default = 'Khám Phá Dịch Vụ';
?>

<section id="services" class="relative section bg-white">
	<div class="site-container">

		<!-- Section Header -->
		<div class="section-header section-header--center">
			<p class="anim-fade-up section-eyebrow">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
			<h2 class="anim-fade-up section-title text-primary">
				<?php echo wp_kses_post( $title ); ?>
			</h2>
			<p class="anim-fade-up section-subtitle">
				<?php echo esc_html( $subtitle ); ?>
			</p>
		</div>

		<!-- 4-Card Grid -->
		<div class="services-grid">
			<?php
			$card_index = 0;
			while ( $services_query->have_posts() ) :
				$services_query->the_post();

				// ACF fields.
				$sv_desc = '';
				if ( function_exists( 'get_field' ) ) {
					$sv_desc = get_field( 'sv_hero_desc' ) ?: '';
				}
				if ( empty( $sv_desc ) ) {
					$sv_desc = has_excerpt() ? get_the_excerpt() : '';
				}

				$link_label = $link_label_default;
				?>

				<a href="<?php the_permalink(); ?>" class="service-card anim-fade-up group block">
					<div class="service-card__img-wrap">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', [
								'class'   => 'service-card__img',
								'loading' => 'lazy',
								'alt'     => esc_attr( get_the_title() ),
							] ); ?>
						<?php else :
							$hero_img = function_exists( 'get_field' ) ? get_field( 'sv_hero_image' ) : null;
							if ( $hero_img && isset( $hero_img['ID'] ) ) :
								echo wp_get_attachment_image( $hero_img['ID'], 'large', false, [
									'class'   => 'service-card__img',
									'loading' => 'lazy',
									'alt'     => esc_attr( get_the_title() ),
								] );
							else : ?>
								<img src="<?php echo esc_url( XANH_THEME_URI . '/assets/images/placeholder-project.png' ); ?>"
									alt="<?php echo esc_attr( get_the_title() ); ?>"
									class="service-card__img" width="400" height="520" loading="lazy" />
							<?php endif;
						endif; ?>
					</div>
					<div class="service-card__body">
						<h3 class="service-card__title"><?php the_title(); ?></h3>
						<?php if ( $sv_desc ) : ?>
							<p class="service-card__desc"><?php echo esc_html( wp_strip_all_tags( $sv_desc ) ); ?></p>
						<?php endif; ?>
						<span class="service-card__link">
							<?php echo esc_html( $link_label ); ?>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
								<path d="M5 12h14" />
								<path d="m12 5 7 7-7 7" />
							</svg>
						</span>
					</div>
				</a>

				<?php
				$card_index++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>

		<!-- Xem Thêm Dịch Vụ -->
		<div class="flex justify-center mt-12 md:mt-16">
			<a href="<?php echo esc_url( $more_url ); ?>" class="btn btn--outline group">
				<span>Xem Thêm Dịch Vụ</span>
				<i data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300 group-hover:translate-y-1"></i>
			</a>
		</div>
	</div>
</section>
