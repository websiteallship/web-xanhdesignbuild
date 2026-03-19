<?php
/**
 * Template Part: Section — CTA Contact (Đặt Lịch Tư Vấn).
 *
 * Full-width background image with green-tinted overlay and centered CTA.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uploads = content_url( '/uploads/2026/03/' );
$bg_img  = $uploads . 'interior-living.png';
?>

<section id="cta-contact" class="cta-contact-section" aria-label="Đặt lịch tư vấn">
	<!-- Background Image -->
	<img src="<?php echo esc_url( $bg_img ); ?>" alt="" class="cta-contact__bg" aria-hidden="true" width="1920" height="1080" loading="lazy" />
	<!-- Dark Overlay -->
	<div class="cta-contact__overlay"></div>

	<!-- Content -->
	<div class="site-container cta-contact__container">
		<p class="anim-fade-up cta-contact__eyebrow">Bắt Đầu Hành Trình</p>

		<h2 class="anim-fade-up cta-contact__heading">
			Mọi Tổ Ấm Bình Yên<br>Đều Bắt Đầu Từ<br>Một Cuộc Trò Chuyện.
		</h2>

		<p class="anim-fade-up cta-contact__body">
			Hãy để XANH lắng nghe câu chuyện của bạn — đồng hành từ những ý tưởng sơ khai nhất đến ngày trao tay chiếc chìa khóa.
		</p>

		<a href="#contact" class="anim-fade-up btn btn--primary cta-contact__btn group">
			<span>Đặt Lịch Tư Vấn Riêng</span>
			<i data-lucide="phone" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5"></i>
		</a>
	</div>
</section>
