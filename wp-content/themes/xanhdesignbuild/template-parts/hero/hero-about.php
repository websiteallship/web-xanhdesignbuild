<?php
/**
 * Template Part: Hero — About Page.
 *
 * Static hero image (no slider) with text overlay, CTA button,
 * video play button, video modal, and scroll indicator.
 *
 * ACF fields: about_hero_eyebrow, about_hero_title, about_hero_subtitle,
 *             about_hero_image, about_hero_cta_text, about_hero_video_url.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$eyebrow   = get_field( 'about_hero_eyebrow' ) ?: 'Câu Chuyện Của Chúng Tôi';
$headline  = get_field( 'about_hero_title' ) ?: 'Xanh — Design & Build<br /><span class="font-light">Câu Chuyện Của Sự Liền Mạch & Bền Vững.</span>';
$subtitle  = get_field( 'about_hero_subtitle' ) ?: 'Chúng tôi là thương hiệu cung cấp giải pháp nội thất và xây dựng hoàn thiện theo hướng bền vững. Không chỉ thiết kế một không gian, chúng tôi kiến tạo những công trình đáng để đầu tư.';
$cta_text  = get_field( 'about_hero_cta_text' ) ?: 'Khám Phá Hành Trình Xanh';
$image     = xanh_get_image( 'about_hero_image' );
$video_url = get_field( 'about_hero_video_url' ) ?: 'https://www.youtube.com/embed/dQw4w9WgXcQ';

// Fallback image.
$img_url = $image['url'] ?? esc_url( XANH_THEME_URI . '/assets/images/about-hero-bg.png' );
$img_alt = $image['alt'] ?? 'Không gian nội thất sang trọng — XANH Design & Build';
$img_id  = $image['ID'] ?? null;

// Build video embed URL with autoplay params.
$video_embed_url = '';
if ( $video_url ) {
	// Convert watch URL to embed URL if needed.
	if ( strpos( $video_url, 'watch?v=' ) !== false ) {
		$video_embed_url = preg_replace(
			'/https?:\/\/(www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
			'https://www.youtube.com/embed/$2',
			$video_url
		);
	} elseif ( strpos( $video_url, 'youtu.be/' ) !== false ) {
		$video_embed_url = preg_replace(
			'/https?:\/\/youtu\.be\/([a-zA-Z0-9_-]+)/',
			'https://www.youtube.com/embed/$1',
			$video_url
		);
	} else {
		$video_embed_url = $video_url;
	}
	$video_embed_url .= ( strpos( $video_embed_url, '?' ) !== false ? '&' : '?' ) . 'autoplay=1&rel=0&modestbranding=1';
}
?>

<section id="about-hero" class="relative w-full h-screen overflow-hidden">

	<!-- Background Image (static, no slider — for performance per spec) -->
	<div class="about-hero__bg absolute inset-0 w-full h-full">
		<?php if ( $img_id ) :
			echo wp_get_attachment_image( $img_id, 'full', false, [
				'class'   => 'w-full h-full object-cover',
				'loading' => 'eager',
				'sizes'   => '100vw',
			] );
		else : ?>
			<img src="<?php echo esc_url( $img_url ); ?>"
				alt="<?php echo esc_attr( $img_alt ); ?>"
				class="w-full h-full object-cover"
				width="1920" height="1080" />
		<?php endif; ?>
	</div>

	<!-- Dark overlay gradient -->
	<div class="absolute inset-0 bg-gradient-to-b from-black/60 via-primary/50 to-primary/80 z-10"></div>

	<!-- Content Overlay -->
	<div
		class="about-hero__content relative z-20 flex flex-col justify-center h-full site-container site-container--hero">
		<div class="max-w-2xl lg:max-w-3xl">

			<!-- Eyebrow / Sub-label -->
			<span
				class="about-hero-el block text-xs md:text-sm font-semibold tracking-[0.2em] uppercase text-white/50 mb-5 md:mb-6">
				<?php echo esc_html( $eyebrow ); ?>
			</span>

			<!-- Headline -->
			<h1 id="about-hero-headline"
				class="about-hero-el text-white text-3xl md:text-4xl lg:text-5xl xl:text-[3.5rem] font-bold !leading-[1.35] tracking-[-0.02em] mb-7 md:mb-9">
				<?php echo wp_kses_post( $headline ); ?>
			</h1>

			<!-- Sub-headline -->
			<p id="about-hero-subheadline"
				class="about-hero-el text-white/70 text-base md:text-lg font-light !leading-[2] tracking-wide max-w-xl mb-10 md:mb-12">
				<?php echo esc_html( $subtitle ); ?>
			</p>

			<!-- Action Row: CTA + Video Play Button -->
			<div class="about-hero-el flex flex-wrap items-center gap-5 md:gap-8">
				<!-- CTA Button -->
				<a href="#about-pain" class="btn btn--primary group">
					<span><?php echo esc_html( $cta_text ); ?></span>
					<i data-lucide="arrow-down"
						class="w-4 h-4 transition-transform duration-300 group-hover:translate-y-0.5"></i>
				</a>

				<?php if ( $video_embed_url ) : ?>
					<!-- Video Play Button -->
					<button id="video-play-btn" class="video-play-btn group" aria-label="<?php esc_attr_e( 'Xem video giới thiệu công ty', 'xanh' ); ?>">
						<span class="video-play-btn__circle">
							<svg class="w-5 h-5 md:w-6 md:h-6 text-white ml-0.5" viewBox="0 0 24 24" fill="currentColor">
								<path d="M8 5v14l11-7z" />
							</svg>
						</span>
						<span
							class="video-play-btn__label text-white/80 text-sm font-medium tracking-wide group-hover:text-white transition-colors duration-300">
							<?php esc_html_e( 'Xem Video Giới Thiệu', 'xanh' ); ?>
						</span>
					</button>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- Scroll indicator -->
	<div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center gap-2">
		<span class="text-white/40 text-[10px] uppercase tracking-[0.2em] font-medium"><?php esc_html_e( 'Khám phá', 'xanh' ); ?></span>
		<div class="scroll-indicator">
			<div class="scroll-indicator__dot"></div>
		</div>
	</div>
</section>

<?php if ( $video_embed_url ) : ?>
	<!-- ============================================= -->
	<!-- VIDEO MODAL                                   -->
	<!-- ============================================= -->
	<div id="video-modal" class="video-modal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Video giới thiệu công ty', 'xanh' ); ?>">
		<!-- Backdrop -->
		<div class="video-modal__backdrop" id="video-modal-backdrop"></div>

		<!-- Modal Content -->
		<div class="video-modal__container">
			<!-- Close button -->
			<button class="video-modal__close" id="video-modal-close" aria-label="<?php esc_attr_e( 'Đóng video', 'xanh' ); ?>">
				<i data-lucide="x" class="w-6 h-6"></i>
			</button>

			<!-- Video wrapper (16:9) -->
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
