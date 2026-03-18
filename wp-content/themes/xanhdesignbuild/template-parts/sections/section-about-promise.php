<?php
/**
 * Template Part: Section 4 — The Promise (Sứ Mệnh).
 *
 * 2-column: left large typography, right content + 5 highlights + CTA.
 * ACF fields: about_promise_eyebrow, about_promise_title, about_promise_lead,
 *             about_promise_body, about_promise_cta_text, about_promise_cta_url.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$promise_eyebrow = get_field( 'about_promise_eyebrow' ) ?: 'Sứ Mệnh Xuyên Suốt';
$promise_title   = get_field( 'about_promise_title' ) ?: 'Đầu Mối Duy Nhất.<br>Trách Nhiệm Trọn Vẹn.';
$promise_lead    = get_field( 'about_promise_lead' ) ?: 'Xanh ra đời với sứ mệnh trở thành <strong class="text-primary font-bold">đầu mối duy nhất</strong> chịu trách nhiệm xuyên suốt — từ bản vẽ đầu tiên đến ngày bàn giao chìa khóa.';
$promise_body    = get_field( 'about_promise_body' ) ?: 'Sự liền mạch này chính là chìa khóa để giảm thiểu rủi ro, tối ưu giá trị cho gia chủ, và kiến tạo nên những không gian sống "Xanh" — nơi mỗi chi tiết đều có ý nghĩa.';
$promise_cta_text = get_field( 'about_promise_cta_text' ) ?: 'Khám Phá Hành Trình Của Bạn';
$promise_cta_url  = get_field( 'about_promise_cta_url' ) ?: '#';

// 5 compact highlights.
$highlights = [
	[ 'strong' => 'Thiết kế',  'text' => '— Tỉ mỉ từng chi tiết' ],
	[ 'strong' => 'Dự toán',   'text' => '— Minh bạch, rõ ràng' ],
	[ 'strong' => 'Vật liệu',  'text' => '— Chuẩn mực bền vững' ],
	[ 'strong' => 'Thi công',   'text' => '— Trọn vẹn cam kết' ],
	[ 'strong' => 'Bảo hành',  'text' => '— Đồng hành trường tồn' ],
];
?>

<section id="about-promise" class="bg-white relative w-full overflow-hidden py-12 md:py-16 lg:py-20">
	<div class="site-container">
		<div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">

			<!-- Left: Large Typography -->
			<div class="w-full lg:w-5/12 section-header section-header--left text-center lg:text-left anim-fade-up">
				<span class="promise-el section-eyebrow text-primary/70 block mb-4">
					<?php echo esc_html( $promise_eyebrow ); ?>
				</span>
				<h2 class="promise-el section-title text-primary mb-6">
					<?php echo wp_kses_post( $promise_title ); ?>
				</h2>
			</div>

			<!-- Right: Content -->
			<div class="w-full lg:w-7/12 anim-fade-up">
				<div class="promise-el max-w-xl mx-auto lg:mx-0 mb-10 text-center lg:text-left">
					<p class="text-lead text-dark/80 mb-6">
						<?php echo wp_kses_post( $promise_lead ); ?>
					</p>
					<p class="text-body text-dark/60">
						<?php echo esc_html( $promise_body ); ?>
					</p>
				</div>

				<!-- 5 compact highlights -->
				<div class="promise-el grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6 max-w-xl mx-auto lg:mx-0">
					<?php foreach ( $highlights as $j => $hl ) :
						$span_extra = $j === 4 ? ' sm:col-span-2' : '';
					?>
						<div class="flex items-start gap-3 justify-center lg:justify-start<?php echo esc_attr( $span_extra ); ?>">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-primary shrink-0 mt-0.5"></i>
							<span class="text-dark/80 text-base font-body tracking-wide">
								<strong class="font-semibold text-dark"><?php echo esc_html( $hl['strong'] ); ?></strong>
								<?php echo esc_html( $hl['text'] ); ?>
							</span>
						</div>
					<?php endforeach; ?>
				</div>

				<!-- CTA -->
				<div class="promise-el mt-14 text-center lg:text-left">
					<a href="<?php echo esc_url( $promise_cta_url ); ?>" class="btn btn--primary group rounded-sm">
						<span><?php echo esc_html( $promise_cta_text ); ?></span>
						<i data-lucide="arrow-right"
							class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1.5"></i>
					</a>
				</div>
			</div>

		</div>
	</div>
</section>
