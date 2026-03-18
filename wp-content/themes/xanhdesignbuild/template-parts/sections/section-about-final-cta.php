<?php
/**
 * Template Part: Section 7 — Final CTA (Khởi Đầu Hành Trình).
 *
 * 12-column grid: left typography + CTA buttons, right portrait image.
 * ACF fields: about_cta_eyebrow, about_cta_title, about_cta_subtitle,
 *             about_cta_btn1_text, about_cta_btn1_url, about_cta_btn2_text,
 *             about_cta_btn2_url, about_cta_image.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cta_eyebrow  = get_field( 'about_cta_eyebrow' ) ?: 'Khởi Đầu Hành Trình';
$cta_title    = get_field( 'about_cta_title' ) ?: 'Hành trình kiến tạo không gian bắt đầu bằng một cuộc trò chuyện.';
$cta_subtitle = get_field( 'about_cta_subtitle' ) ?: 'Chúng tôi hiểu — xây nhà là quyết định lớn nhất đời. Đặt lịch trao đổi riêng để mỗi mong ước của bạn được lắng nghe, và để XANH đồng hành cùng bạn từ bản vẽ đầu tiên.';
$cta_btn1_text = get_field( 'about_cta_btn1_text' ) ?: 'Đặt Lịch Tư Vấn Riêng';
$cta_btn1_url  = get_field( 'about_cta_btn1_url' ) ?: '/lien-he/';
$cta_btn2_text = get_field( 'about_cta_btn2_text' ) ?: 'Khám Phá Các Tác Phẩm';
$cta_btn2_url  = get_field( 'about_cta_btn2_url' ) ?: '/du-an/';
$cta_image     = xanh_get_image( 'about_cta_image' );
$cta_img_id    = $cta_image['ID'] ?? null;
$cta_img_url   = $cta_image['url'] ?? content_url( 'uploads/2026/03/cta-portrait.png' );
?>

<section id="about-final-cta" class="bg-light relative w-full overflow-hidden py-12 md:py-16 lg:py-20">
	<div class="site-container">
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

			<!-- Left: Typography & CTA Buttons -->
			<div class="w-full lg:col-span-7 flex flex-col items-center lg:items-start text-center lg:text-left">
				<div class="section-header section-header--left w-full">
					<span id="cta-eyebrow" class="section-eyebrow text-primary/50 block mb-4">
						<?php echo esc_html( $cta_eyebrow ); ?>
					</span>
					<h2 id="cta-title" class="section-title text-primary mb-6 tracking-[-0.02em] font-bold">
						<?php echo esc_html( $cta_title ); ?>
					</h2>
					<p id="cta-subtitle" class="section-subtitle text-dark/80 max-w-xl mx-auto lg:mx-0">
						<?php echo esc_html( $cta_subtitle ); ?>
					</p>
				</div>

				<div id="cta-buttons"
					class="mt-8 lg:mt-10 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 sm:gap-6 w-full sm:w-auto">
					<a href="<?php echo esc_url( $cta_btn1_url ); ?>" class="btn btn--primary w-full sm:w-auto justify-center group">
						<span><?php echo esc_html( $cta_btn1_text ); ?></span>
						<i data-lucide="arrow-right"
							class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1.5"></i>
					</a>
					<a href="<?php echo esc_url( $cta_btn2_url ); ?>" class="btn btn--outline w-full sm:w-auto justify-center group">
						<span><?php echo esc_html( $cta_btn2_text ); ?></span>
						<i data-lucide="chevron-down" class="w-5 h-5"></i>
					</a>
				</div>
			</div>

			<!-- Right: Portrait Editorial Image -->
			<div id="cta-image-col" class="w-full lg:col-span-5">
				<div class="relative w-full aspect-[4/5] overflow-hidden rounded-sm shadow-2xl">
					<!-- Decorative outline frame -->
					<div class="absolute inset-4 border border-white/30 z-10 pointer-events-none"></div>
					<?php if ( $cta_img_id ) :
						echo wp_get_attachment_image( $cta_img_id, 'large', false, [
							'class'   => 'w-full h-full object-cover transition-transform duration-1000 ease-out hover:scale-105',
							'loading' => 'lazy',
						] );
					else : ?>
						<img src="<?php echo esc_url( $cta_img_url ); ?>" alt="<?php esc_attr_e( 'Chi tiết kiến trúc không gian ấm cúng', 'xanh' ); ?>"
							class="w-full h-full object-cover transition-transform duration-1000 ease-out hover:scale-105"
							width="480" height="600" loading="lazy">
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>
</section>
