<?php
/**
 * Template Part: Section SV Empathy (S2).
 *
 * Asymmetric editorial layout — text left, image right.
 *
 * ACF fields: sv_empathy_eyebrow, sv_empathy_title,
 *             sv_empathy_body (wysiwyg), sv_empathy_image.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = get_field( 'sv_empathy_eyebrow' ) ?: 'Sự Đồng Cảm Chuyên Sâu';
$title   = get_field( 'sv_empathy_title' ) ?: 'Hơn Cả Một Bản Vẽ <br />Đó Là Sự Thấu Hiểu Lối Sống.';
$body    = get_field( 'sv_empathy_body' );
$image   = xanh_get_image( 'sv_empathy_image' );

// Fallback body
if ( ! $body ) {
	$body = '<p>Lựa chọn đơn vị <strong>thiết kế kiến trúc và nội thất</strong> không chỉ là giao việc, mà là trao gửi niềm tin. Chúng tôi hiểu những nỗi trăn trở: Liệu không gian có phản ánh cá tính gia đình? Bản vẽ có khả thi? Chi phí có vượt dự toán?</p><p>Tại XANH, mọi đường nét đều bắt nguồn từ chính câu chuyện của bạn. Chúng tôi mang đến sự bình yên với quy trình làm việc chuẩn mực — <strong class="text-primary font-semibold"> đầu tư đều minh bạch, rõ ràng, không ngoại lệ.</strong></p>';
}

// Fallback image URL
$img_url = '';
if ( $image ) {
	$img_url = $image['url'];
} else {
	$img_url = site_url( '/wp-content/uploads/2026/03/project-after-1.png' );
}
?>

<section id="service-empathy" class="empathy-section">
	<div class="empathy-section__inner">

		<!-- LEFT: Text block -->
		<div class="empathy-section__text">

			<!-- Eyebrow -->
			<span class="section-eyebrow text-primary/50 relative inline-block mb-4 empathy-anim" data-anim="fade-right">
				<?php echo esc_html( $eyebrow ); ?>
			</span>

			<!-- H2 Headline -->
			<h2 class="empathy-headline empathy-anim" data-anim="fade-up">
				<?php echo wp_kses_post( $title ); ?>
			</h2>

			<!-- Body -->
			<div class="empathy-body empathy-anim" data-anim="fade-up">
				<?php echo wp_kses_post( $body ); ?>
			</div>

		</div>

		<!-- RIGHT: Editorial image (overflows left) -->
		<div class="empathy-section__image empathy-anim" data-anim="fade-left">
			<div class="empathy-image-wrap">
				<?php if ( $image ) : ?>
					<?php
					echo wp_get_attachment_image( $image['ID'], 'large', false, [
						'class'   => 'w-full h-full object-cover',
						'alt'     => esc_attr( $image['alt'] ?? 'Không gian sống — XANH Design & Build' ),
						'loading' => 'lazy',
						'width'   => 540,
						'height'  => 720,
					] );
					?>
				<?php else : ?>
					<img src="<?php echo esc_url( $img_url ); ?>"
						alt="Không gian sống biệt thự hiện đại — XANH Design & Build"
						width="540" height="720" loading="lazy" />
				<?php endif; ?>
				<!-- Subtle hover overlay -->
				<div class="empathy-image-overlay"></div>
			</div>
		</div>

	</div>
</section>
