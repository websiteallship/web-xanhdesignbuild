<?php
/**
 * Template Part: Section SV FAQ (S7).
 *
 * Accordion FAQ with FAQPage JSON-LD schema.
 * Accordion JS in service-detail.js (XanhServiceDetail.initFAQ).
 *
 * ACF fields: sv_faq_eyebrow, sv_faq_title,
 *             sv_faq_items (repeater: question, answer).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = get_field( 'sv_faq_eyebrow' ) ?: 'Giải Đáp Thắc Mắc';
$title   = get_field( 'sv_faq_title' ) ?: 'Những Câu Hỏi <em class="text-primary not-italic">Thường Gặp</em>';

$faq_items = get_field( 'sv_faq_items' );
if ( ! $faq_items ) {
	$faq_items = [
		[
			'question' => 'Quy trình thiết kế kiến trúc và nội thất tại XANH thường mất bao lâu?',
			'answer'   => 'Thời gian thiết kế phụ thuộc vào quy mô và độ phức tạp của từng dự án. Thông thường, một bộ hồ sơ thiết kế hoàn chỉnh mất từ 4 đến 6 tuần. Chúng tôi dành thời gian này để nghiên cứu kỹ lưỡng vị trí địa lý, hướng nắng gió và thấu hiểu thói quen sinh hoạt của gia đình, đảm bảo bản vẽ cuối cùng mang đậm dấu ấn cá nhân của bạn.',
		],
		[
			'question' => 'Hồ sơ thiết kế tại XANH bao gồm những gì?',
			'answer'   => 'Hồ sơ thiết kế của chúng tôi mang tiêu chuẩn quốc tế, bao gồm: Bản vẽ phối cảnh 3D siêu thực (sát thực tế 98%), Bản vẽ kỹ thuật thi công kiến trúc, Bản vẽ kết cấu - ME (điện nước) và Bản vẽ chi tiết nội thất. Tất cả đều được thể hiện chi tiết, minh bạch từng loại vật liệu để bạn dễ dàng theo dõi quá trình thi công.',
		],
		[
			'question' => 'Liệu không gian thực tế có giống như bản vẽ 3D?',
			'answer'   => 'Tại XANH, chúng tôi cam kết thi công thực tế sát với bản vẽ 3D lên đến 98%. Mỗi vật liệu, màu sắc và ánh sáng trong bản vẽ đều được tính toán dựa trên các vật tư có sẵn tại Việt Nam và trải qua quá trình lựa chọn khắt khe tại showroom cùng chủ đầu tư trước khi đưa vào thi công.',
		],
		[
			'question' => 'Phong cách thiết kế thế mạnh của XANH là gì?',
			'answer'   => 'XANH không định hình mình vào một khuôn mẫu cứng nhắc. Chúng tôi theo đuổi triết lý "Warm Luxury" – sự sang trọng tinh tế, ấm áp và vượt thời gian. Dù là phong cách Hiện đại, Tropical hay Indochine, XANH đều tập trung tối ưu công năng, ứng dụng vật liệu tự nhiên và đưa ánh sáng vào từng ngóc ngách không gian.',
		],
	];
}
?>

<section id="service-faq" class="s7-faq">
	<div class="site-container">

		<!-- Section Header -->
		<div class="s7-faq__header anim-fade-up section-header section-header--center">
			<span class="section-eyebrow s7-faq__eyebrow anim-fade-up"><?php echo esc_html( $eyebrow ); ?></span>
			<h2 class="section-title s7-faq__title anim-fade-up"><?php echo wp_kses_post( $title ); ?></h2>
		</div>

		<?php if ( $faq_items ) : ?>
			<div class="faq-list anim-fade-up" id="faq-list">
				<?php foreach ( $faq_items as $index => $item ) :
					$is_first = ( 0 === $index );
					$question = $item['question'] ?? '';
					$answer   = $item['answer'] ?? '';
					if ( ! $question ) {
						continue;
					}
				?>
					<div class="faq-item<?php echo $is_first ? ' is-open' : ''; ?>">
						<button class="faq-item__question" aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>">
							<span><?php echo esc_html( $question ); ?></span>
							<span class="faq-item__icon">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
							</span>
						</button>
						<div class="faq-item__answer"<?php echo $is_first ? ' style="max-height:300px;"' : ''; ?>>
							<div class="faq-item__answer-inner">
								<?php echo esc_html( $answer ); ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>

<?php
// ─── FAQPage JSON-LD Schema ───
if ( $faq_items ) :
	$schema_items = [];
	foreach ( $faq_items as $item ) {
		$q = $item['question'] ?? '';
		$a = $item['answer'] ?? '';
		if ( ! $q || ! $a ) {
			continue;
		}
		$schema_items[] = [
			'@type'          => 'Question',
			'name'           => $q,
			'acceptedAnswer' => [
				'@type' => 'Answer',
				'text'  => $a,
			],
		];
	}
	if ( $schema_items ) :
		$schema = [
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => $schema_items,
		];
	?>
		<script type="application/ld+json"><?php echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?></script>
	<?php endif; ?>
<?php endif; ?>
