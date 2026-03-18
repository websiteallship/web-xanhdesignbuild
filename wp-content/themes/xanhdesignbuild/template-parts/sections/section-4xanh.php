<?php
/**
 * Template Part: Section — 4 Xanh Core Values.
 *
 * Dark primary background, editorial grid 2×2 with sticky headline column.
 * ACF fields: values_eyebrow, values_headline, values_tagline, values_items (Repeater).
 * Icons rendered via Lucide JS (data-lucide="icon-name").
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$eyebrow  = get_field( 'values_eyebrow' ) ?: 'Triết Lý Cốt Lõi';
$headline = get_field( 'values_headline' ) ?: 'CON<br class="hidden md:block"> ĐƯỜNG<br class="hidden md:block"> CỦA<br class="hidden md:block"> XANH';
$tagline  = get_field( 'values_tagline' ) ?: 'Xây Bằng Sự Tử Tế & Tầm Nhìn Thế Hệ.';

$default_items = [
	[ 'number' => '01', 'icon' => 'circle-dollar-sign', 'title' => 'Chi Phí<br>Xanh', 'desc' => 'Dự toán minh bạch từ viên gạch đầu tiên. Cam kết không phát sinh ngoài hợp đồng — để gia chủ luôn an tâm về tài chính.' ],
	[ 'number' => '02', 'icon' => 'leaf', 'title' => 'Vật Liệu<br>Xanh', 'desc' => 'Chọn lọc vật liệu chính hãng, thân thiện môi trường — tạo ra những giải pháp kiến trúc bền vững, tiên phong.' ],
	[ 'number' => '03', 'icon' => 'sun', 'title' => 'Vận Hành<br>Xanh', 'desc' => 'Quy trình chuẩn ISO. Giám sát chất lượng 3 lớp, cập nhật tiến độ hàng tuần qua ứng dụng — minh bạch tuyệt đối.' ],
	[ 'number' => '04', 'icon' => 'handshake', 'title' => 'Giá Trị<br>Xanh', 'desc' => 'Đồng hành tận tâm từ tư vấn đến bảo hành trọn đời — mỗi ngôi nhà XANH gia tăng giá trị cho thế hệ tiếp theo.' ],
];
$acf_items = get_field( 'values_items' );
$items     = ( is_array( $acf_items ) && ! empty( $acf_items[0]['desc'] ?? '' ) )
	? $acf_items
	: $default_items;
?>

<section id="core-values" class="relative section bg-primary">
	<div class="core-values-layout site-container">

		<!-- Split: Left Headline + Right Grid -->
		<div class="core-values-split">

			<!-- LEFT: Big Editorial Headline -->
			<div class="core-values-headline">
				<p class="anim-fade-up section-eyebrow section-eyebrow--values mb-6">
					<?php echo esc_html( $eyebrow ); ?>
				</p>
				<h2 class="anim-fade-up section-title section-title--light mb-8">
					<?php echo wp_kses_post( $headline ); ?>
				</h2>
				<p class="anim-fade-up text-base text-beige font-medium italic !leading-[1.6] tracking-wide border-l-2 border-accent pl-4 max-w-[280px]">
					<?php echo esc_html( $tagline ); ?>
				</p>
			</div>

			<!-- RIGHT: 2×2 Editorial Grid -->
			<div class="core-values-grid">
				<?php foreach ( $items as $item ) :
					$number = esc_html( $item['number'] ?? '' );
					$icon   = sanitize_title( $item['icon'] ?? '' );
					$title  = wp_kses_post( $item['title'] ?? '' );
					$desc   = esc_html( $item['desc'] ?? '' );
					?>
					<div class="core-value-item">
						<div class="core-value-item__header">
							<span class="core-value-item__number"><?php echo $number; ?></span>
							<?php if ( $icon ) : ?>
								<i data-lucide="<?php echo esc_attr( $icon ); ?>" class="core-value-item__icon"></i>
							<?php endif; ?>
						</div>
						<h3 class="core-value-item__title"><?php echo $title; ?></h3>
						<p class="core-value-item__desc"><?php echo $desc; ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
