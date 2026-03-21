<?php
/**
 * Template Part: Hero — Homepage.
 *
 * Swiper slider with fixed text overlay, video play button, and video modal.
 * ACF fields: hero_slides (Repeater), hero_headline, hero_subheadline,
 *             hero_cta_text, hero_cta_url, hero_video_url.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$hero_slides     = get_field( 'hero_slides' );
$hero_eyebrow    = get_field( 'hero_eyebrow' ) ?: 'Kiến Tạo Tổ Ấm Bình Yên';
$hero_headline   = get_field( 'hero_headline' ) ?: 'Đừng Chỉ Xây Một Ngôi Nhà.<br><span class="font-light">Hãy Xây Dựng Sự Bình Yên.</span>';
$hero_subheadline = get_field( 'hero_subheadline' ) ?: 'Tại Xanh, chúng tôi tin rằng hành trình kiến tạo tổ ấm không nên bắt đầu bằng sự lo âu...';
$hero_cta_text   = get_field( 'hero_cta_text' ) ?: 'Lắng Nghe Câu Chuyện Của Xanh';
$hero_cta_url    = get_field( 'hero_cta_url' ) ?: '#empathy';
$hero_video_url  = get_field( 'hero_video_url' ) ?: '';

// Default slides if no ACF data.
$upload_dir = wp_get_upload_dir();
$upload_baseurl = $upload_dir['baseurl'];

$default_slides = [
	[ 'image' => [ 'url' => esc_url( $upload_baseurl . '/2026/03/hero-house.png' ), 'alt' => 'Biệt thự hiện đại giữa thiên nhiên — XANH' ] ],
	[ 'image' => [ 'url' => esc_url( $upload_baseurl . '/2026/03/hero-bg.png' ), 'alt' => 'Gia đình hạnh phúc trong tổ ấm — XANH' ] ],
	[ 'image' => [ 'url' => esc_url( $upload_baseurl . '/2026/03/project-1.png' ), 'alt' => 'Nhà phố nhiệt đới hiện đại — XANH' ] ],
];

$slides = $hero_slides ?: $default_slides;

// Build video embed URL.
$video_embed_url = '';
if ( $hero_video_url ) {
	$youtube_id = '';
	if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $hero_video_url, $match ) ) {
		$youtube_id = $match[1];
	}

	if ( $youtube_id ) {
		$video_embed_url = 'https://www.youtube.com/embed/' . $youtube_id . '?autoplay=1&rel=0&modestbranding=1';
	} else {
		// Fallback for Vimeo or direct links
		$video_embed_url = $hero_video_url;
		$video_embed_url .= ( strpos( $video_embed_url, '?' ) !== false ? '&' : '?' ) . 'autoplay=1';
	}
}
?>

<section id="hero" class="relative w-full h-screen overflow-hidden">

	<!-- Background Slider (visual showcase only) -->
	<div class="swiper hero-swiper absolute inset-0 w-full h-full">
		<div class="swiper-wrapper">
			<?php foreach ( $slides as $slide ) :
				if ( $hero_slides && isset( $slide['image']['ID'] ) ) :
					// ACF image field — use wp_get_attachment_image for responsive srcset.
					echo '<div class="swiper-slide">';
					echo wp_get_attachment_image( $slide['image']['ID'], 'xanh-hero', false, [
						'class'         => 'w-full h-full object-cover',
						'loading'       => 'eager',
						'fetchpriority' => 'high',
						'sizes'         => '100vw',
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
							width="1920" height="1080" loading="eager" fetchpriority="high" />
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
			<!-- Eyebrow / Sub-label -->
			<span
				class="home-hero-el block text-xs md:text-sm font-semibold tracking-[0.2em] uppercase text-white/50 mb-5 md:mb-6">
				<?php echo esc_html( $hero_eyebrow ); ?>
			</span>

			<!-- Headline -->
			<h1 id="hero-headline"
				class="home-hero-el text-white text-3xl md:text-4xl lg:text-5xl xl:text-[3.2rem] font-bold !leading-[1.5] tracking-[-0.02em] mb-7 md:mb-9">
				<?php echo wp_kses_post( $hero_headline ); ?>
			</h1>
			<!-- Sub-headline -->
			<p id="hero-subheadline"
				class="home-hero-el text-white/70 text-base md:text-lg font-light !leading-[2] tracking-wide max-w-xl mb-9 md:mb-11">
				<?php echo esc_html( $hero_subheadline ); ?>
			</p>

			<!-- Action Row: CTA + Video Play Button -->
			<div class="home-hero-el flex flex-wrap items-center gap-5 md:gap-8">
				<!-- CTA Button -->
				<a href="<?php echo esc_url( $hero_cta_url ); ?>" id="hero-cta"
					class="btn btn--primary shadow-lg shadow-accent/20 hover:shadow-accent/30 hover:shadow-xl group">
					<span><?php echo esc_html( $hero_cta_text ); ?></span>
					<i data-lucide="arrow-down" class="w-4 h-4 transition-transform duration-300 group-hover:translate-y-0.5"></i>
				</a>

				<?php if ( $video_embed_url ) : ?>
					<!-- Video Play Button -->
					<button id="video-play-btn" class="video-play-btn group" aria-label="<?php esc_attr_e( 'Xem video giới thiệu', 'xanh' ); ?>">
						<span class="video-play-btn__circle">
							<svg class="w-5 h-5 md:w-6 md:h-6 text-white ml-0.5" viewBox="0 0 24 24" fill="currentColor">
								<path d="M8 5v14l11-7z" />
							</svg>
						</span>
						<span
							class="video-play-btn__label text-white/80 text-sm font-medium tracking-wide group-hover:text-white transition-colors duration-300">
							<?php esc_html_e( 'Xem Video', 'xanh' ); ?>
						</span>
					</button>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- Navigation Dots -->
	<?php if ( count( $slides ) > 1 ) : ?>
		<div class="hero-dots" role="tablist" aria-label="<?php esc_attr_e( 'Hero slide navigation', 'xanh' ); ?>">
			<?php for ( $d = 0; $d < count( $slides ); $d++ ) : ?>
				<button class="hero-dot<?php echo 0 === $d ? ' is-active' : ''; ?>"
					data-goto="<?php echo esc_attr( $d ); ?>"
					aria-label="<?php echo esc_attr( 'Slide ' . ( $d + 1 ) ); ?>"
					role="tab"
					aria-selected="<?php echo 0 === $d ? 'true' : 'false'; ?>"></button>
			<?php endfor; ?>
		</div>
	<?php endif; ?>
</section>

<?php if ( $video_embed_url ) : ?>
	<!-- VIDEO MODAL -->
	<div id="video-modal" class="video-modal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Video giới thiệu', 'xanh' ); ?>">
		<div class="video-modal__backdrop" id="video-modal-backdrop"></div>
		<div class="video-modal__container">
			<button class="video-modal__close" id="video-modal-close" aria-label="<?php esc_attr_e( 'Đóng video', 'xanh' ); ?>">
				<i data-lucide="x" class="w-6 h-6"></i>
			</button>
			<div class="video-modal__video-wrap">
				<iframe id="video-iframe"
					src=""
					data-src="<?php echo esc_url( $video_embed_url ); ?>"
					title="<?php esc_attr_e( 'Video Giới Thiệu XANH Design & Build', 'xanh' ); ?>"
					frameborder="0"
					allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
					allowfullscreen>
				</iframe>
			</div>
		</div>
	</div>
<?php endif; ?>
