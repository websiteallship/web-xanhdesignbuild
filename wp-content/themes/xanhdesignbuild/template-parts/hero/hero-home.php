<?php
/**
 * Template Part: Hero — Homepage.
 *
 * Swiper slider with fixed text overlay.
 * ACF fields: hero_slides (Repeater), hero_headline, hero_subheadline, hero_cta_text, hero_cta_url.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$hero_slides     = get_field( 'hero_slides' );
$hero_headline   = get_field( 'hero_headline' ) ?: 'Đừng Chỉ Xây Một Ngôi Nhà.<br><span class="font-light">Hãy Xây Dựng Sự Bình Yên.</span>';
$hero_subheadline = get_field( 'hero_subheadline' ) ?: 'Tại Xanh, chúng tôi tin rằng hành trình kiến tạo tổ ấm không nên bắt đầu bằng sự lo âu...';
$hero_cta_text   = get_field( 'hero_cta_text' ) ?: 'Lắng Nghe Câu Chuyện Của Xanh';
$hero_cta_url    = get_field( 'hero_cta_url' ) ?: '#empathy';

// Default slides if no ACF data.
$default_slides = [
	[ 'image' => [ 'url' => esc_url( XANH_THEME_URI . '/assets/images/hero-house.png' ), 'alt' => 'Biệt thự hiện đại giữa thiên nhiên — XANH' ] ],
	[ 'image' => [ 'url' => esc_url( XANH_THEME_URI . '/assets/images/hero-bg.png' ), 'alt' => 'Gia đình hạnh phúc trong tổ ấm — XANH' ] ],
	[ 'image' => [ 'url' => esc_url( XANH_THEME_URI . '/assets/images/project-1.png' ), 'alt' => 'Nhà phố nhiệt đới hiện đại — XANH' ] ],
];

$slides = $hero_slides ?: $default_slides;
?>

<section id="hero" class="relative w-full h-screen overflow-hidden">

	<!-- Background Slider (visual showcase only) -->
	<div class="swiper hero-swiper absolute inset-0 w-full h-full">
		<div class="swiper-wrapper">
			<?php foreach ( $slides as $slide ) :
				if ( $hero_slides && isset( $slide['image']['ID'] ) ) :
					// ACF image field — use wp_get_attachment_image for responsive srcset.
					echo '<div class="swiper-slide">';
					echo wp_get_attachment_image( $slide['image']['ID'], 'full', false, [
						'class'   => 'w-full h-full object-cover',
						'loading' => 'eager',
						'sizes'   => '100vw',
					] );
					echo '</div>';
				else :
					// Fallback static image.
					$img_url = esc_url( $slide['image']['url'] ?? '' );
					$img_alt = esc_attr( $slide['image']['alt'] ?? '' );
					?>
					<div class="swiper-slide">
						<img src="<?php echo esc_url( $slide['image']['url'] ?? '' ); ?>"
							alt="<?php echo esc_attr( $slide['image']['alt'] ?? '' ); ?>"
							class="w-full h-full object-cover"
							width="1920" height="1080" />
					</div>
				<?php endif;
			endforeach; ?>
		</div>
	</div>

	<!-- Dark overlay -->
	<div class="absolute inset-0 bg-gradient-to-b from-black/60 via-primary/50 to-primary/80 z-10"></div>

	<!-- Fixed Content Overlay -->
	<div class="hero-content relative z-20 flex flex-col justify-center h-full site-container site-container--hero">
		<div class="max-w-2xl lg:max-w-3xl">
			<!-- Headline -->
			<h1 id="hero-headline"
				class="hero-headline text-white text-3xl md:text-4xl lg:text-5xl xl:text-[3.2rem] font-bold !leading-[1.5] tracking-[-0.02em] mb-7 md:mb-9">
				<?php echo wp_kses_post( $hero_headline ); ?>
			</h1>
			<!-- Sub-headline -->
			<p id="hero-subheadline"
				class="hero-subheadline text-white/70 text-base md:text-lg font-light !leading-[2] tracking-wide max-w-xl mb-9 md:mb-11">
				<?php echo esc_html( $hero_subheadline ); ?>
			</p>
			<!-- CTA Button -->
			<a href="<?php echo esc_url( $hero_cta_url ); ?>" id="hero-cta"
				class="hero-cta btn btn--primary shadow-lg shadow-accent/20 hover:shadow-accent/30 hover:shadow-xl group">
				<span><?php echo esc_html( $hero_cta_text ); ?></span>
				<i data-lucide="arrow-down" class="w-4 h-4 transition-transform duration-300 group-hover:translate-y-0.5"></i>
			</a>
		</div>
	</div>

	<!-- Pagination Dots -->
	<div class="swiper-pagination hero-pagination"></div>
</section>
