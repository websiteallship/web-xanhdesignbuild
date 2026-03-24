<?php
/**
 * Template Part: FAQ Accordion — Contact Page.
 *
 * Accordion FAQ section with ACF Repeater.
 * Also outputs FAQPage JSON-LD schema for SEO.
 *
 * ACF fields: contact_faq_eyebrow, contact_faq_title,
 *             contact_faq_items (repeater: question + answer).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$eyebrow = get_field( 'contact_faq_eyebrow' ) ?: 'Câu Hỏi Thường Gặp';
$title   = get_field( 'contact_faq_title' ) ?: 'Những Điều Bạn <em style="font-style:normal;color:var(--color-primary);">Muốn Biết</em>';

// FAQ items — ACF Repeater or fallback.
$faq_items = get_field( 'contact_faq_items' );
if ( ! $faq_items ) {
	$faq_items = [
		[
			'question' => 'Tôi có phải trả phí cho buổi tư vấn ban đầu không?',
			'answer'   => 'Hoàn toàn không. Buổi tư vấn đầu tiên tại XANH luôn miễn phí và không ràng buộc. Chúng tôi muốn hiểu rõ nhu cầu, ngân sách và phong cách sống mong muốn của bạn trước khi bắt đầu bất kỳ cam kết nào. Hãy xem đây là một cuộc trò chuyện thân mật giữa bạn và kiến trúc sư.',
		],
		[
			'question' => 'XANH thi công ở khu vực nào?',
			'answer'   => 'XANH hiện phục vụ khu vực Khánh Hòa và các tỉnh lân cận như Phú Yên, Ninh Thuận, Đắk Lắk. Với mỗi dự án, chúng tôi đều cử đội ngũ kỹ sư giám sát trực tiếp tại công trình, đảm bảo chất lượng từng giai đoạn.',
		],
		[
			'question' => 'Bảng dự toán có phát sinh chi phí không?',
			'answer'   => 'XANH cam kết 100% không phát sinh chi phí so với dự toán ban đầu. Mọi hạng mục đều được liệt kê minh bạch, chi tiết đến từng vật liệu. Nếu có bất kỳ điều chỉnh nào, chúng tôi sẽ thông báo và được sự đồng ý của bạn trước khi thực hiện.',
		],
		[
			'question' => 'XANH có nhận thi công nếu tôi đã có bản vẽ?',
			'answer'   => 'Có. Chúng tôi sẽ thẩm định bản vẽ hiện có, đánh giá tính khả thi về kết cấu và vật liệu, sau đó đưa ra dự toán chi phí thi công chi tiết. Nếu cần tối ưu thêm, đội ngũ KTS của XANH sẵn sàng tư vấn và điều chỉnh.',
		],
	];
}
?>

<section class="contact-faq" id="contact-faq">
	<div class="site-container">
		<div class="contact-faq__header section-header section-header--center">
			<span class="section-eyebrow anim-fade-up"><?php echo esc_html( $eyebrow ); ?></span>
			<h2 class="section-title anim-fade-up mt-2"><?php echo wp_kses_post( $title ); ?></h2>
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
