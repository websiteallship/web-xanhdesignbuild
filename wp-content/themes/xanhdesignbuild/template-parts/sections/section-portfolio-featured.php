<?php
/**
 * Template Part: Section — Portfolio Featured (Dự Án Tiêu Biểu).
 *
 * Desktop: Before/After drag slider + info panel + thumbnail Swiper.
 * Mobile:  Full-card Swiper with inline B/A sliders.
 * ACF: portfolio_eyebrow, portfolio_headline, portfolio_subtitle, portfolio_projects (Repeater).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uploads = content_url( '/uploads/2026/03/' );

// ACF data with fallbacks.
$eyebrow  = get_field( 'portfolio_eyebrow' ) ?: 'Dự Án Tiêu Biểu';
$headline = get_field( 'portfolio_headline' ) ?: 'Hành Trình<br>Chuyển Hoá Không Gian.';
$subtitle = get_field( 'portfolio_subtitle' ) ?: 'Mỗi dự án là một câu chuyện — từ những không gian cũ kỹ đến tổ ấm hoàn hảo mà gia chủ luôn mong ước.';

$default_projects = [
	[
		'before_img' => $uploads . 'project-before-1.png',
		'after_img'  => $uploads . 'project-after-1.png',
		'thumb_img'  => $uploads . 'project-1.png',
		'tag'        => 'Cải tạo toàn diện',
		'title'      => 'Nhà Phố Quận 7',
		'area'       => '120 m²',
		'duration'   => '6 tháng',
		'year'       => '2025',
		'quote'      => '"Chúng tôi không nghĩ ngôi nhà 20 năm tuổi có thể trở nên đẹp đến vậy. XANH đã biến giấc mơ thành hiện thực — đúng tiến độ, đúng chi phí."',
		'author'     => '— Anh Minh & Chị Hương, Q7, TP.HCM',
		'link_url'   => '#',
	],
	[
		'before_img' => $uploads . 'project-3.png',
		'after_img'  => $uploads . 'project-2.png',
		'thumb_img'  => $uploads . 'project-2.png',
		'tag'        => 'Xây mới trọn gói',
		'title'      => 'Biệt Thự Thảo Điền',
		'area'       => '280 m²',
		'duration'   => '14 tháng',
		'year'       => '2024',
		'quote'      => '"Từ mảnh đất trống đến ngôi nhà mơ ước — XANH tận tâm từ bản vẽ đầu tiên đến ngày bàn giao chìa khoá."',
		'author'     => '— Gia đình anh Tuấn, Thảo Điền, Q2',
		'link_url'   => '#',
	],
	[
		'before_img' => $uploads . 'project-4.png',
		'after_img'  => $uploads . 'project-3.png',
		'thumb_img'  => $uploads . 'project-3.png',
		'tag'        => 'Thiết kế nội thất',
		'title'      => 'Penthouse Quận 2',
		'area'       => '95 m²',
		'duration'   => '3 tháng',
		'year'       => '2024',
		'quote'      => '"Không gian sống thay đổi hoàn toàn — sang trọng, tinh tế nhưng vẫn ấm cúng cho gia đình nhỏ."',
		'author'     => '— Chị Linh, Thủ Thiêm, Q2',
		'link_url'   => '#',
	],
	[
		'before_img' => $uploads . 'project-1.png',
		'after_img'  => $uploads . 'project-4.png',
		'thumb_img'  => $uploads . 'project-4.png',
		'tag'        => 'Xây mới & nội thất',
		'title'      => 'Villa Bình Dương',
		'area'       => '350 m²',
		'duration'   => '18 tháng',
		'year'       => '2023',
		'quote'      => '"XANH đã giúp chúng tôi xây dựng không chỉ một ngôi nhà, mà cả một phong cách sống mới — hòa mình với thiên nhiên."',
		'author'     => '— Anh Phúc & Chị Ngọc, Bình Dương',
		'link_url'   => '#',
	],
];

// ACF repeater → map to our format, or use defaults.
$acf_items = get_field( 'portfolio_projects' );
if ( is_array( $acf_items ) && ! empty( $acf_items[0]['title'] ?? '' ) ) {
	$projects = [];
	foreach ( $acf_items as $i => $item ) {
		$before = $item['before_image'] ?? null;
		$after  = $item['after_image'] ?? null;
		$thumb  = $item['thumb_image'] ?? $after;
		$fb     = $default_projects[ $i ] ?? $default_projects[0];
		$projects[] = [
			'before_img' => is_array( $before ) ? $before['url'] : ( $fb['before_img'] ?? '' ),
			'after_img'  => is_array( $after ) ? $after['url'] : ( $fb['after_img'] ?? '' ),
			'thumb_img'  => is_array( $thumb ) ? $thumb['url'] : ( $fb['thumb_img'] ?? '' ),
			'tag'        => $item['tag'] ?? $fb['tag'],
			'title'      => $item['title'] ?? $fb['title'],
			'area'       => $item['area'] ?? $fb['area'],
			'duration'   => $item['duration'] ?? $fb['duration'],
			'year'       => $item['year'] ?? $fb['year'],
			'quote'      => $item['quote'] ?? $fb['quote'],
			'author'     => $item['author'] ?? $fb['author'],
			'link_url'   => $item['link_url'] ?? '#',
		];
	}
} else {
	$projects = $default_projects;
}

$first = $projects[0];
?>

<section id="projects" class="relative section bg-white">
	<div class="site-container">

		<!-- Section Header -->
		<div class="section-header section-header--center">
			<p class="anim-fade-up section-eyebrow">
				<?php echo esc_html( $eyebrow ); ?>
			</p>
			<h2 class="anim-fade-up section-title text-primary">
				<?php echo wp_kses_post( $headline ); ?>
			</h2>
			<p class="anim-fade-up section-subtitle">
				<?php echo esc_html( $subtitle ); ?>
			</p>
		</div>

		<!-- ── Featured Slider Area (Desktop) ── -->
		<div class="anim-fade-up projects-featured">

			<!-- LEFT: Before/After Comparison Slider -->
			<div class="ba-slider-wrap">
				<div class="ba-slider" id="ba-slider">
					<!-- After image (background) -->
					<img src="<?php echo esc_url( $first['after_img'] ); ?>"
						alt="Sau cải tạo — <?php echo esc_attr( $first['title'] ); ?>"
						class="ba-slider__after" id="ba-after-img" draggable="false" />
					<!-- Before image (clipped foreground) -->
					<div class="ba-slider__before" id="ba-before-clip">
						<img src="<?php echo esc_url( $first['before_img'] ); ?>"
							alt="Trước cải tạo — <?php echo esc_attr( $first['title'] ); ?>"
							id="ba-before-img" draggable="false" />
					</div>
					<!-- Drag handle -->
					<div class="ba-slider__handle" id="ba-handle">
						<div class="ba-slider__handle-line"></div>
						<div class="ba-slider__handle-knob">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="m9 18-6-6 6-6" />
								<path d="m15 6 6 6-6 6" />
							</svg>
						</div>
						<div class="ba-slider__handle-line"></div>
					</div>
					<!-- Labels -->
					<span class="ba-slider__label ba-slider__label--before">Trước</span>
					<span class="ba-slider__label ba-slider__label--after">Sau</span>
				</div>
			</div>

			<!-- RIGHT: Project Info -->
			<div class="ba-info" id="ba-info">
				<span class="ba-info__tag" id="ba-tag"><?php echo esc_html( $first['tag'] ); ?></span>
				<h3 class="ba-info__title" id="ba-title"><?php echo esc_html( $first['title'] ); ?></h3>
				<div class="ba-info__meta" id="ba-meta">
					<span class="meta-item"><i data-lucide="maximize"></i> <?php echo esc_html( $first['area'] ); ?></span>
					<span class="meta-sep"></span>
					<span class="meta-item"><i data-lucide="clock"></i> <?php echo esc_html( $first['duration'] ); ?></span>
					<span class="meta-sep"></span>
					<span class="meta-item"><i data-lucide="calendar"></i> <?php echo esc_html( $first['year'] ); ?></span>
				</div>
				<blockquote class="ba-info__quote" id="ba-quote">
					<?php echo esc_html( $first['quote'] ); ?>
				</blockquote>
				<p class="ba-info__author" id="ba-author">
					<?php echo esc_html( $first['author'] ); ?>
				</p>
				<a href="<?php echo esc_url( $first['link_url'] ); ?>" class="ba-info__cta group">
					Xem Hành Trình Dự Án
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
						stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1">
						<path d="M5 12h14" />
						<path d="m12 5 7 7-7 7" />
					</svg>
				</a>
			</div>
		</div>

		<!-- ── Mobile: Full Project Slider ── -->
		<div class="projects-mobile-slider">
			<div class="swiper projects-mobile-swiper" id="projects-mobile-swiper">
				<div class="swiper-wrapper">
					<?php foreach ( $projects as $idx => $proj ) : ?>
						<div class="swiper-slide">
							<div class="mobile-project-card">
								<div class="mobile-project-card__img">
									<div class="ba-custom-slider">
										<img src="<?php echo esc_url( $proj['after_img'] ); ?>"
											alt="Sau — <?php echo esc_attr( $proj['title'] ); ?>"
											class="ba-custom-slider__after" draggable="false" />
										<div class="ba-custom-slider__before">
											<img src="<?php echo esc_url( $proj['before_img'] ); ?>"
												alt="Trước — <?php echo esc_attr( $proj['title'] ); ?>" draggable="false" />
										</div>
										<div class="ba-custom-slider__handle">
											<div class="ba-custom-slider__handle-line"></div>
											<div class="ba-custom-slider__handle-knob">
												<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
													<path d="m9 18-6-6 6-6" />
													<path d="m15 6 6 6-6 6" />
												</svg>
											</div>
											<div class="ba-custom-slider__handle-line"></div>
										</div>
										<span class="ba-slider__label ba-slider__label--before">Trước</span>
										<span class="ba-slider__label ba-slider__label--after">Sau</span>
									</div>
								</div>
								<div class="mobile-project-card__body">
									<span class="mobile-project-card__tag tag"><?php echo esc_html( $proj['tag'] ); ?></span>
									<h3 class="mobile-project-card__title"><?php echo esc_html( $proj['title'] ); ?></h3>
									<div class="mobile-project-card__meta">
										<span class="meta-item"><i data-lucide="maximize"></i> <?php echo esc_html( $proj['area'] ); ?></span>
										<span class="meta-sep"></span>
										<span class="meta-item"><i data-lucide="clock"></i> <?php echo esc_html( $proj['duration'] ); ?></span>
										<span class="meta-sep"></span>
										<span class="meta-item"><i data-lucide="calendar"></i> <?php echo esc_html( $proj['year'] ); ?></span>
									</div>
									<blockquote class="mobile-project-card__quote">
										<?php echo esc_html( $proj['quote'] ); ?>
									</blockquote>
									<p class="mobile-project-card__author"><?php echo esc_html( $proj['author'] ); ?></p>
									<a href="<?php echo esc_url( $proj['link_url'] ); ?>" class="mobile-project-card__cta">
										Xem Hành Trình Dự Án
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
											<path d="M5 12h14" />
											<path d="m12 5 7 7-7 7" />
										</svg>
									</a>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="projects-mobile-pagination"></div>
		</div>

		<!-- ── Thumbnail Selector Row (Swiper) ── -->
		<div class="projects-thumbs-wrapper">
			<div class="swiper projects-thumbs-swiper" id="projects-thumbs">
				<div class="swiper-wrapper">
					<?php foreach ( $projects as $idx => $proj ) : ?>
						<div class="swiper-slide">
							<button class="project-thumb<?php echo 0 === $idx ? ' is-active' : ''; ?>"
								data-index="<?php echo esc_attr( $idx ); ?>"
								data-before-img="<?php echo esc_url( $proj['before_img'] ); ?>"
								data-after-img="<?php echo esc_url( $proj['after_img'] ); ?>"
								data-tag="<?php echo esc_attr( $proj['tag'] ); ?>"
								data-title="<?php echo esc_attr( $proj['title'] ); ?>"
								data-area="<?php echo esc_attr( $proj['area'] ); ?>"
								data-duration="<?php echo esc_attr( $proj['duration'] ); ?>"
								data-year="<?php echo esc_attr( $proj['year'] ); ?>"
								data-quote="<?php echo esc_attr( $proj['quote'] ); ?>"
								data-author="<?php echo esc_attr( $proj['author'] ); ?>">
								<div class="project-thumb__img-wrap">
									<img src="<?php echo esc_url( $proj['thumb_img'] ); ?>"
										alt="<?php echo esc_attr( $proj['title'] ); ?>" loading="lazy" />
								</div>
								<div class="project-thumb__info">
									<span class="project-thumb__name"><?php echo esc_html( $proj['title'] ); ?></span>
									<span class="project-thumb__type"><?php echo esc_html( $proj['tag'] ); ?></span>
								</div>
								<div class="project-thumb__bar"></div>
							</button>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<!-- Side Arrow Buttons (PC) -->
			<button class="thumbs-nav__btn thumbs-nav__prev projects-thumbs-prev" aria-label="Dự án trước">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="m15 18-6-6 6-6" />
				</svg>
			</button>
			<button class="thumbs-nav__btn thumbs-nav__next projects-thumbs-next" aria-label="Dự án tiếp">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="m9 18 6-6-6-6" />
				</svg>
			</button>
		</div>

		<!-- Bottom nav: pagination + mobile prev/next buttons -->
		<div class="thumbs-nav">
			<button class="thumbs-nav__btn thumbs-nav__prev thumbs-nav__mobile-btn thumbs-mobile-prev"
				aria-label="Dự án trước">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="m15 18-6-6 6-6" />
				</svg>
			</button>
			<div class="thumbs-pagination"></div>
			<button class="thumbs-nav__btn thumbs-nav__next thumbs-nav__mobile-btn thumbs-mobile-next"
				aria-label="Dự án tiếp">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="m9 18 6-6-6-6" />
				</svg>
			</button>
		</div>

	</div>
</section>
