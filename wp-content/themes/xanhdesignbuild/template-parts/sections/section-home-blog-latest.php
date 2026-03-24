<?php
/**
 * Template Part: Section — Blog Latest (Cẩm Nang & Cảm Hứng).
 *
 * Displays latest blog posts in a Swiper slider with side arrows.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uploads = content_url( '/uploads/2026/03/' );

// Query latest posts (cached via transient — 1hr TTL).
$transient_key = 'xanh_home_blog_latest';
$blog_query = get_transient( $transient_key );

if ( false === $blog_query ) {
	$blog_query = new WP_Query( [
		'posts_per_page' => 6,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	] );
	set_transient( $transient_key, $blog_query, HOUR_IN_SECONDS );
}

// Fallback static posts if no posts exist.
$fallback_posts = [
	[
		'title' => 'Xu Hướng Thiết Kế Nhà Phố Xanh 2025 — Bền Vững & Tinh Tế',
		'img'   => $uploads . 'project-1.png',
		'date'  => '15 Th12, 2025',
		'url'   => '#',
	],
	[
		'title' => 'Nghệ Thuật Chọn Vật Liệu — Chìa Khóa Cho Không Gian Sang Trọng',
		'img'   => $uploads . 'project-2.png',
		'date'  => '28 Th11, 2025',
		'url'   => '#',
	],
	[
		'title' => 'Hành Trình Kiến Tạo Biệt Thự Hiện Đại — Từ Bản Vẽ Đến Tổ Ấm',
		'img'   => $uploads . 'project-3.png',
		'date'  => '10 Th11, 2025',
		'url'   => '#',
	],
	[
		'title' => 'Cải Tạo Nhà Phố — Biến Không Gian Cũ Thành Tổ Ấm Mơ Ước',
		'img'   => $uploads . 'project-4.png',
		'date'  => '01 Th10, 2025',
		'url'   => '#',
	],
];

$use_fallback = ! $blog_query->have_posts();
?>

<section id="blog" class="blog-section" aria-label="Blog & Cảm Hứng">
	<div class="site-container">

		<!-- Header -->
		<p class="anim-fade-up section-eyebrow blog-eyebrow">Cẩm Nang & Cảm Hứng</p>
		<h2 class="anim-fade-up section-title text-primary blog-heading">
			Không Gian Sống Xanh<br>Dưới Góc Nhìn Chuyên Gia
		</h2>

		<!-- Slider + Side Arrows Wrapper -->
		<div class="blog-slider-wrap anim-fade-up">
			<div class="swiper blog-swiper" id="blog-swiper">
				<div class="swiper-wrapper">

					<?php if ( $use_fallback ) : ?>
						<?php foreach ( $fallback_posts as $post_data ) : ?>
							<div class="swiper-slide">
								<article class="blog-card">
									<a href="<?php echo esc_url( $post_data['url'] ); ?>" class="blog-card__img-link">
										<img src="<?php echo esc_url( $post_data['img'] ); ?>"
											alt="<?php echo esc_attr( $post_data['title'] ); ?>"
											class="blog-card__img" loading="lazy" width="480" height="360" />
									</a>
									<div class="blog-card__body">
										<time class="blog-card__date"><?php echo esc_html( $post_data['date'] ); ?></time>
										<h3 class="blog-card__title">
											<a href="<?php echo esc_url( $post_data['url'] ); ?>"><?php echo esc_html( $post_data['title'] ); ?></a>
										</h3>
										<a href="<?php echo esc_url( $post_data['url'] ); ?>" class="blog-card__link">
											Khám Phá
											<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
												<path d="M5 12h14" />
												<path d="m12 5 7 7-7 7" />
											</svg>
										</a>
									</div>
								</article>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
							<div class="swiper-slide">
								<article class="blog-card">
									<a href="<?php the_permalink(); ?>" class="blog-card__img-link">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'medium_large', [
												'class'   => 'blog-card__img',
												'loading' => 'lazy',
											] ); ?>
										<?php else : ?>
											<img src="<?php echo esc_url( XANH_THEME_URI . '/assets/images/placeholder-project.png' ); ?>"
												alt="<?php the_title_attribute(); ?>"
												class="blog-card__img" loading="lazy" />
										<?php endif; ?>
									</a>
									<div class="blog-card__body">
										<time class="blog-card__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
											<?php echo esc_html( get_the_date( 'd M, Y' ) ); ?>
										</time>
										<h3 class="blog-card__title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>
										<a href="<?php the_permalink(); ?>" class="blog-card__link">
											Khám Phá
											<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
												<path d="M5 12h14" />
												<path d="m12 5 7 7-7 7" />
											</svg>
										</a>
									</div>
								</article>
							</div>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					<?php endif; ?>

				</div>
			</div>

			<!-- Side Arrow Buttons (hidden by default, shown on hover) -->
			<button class="blog-nav__btn blog-nav__prev blog-swiper-prev" aria-label="Bài trước">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="m15 18-6-6 6-6" />
				</svg>
			</button>
			<button class="blog-nav__btn blog-nav__next blog-swiper-next" aria-label="Bài tiếp">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="m9 18 6-6-6-6" />
				</svg>
			</button>
		</div>

		<!-- Bottom Nav: pagination + mobile buttons -->
		<div class="blog-nav">
			<button class="blog-nav__btn blog-nav__prev blog-nav__mobile-btn blog-mobile-prev" aria-label="Bài trước">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="m15 18-6-6 6-6" />
				</svg>
			</button>
			<div class="blog-pagination"></div>
			<button class="blog-nav__btn blog-nav__next blog-nav__mobile-btn blog-mobile-next" aria-label="Bài tiếp">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="m9 18 6-6-6-6" />
				</svg>
			</button>
		</div>

	</div>
</section>
