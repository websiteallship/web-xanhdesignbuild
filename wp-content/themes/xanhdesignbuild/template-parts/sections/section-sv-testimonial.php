<?php
/**
 * Template Part: Section SV Testimonial (S6).
 *
 * Carousel testimonial with per-slide image, quote, author.
 * JS carousel logic in service-detail.js (XanhServiceDetail.initTestimonialCarousel).
 *
 * ACF fields: sv_testi_eyebrow, sv_testi_title, sv_testi_subtitle,
 *             sv_testi_bg_image, sv_testimonials (repeater: image, quote, name, role, avatar).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow  = get_field( 'sv_testi_eyebrow' ) ?: 'Dấu Ấn Sự Hài Lòng';
$title    = get_field( 'sv_testi_title' ) ?: 'Tiếng Nói Khách Hàng';
$subtitle = get_field( 'sv_testi_subtitle' ) ?: 'Niềm tự hào lớn nhất của dịch vụ <strong>thiết kế kiến trúc và nội thất</strong> XANH chính là sự hài lòng và những lời chia sẻ chân thành từ chính các gia chủ.';
$bg_image = xanh_get_image( 'sv_testi_bg_image' );

$testimonials = get_field( 'sv_testimonials' );
if ( ! $testimonials ) {
	$testimonials = [
		[
			'quote'  => '"Từ bản phối cảnh 3D đến khi hoàn thiện — chúng tôi gần như không thấy sự khác biệt. XANH đã biến giấc mơ của gia đình chúng tôi thành hiện thực, từng chi tiết đều tinh tế và chính xác."',
			'name'   => 'Chị Thuỳ Linh',
			'role'   => 'Khách Hàng Xác Minh ⎮ Villa Thảo Điền',
		],
		[
			'quote'  => '"Minh bạch từng đồng, rõ ràng từng hạng mục. Không một khoản phát sinh nào ngoài dự toán. Đó là điều khiến chúng tôi tin tưởng XANH tuyệt đối."',
			'name'   => 'Anh Minh Đức',
			'role'   => 'Khách Hàng Xác Minh ⎮ The Oakwood Tower',
		],
		[
			'quote'  => '"Đội ngũ kiến trúc sư XANH không chỉ thiết kế — họ lắng nghe, thấu hiểu và kiến tạo. Ngôi nhà này mang đúng tinh thần và cá tính của gia đình chúng tôi."',
			'name'   => 'Chị Hồng Nhung',
			'role'   => 'Khách Hàng Xác Minh ⎮ Garden Residence',
		],
	];
}

$total_slides = count( $testimonials );

// Background image fallback
$bg_url = '';
if ( $bg_image ) {
	$bg_url = $bg_image['url'];
} else {
	$bg_url = site_url( '/wp-content/uploads/2026/03/service-architecture.png' );
}
?>

<section id="service-testimonial" class="s6-testimonial">

	<!-- Background image + overlay -->
	<div class="s6-testimonial__bg">
		<?php if ( $bg_image ) : ?>
			<?php
			echo wp_get_attachment_image( $bg_image['ID'], 'large', false, [
				'class'       => 's6-testimonial__bg-img',
				'alt'         => '',
				'aria-hidden' => 'true',
				'loading'     => 'lazy',
			] );
			?>
		<?php else : ?>
			<img src="<?php echo esc_url( $bg_url ); ?>" alt="" class="s6-testimonial__bg-img" loading="lazy" aria-hidden="true" />
		<?php endif; ?>
		<div class="s6-testimonial__overlay"></div>
	</div>

	<div class="site-container relative z-10">

		<!-- Section Header -->
		<div class="s6-testimonial__header anim-fade-up">
			<span class="section-eyebrow s6-testimonial__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<h2 class="section-title section-title--light s6-testimonial__title"><?php echo esc_html( $title ); ?></h2>
			<p class="s6-testimonial__subtitle">
				<?php echo wp_kses_post( $subtitle ); ?>
			</p>
		</div>

		<?php if ( $testimonials ) : ?>
			<div class="s6-testimonial__wrapper anim-fade-up" style="transition-delay: 150ms;">

				<!-- Carousel track (clips overflow for horizontal slide) -->
				<div class="s6-testimonial__track" id="testimonial-carousel">
					<?php foreach ( $testimonials as $si => $slide ) :
						$is_active  = ( 0 === $si );
						$slide_img  = isset( $slide['image'] ) ? $slide['image'] : null;
						$avatar     = isset( $slide['avatar'] ) ? $slide['avatar'] : null;
					?>
						<div class="s6-testimonial__slide<?php echo $is_active ? ' is-active' : ''; ?>" data-slide="<?php echo esc_attr( $si ); ?>">
							<!-- Per-slide project image with gradient -->
							<?php if ( $slide_img && isset( $slide_img['ID'] ) ) : ?>
								<div class="s6-testimonial__img-wrap">
									<?php echo wp_get_attachment_image( $slide_img['ID'], 'medium_large', false, [ 'loading' => 'lazy' ] ); ?>
									<div class="s6-testimonial__img-gradient"></div>
								</div>
							<?php endif; ?>
							<!-- Quote block -->
							<div class="s6-testimonial__body">
								<svg class="s6-testimonial__quote-open" viewBox="0 0 48 48" fill="currentColor" aria-hidden="true">
									<path d="M21 28c0 5.5-4.5 10-10 10S1 33.5 1 28c0-10 8-18 20-20l1 4C14 14 11 18 11 22v1c1-1 3-1 4-1 3.3 0 6 2.7 6 6zm24 0c0 5.5-4.5 10-10 10s-10-4.5-10-10c0-10 8-18 20-20l1 4c-8 2-11 6-11 10v1c1-1 3-1 4-1 3.3 0 6 2.7 6 6z" />
								</svg>
								<blockquote class="s6-testimonial__quote">
									<?php echo esc_html( $slide['quote'] ?? '' ); ?>
								</blockquote>
								<svg class="s6-testimonial__quote-close" viewBox="0 0 48 48" fill="currentColor" aria-hidden="true">
									<path d="M27 20c0-5.5 4.5-10 10-10s10 4.5 10 10c0 10-8 18-20 20l-1-4c8-2 11-6 11-10v-1c-1 1-3 1-4 1-3.3 0-6-2.7-6-6zM3 20c0-5.5 4.5-10 10-10s10 4.5 10 10c0 10-8 18-20 20l-1-4c8-2 11-6 11-10v-1c-1 1-3 1-4 1-3.3 0-6-2.7-6-6z" />
								</svg>
								<div class="s6-testimonial__author">
									<?php if ( $avatar && isset( $avatar['url'] ) ) : ?>
										<img src="<?php echo esc_url( $avatar['url'] ); ?>"
											alt="<?php echo esc_attr( $slide['name'] ?? '' ); ?>"
											class="s6-testimonial__avatar" width="72" height="72" loading="lazy" />
									<?php endif; ?>
									<div class="s6-testimonial__author-info">
										<?php if ( ! empty( $slide['name'] ) ) : ?>
											<span class="s6-testimonial__name"><?php echo esc_html( $slide['name'] ); ?></span>
										<?php endif; ?>
										<?php if ( ! empty( $slide['role'] ) ) : ?>
											<span class="s6-testimonial__role">
												<svg class="s6-testimonial__star" viewBox="0 0 20 20" fill="currentColor">
													<path d="M10 2L12.09 7.26L17.51 7.64L13.55 10.97L14.9 16.18L10 13.27L5.1 16.18L6.45 10.97L2.49 7.64L7.91 7.26L10 2Z" />
												</svg>
												<?php echo esc_html( $slide['role'] ); ?>
											</span>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div><!-- /.s6-testimonial__track -->

				<!-- Navigation Dots -->
				<?php if ( $total_slides > 1 ) : ?>
					<div class="s6-testimonial__dots" role="tablist" aria-label="Testimonial navigation">
						<?php for ( $d = 0; $d < $total_slides; $d++ ) : ?>
							<button class="s6-testimonial__dot<?php echo 0 === $d ? ' is-active' : ''; ?>"
								data-goto="<?php echo esc_attr( $d ); ?>"
								aria-label="<?php echo esc_attr( 'Slide ' . ( $d + 1 ) ); ?>"
								role="tab"
								aria-selected="<?php echo 0 === $d ? 'true' : 'false'; ?>"></button>
						<?php endfor; ?>
					</div>
				<?php endif; ?>

			</div><!-- /.s6-testimonial__wrapper -->
		<?php endif; ?>

	</div>
</section>
