<?php
/**
 * Template Part: Section 2 — Pain Points (Nỗi Trăn Trở).
 *
 * Sticky left headline + scrollable pain-point list on right.
 * ACF repeater: about_pain_items (icon, title, quote).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$pain_eyebrow = get_field( 'about_pain_eyebrow' ) ?: 'Khởi nguồn từ những trăn trở';
$pain_title   = get_field( 'about_pain_title' ) ?: 'Mọi Điểm Chạm Bắt Đầu<br class="hidden lg:block" /> Từ Một Sự Thật...';
$pain_desc    = get_field( 'about_pain_subtitle' ) ?: 'Xuất phát là những chuyên gia trong lĩnh vực giải pháp nội thất hoàn thiện và vật tư, trải qua nhiều dự án lớn nhỏ, chúng tôi đã chứng kiến 5 "nỗi đau" lớn mà các chủ đầu tư thường xuyên phải đối mặt:';

// Pain items — ACF repeater or fallback.
$pain_items = get_field( 'about_pain_items' );
if ( empty( $pain_items ) ) {
	$pain_items = [
		[ 'icon' => 'image-off',    'title' => 'Bản vẽ tính thực thi thấp',  'quote' => '"Bản vẽ thiết kế đẹp nhưng thi công không giống, hoặc hoàn toàn không thể thực thi trong điều kiện thực tế."' ],
		[ 'icon' => 'trending-up',  'title' => 'Dự toán đội phí',            'quote' => '"Bảng dự toán ban đầu rất thấp để chào mời, nhưng rồi lại đội chi phí rất cao và phát sinh liên tục trong quá trình làm."' ],
		[ 'icon' => 'split',        'title' => 'Đùn đẩy trách nhiệm',        'quote' => '"Đơn vị thiết kế và đơn vị thi công không đồng nhất, dẫn đến đùn đẩy trách nhiệm khi có sự cố xảy ra."' ],
		[ 'icon' => 'hammer',       'title' => 'Xuống cấp nhanh chóng',      'quote' => '"Công trình vừa thi công xong đã nhanh xuống cấp, thường xuyên phải gọi bảo hành và công năng không được tối ưu."' ],
		[ 'icon' => 'shield-alert', 'title' => 'Thiếu cam kết tổng thể',     'quote' => '"Không có một ai đứng ra chịu trách nhiệm tổng thể quy trình và cam kết thời gian hoàn thành cụ thể rõ ràng."' ],
	];
}
?>

<section id="about-pain" class="bg-white relative w-full border-t border-dark/5">
	<div class="site-container">
		<div class="flex flex-col lg:flex-row relative items-start">

			<!-- Left Column: Sticky Headline -->
			<div
				class="w-full lg:w-5/12 lg:sticky top-[100px] pt-20 lg:pt-32 pb-12 lg:pb-32 pr-0 lg:pr-12 lg:min-h-screen flex flex-col justify-start">
				<div class="about-pain-header section-header section-header--left anim-fade-up">
					<span class="section-eyebrow text-primary/50 relative inline-block mb-4">
						<?php echo esc_html( $pain_eyebrow ); ?>
					</span>
					<h2 class="section-title text-primary mb-6 lg:mb-8">
						<?php echo wp_kses_post( $pain_title ); ?>
					</h2>
					<p class="section-subtitle text-dark/80">
						<?php echo esc_html( $pain_desc ); ?>
					</p>
				</div>
			</div>

			<!-- Right Column: Scrollable List -->
			<div class="w-full lg:w-7/12 pt-0 lg:pt-32 pb-24 lg:pb-32 lg:pl-16 relative">
				<!-- Desktop vertical divider line -->
				<div
					class="pain-divider-line hidden lg:block absolute left-0 top-0 w-px h-[calc(100%-8rem)] bg-dark/10 mt-32">
				</div>

				<div class="flex flex-col gap-0 pain-list">
					<?php foreach ( $pain_items as $i => $item ) :
						$icon    = $item['icon'] ?? 'circle';
						$title   = $item['title'] ?? '';
						$quote   = $item['quote'] ?? '';
						$border  = $i === 0 ? 'first:border-0 lg:first:border-t lg:first:-mt-[1px]' : '';
						$last_border = $i === count( $pain_items ) - 1 ? ' border-b' : '';
					?>
						<div class="pain-el anim-fade-up group relative py-10 lg:py-12 border-t<?php echo esc_attr( $last_border ); ?> border-dark/10 <?php echo esc_attr( $border ); ?>">
							<div class="flex items-start gap-5 md:gap-7">
								<div class="icon-circle shrink-0">
									<i data-lucide="<?php echo esc_attr( $icon ); ?>" class="w-5 h-5 md:w-6 md:h-6"></i>
								</div>
								<div>
									<h3
										class="font-heading text-xl md:text-2xl font-bold text-dark mb-3 lg:mb-4 tracking-[-0.02em] group-hover:text-primary transition-colors duration-300">
										<?php echo esc_html( $title ); ?></h3>
									<p class="text-quote">
										<?php echo esc_html( $quote ); ?>
									</p>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
	</div>
</section>
