<?php
/**
 * Template Part: Section — Empathy (Nỗi Trăn Trở).
 *
 * Split layout: text left + portrait photo right.
 * ACF fields: empathy_eyebrow, empathy_headline, empathy_paragraphs (Repeater),
 *             empathy_quote, empathy_image.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$eyebrow    = get_field( 'empathy_eyebrow' ) ?: 'Chúng Tôi Hiểu';
$headline   = get_field( 'empathy_headline' ) ?: 'Chúng Tôi Hiểu — Xây Nhà Là Quyết Định Lớn Nhất Đời.';
$paragraphs = get_field( 'empathy_paragraphs' ) ?: [
	[ 'text' => 'Những bảng dự toán "chào mồi" thấp bất thường, rồi phát sinh liên tục. Vật liệu kém chất lượng được lén thay thế. Bảo hành "im lặng" sau bàn giao.' ],
	[ 'text' => 'Những gia chủ bắt đầu hành trình xây tổ ấm bằng sự háo hức, rồi kết thúc bằng sự kiệt sức và thất vọng.' ],
];
$quote      = get_field( 'empathy_quote' ) ?: '"Đó không phải là cách một tổ ấm được sinh ra. Và đó là lúc, <strong>Xanh</strong> chọn đi một con đường khác."';
$image      = get_field( 'empathy_image' );
$img_url    = $image['url'] ?? esc_url( XANH_THEME_URI . '/assets/images/empathy-moody.png' );
$img_alt    = $image['alt'] ?? 'Góc khuất ngành xây dựng — XANH Design & Build';
$img_id     = $image['ID'] ?? null;
?>

<section id="empathy" class="empathy-section section bg-white">
	<div class="site-container">
		<div class="empathy-split">

			<!-- LEFT: Text Block -->
			<div class="empathy-text">
				<!-- Sub-label -->
				<span class="anim-fade-up section-eyebrow block mb-6">
					<?php echo esc_html( $eyebrow ); ?>
				</span>

				<!-- Headline -->
				<h2 class="anim-fade-up section-title text-primary mb-8 md:mb-10">
					<?php echo esc_html( $headline ); ?>
				</h2>

				<!-- Body Paragraphs -->
				<div class="space-y-6 mb-10 md:mb-14">
					<?php foreach ( $paragraphs as $para ) : ?>
						<p class="anim-fade-up text-lead">
							<?php echo esc_html( $para['text'] ?? '' ); ?>
						</p>
					<?php endforeach; ?>
				</div>

				<!-- Highlighted Quote Box -->
				<blockquote class="anim-fade-up text-quote empathy-quote">
					<p><?php echo wp_kses_post( $quote ); ?></p>
				</blockquote>
			</div>

			<!-- RIGHT: Single Portrait Photo -->
			<div class="empathy-photo anim-fade-up">
				<?php if ( $img_id ) :
					echo wp_get_attachment_image( $img_id, 'large', false, [
						'class'   => 'w-full h-full object-cover',
						'loading' => 'lazy',
					] );
				else : ?>
					<img src="<?php echo esc_url( $img_url ); ?>"
						alt="<?php echo esc_attr( $img_alt ); ?>"
						class="w-full h-full object-cover"
						width="800" height="1000" loading="lazy" />
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>
