<?php
/**
 * Template Part: Hero — Service Detail Page (S1).
 *
 * Full-viewport hero with background image, headline, subtitle,
 * and counter strip (Sát 3D / Hồ Sơ Thiết Kế / Phát Sinh).
 * Pattern inherited from hero-portfolio.php.
 *
 * ACF fields: sv_hero_eyebrow, sv_hero_title, sv_hero_desc, sv_hero_image,
 *             sv_counter_items (repeater: number, suffix, label).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── ACF fields with fallbacks ──
$eyebrow = get_field( 'sv_hero_eyebrow' ) ?: 'Dịch Vụ — Thiết Kế Kiến Trúc & Nội Thất';
$title   = get_field( 'sv_hero_title' ) ?: 'Không Gian Sống Bản Sắc.<br class="hidden sm:block" /> Hành Trình Kiến Tạo Di Sản.';
$desc    = get_field( 'sv_hero_desc' ) ?: 'Thấu hiểu rằng tổ ấm là tài sản lớn nhất, dịch vụ <strong>thiết kế kiến trúc và nội thất</strong> tại XANH ra đời để biến khát vọng của bạn thành hiện thực. Nghệ thuật không gian kết hợp tính chính xác tuyệt đối — cam kết 98% sát bản vẽ 3D.';
$image   = xanh_get_image( 'sv_hero_image' );

// Counter items from ACF repeater or fallback
$counters = get_field( 'sv_counter_items' );
if ( ! $counters ) {
	$counters = [
		[ 'number' => '98',  'suffix' => '%', 'label' => 'Sát 3D' ],
		[ 'number' => '200', 'suffix' => '+', 'label' => 'Hồ Sơ Thiết Kế' ],
		[ 'number' => '0',   'suffix' => '%', 'label' => 'Phát Sinh' ],
	];
}

// Hero background image URL
$hero_img_url = '';
if ( $image ) {
	$hero_img_url = $image['url'];
} else {
	$hero_img_url = site_url( '/wp-content/uploads/2026/03/service-architecture.png' );
}
?>

<section id="service-hero" class="service-hero relative w-full overflow-hidden">

	<!-- Background image -->
	<div class="service-hero__bg absolute inset-0 w-full h-full">
		<?php if ( $image ) : ?>
			<?php
			echo wp_get_attachment_image( $image['ID'], 'full', false, [
				'class'         => 'w-full h-full object-cover',
				'alt'           => esc_attr( $image['alt'] ?? 'Thiết kế kiến trúc — XANH Design & Build' ),
				'fetchpriority' => 'high',
				'decoding'      => 'async',
			] );
			?>
		<?php else : ?>
			<img src="<?php echo esc_url( $hero_img_url ); ?>"
				alt="Thiết kế kiến trúc biệt thự hiện đại — XANH Design & Build"
				class="w-full h-full object-cover" width="1920" height="1080" fetchpriority="high" />
		<?php endif; ?>
	</div>

	<!-- Gradient overlay -->
	<div class="absolute inset-0 bg-gradient-to-b from-black/60 via-primary/50 to-primary/80 z-[1]"></div>

	<!-- Centered content -->
	<div class="relative z-10 flex flex-col justify-center items-center text-center h-full site-container px-6">
		<div class="max-w-3xl mx-auto">
			<span class="service-hero-el section-eyebrow text-white/60 block mb-5">
				<?php echo esc_html( $eyebrow ); ?>
			</span>
			<h1 class="service-hero-el section-title section-title--light text-white font-bold tracking-[-0.02em] leading-tight mb-6">
				<?php echo wp_kses_post( $title ); ?>
			</h1>
			<p class="service-hero-el text-white/70 text-base md:text-lg max-w-xl mx-auto leading-relaxed">
				<?php echo wp_kses_post( $desc ); ?>
			</p>
		</div>
	</div>

	<!-- Counter strip — bottom bar -->
	<?php if ( $counters ) : ?>
		<div class="relative z-10 w-full border-t border-white/10">
			<div class="site-container">
				<div class="counter-strip flex items-center justify-center gap-5 sm:gap-8 md:gap-16 lg:gap-24 py-3 sm:py-6 md:py-8">
					<?php foreach ( $counters as $ci => $counter ) :
						$num    = $counter['number'] ?? '0';
						$suffix = $counter['suffix'] ?? '';
						$label  = $counter['label'] ?? '';
					?>
						<?php if ( $ci > 0 ) : ?>
							<div class="counter-divider w-px h-10 bg-white/15"></div>
						<?php endif; ?>
						<div class="counter-item text-center">
							<span class="counter-number text-white font-bold text-2xl md:text-3xl lg:text-4xl"
								data-target="<?php echo esc_attr( $num ); ?>">0</span><span class="text-accent font-bold text-2xl md:text-3xl lg:text-4xl"><?php echo esc_html( $suffix ); ?></span>
							<p class="text-white/50 text-xs md:text-sm mt-1 uppercase tracking-wider"><?php echo esc_html( $label ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

</section>
