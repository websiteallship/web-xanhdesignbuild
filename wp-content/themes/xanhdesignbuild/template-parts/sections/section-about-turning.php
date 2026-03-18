<?php
/**
 * Template Part: Section 3 — Turning Point (Bước Ngoặt).
 *
 * SVG circular infographic showing the closed-loop value chain.
 * ACF fields: about_turn_eyebrow, about_turn_title, about_turn_subtitle, about_turn_bg_image.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$turn_eyebrow = get_field( 'about_turn_eyebrow' ) ?: 'Bước Ngoặt';
$turn_title   = get_field( 'about_turn_title' ) ?: 'Lời Giải Cho Sự "Đứt Gãy"<br class="hidden md:block" /> Của Thị Trường';
$turn_subtitle = get_field( 'about_turn_subtitle' ) ?: 'Chúng tôi nhận ra rằng, rễ sâu của rủi ro không nằm ở từng khâu riêng lẻ — mà ở sự đứt gãy giữa các khâu đó. Từ những trăn trở thực tế, <strong class="text-white font-semibold">Xanh — Design &amp; Build</strong> đã chính thức khởi sinh với sứ mệnh khép kín chuỗi giá trị.';
$turn_bg      = xanh_get_image( 'about_turn_bg_image' );
$turn_bg_url  = $turn_bg['url'] ?? content_url( 'uploads/2026/03/section3-turning-bg.png' );

// 5 nodes data.
$nodes = [
	[ 'label1' => 'THIẾT', 'label2' => 'KẾ',    'cx' => 200, 'cy' => 50,  'desc' => 'Tỉ mỉ từng bản vẽ — đảm bảo 98% sát với thực tế thi công' ],
	[ 'label1' => 'DỰ',   'label2' => 'TOÁN',   'cx' => 352, 'cy' => 148, 'desc' => 'Minh bạch từng hạng mục — không phát sinh, không ngoại lệ' ],
	[ 'label1' => 'VẬT',  'label2' => 'LIỆU',   'cx' => 294, 'cy' => 340, 'desc' => 'Chuẩn mực bền vững — nguồn gốc rõ ràng, chất lượng kiểm chứng' ],
	[ 'label1' => 'THI',  'label2' => 'CÔNG',    'cx' => 106, 'cy' => 340, 'desc' => 'Trọn vẹn cam kết — giám sát chặt chẽ, bàn giao đúng tiến độ' ],
	[ 'label1' => 'BẢO',  'label2' => 'HÀNH',    'cx' => 48,  'cy' => 148, 'desc' => 'Đồng hành trường tồn — hỗ trợ tận tâm sau bàn giao' ],
];

// Arrow rotation angles.
$arrow_angles = [ 36, 108, 180, 252, 324 ];
?>

<section id="about-turning" class="bg-primary relative w-full overflow-hidden py-12 md:py-16 lg:py-20">

	<!-- Background image overlay -->
	<div class="absolute inset-0">
		<img src="<?php echo esc_url( $turn_bg_url ); ?>" alt="" class="w-full h-full object-cover opacity-20" />
	</div>
	<div class="absolute inset-0 bg-primary/80"></div>

	<div class="site-container relative z-10">

		<!-- Section Header -->
		<div class="section-header section-header--center anim-fade-up max-w-3xl mx-auto mb-16 md:mb-20 lg:mb-24">
			<span class="turn-el section-eyebrow text-white/40 block mb-4">
				<?php echo esc_html( $turn_eyebrow ); ?>
			</span>
			<h2 class="turn-el section-title text-white mb-6 lg:mb-8">
				<?php echo wp_kses_post( $turn_title ); ?>
			</h2>
			<p class="turn-el section-subtitle text-white/70 mx-auto w-full max-w-none">
				<?php echo wp_kses_post( $turn_subtitle ); ?>
			</p>
		</div>

		<!-- Circular Infographic -->
		<div class="flex justify-center w-full">
			<div class="turn-el w-full flex justify-center">
				<div
					class="relative w-[92vw] h-[92vw] sm:w-[400px] sm:h-[400px] md:w-[480px] md:h-[480px] lg:w-[560px] lg:h-[560px] xl:w-[600px] xl:h-[600px]">

					<!-- SVG Circle -->
					<svg viewBox="0 0 400 420" class="w-full h-full" id="turning-circle-svg">
						<!-- Background circle -->
						<circle cx="200" cy="210" r="160" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="2" />

						<!-- Animated progress circle -->
						<circle cx="200" cy="210" r="160" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2.5"
							stroke-dasharray="1005.3" stroke-dashoffset="1005.3" stroke-linecap="round"
							transform="rotate(-90 200 210)" id="turning-progress-circle" />

						<!-- Hidden center text (replaced by HTML overlay) -->
						<text x="200" y="185" text-anchor="middle" fill="white" font-family="Inter" font-weight="700"
							font-size="16" letter-spacing="0.1em" opacity="0">CHUỖI GIÁ TRỊ</text>
						<text x="200" y="210" text-anchor="middle" fill="white" font-family="Inter" font-weight="700"
							font-size="16" letter-spacing="0.1em" opacity="0">KHÉP KÍN</text>
						<text x="200" y="235" text-anchor="middle" fill="rgba(255,138,0,0.9)" font-family="Inter"
							font-weight="600" font-size="11" letter-spacing="0.15em" opacity="0">XANH — D&B</text>

						<!-- Clockwise Flow Arrows -->
						<g id="arrows-orbit" class="arrows-orbit">
							<?php foreach ( $arrow_angles as $angle ) : ?>
								<g class="turn-arrow" opacity="0">
									<g transform="rotate(<?php echo esc_attr( $angle ); ?> 200 210)">
										<rect x="180" y="35" width="30" height="30" fill="transparent" />
										<path class="flow-arrow-path" d="M 192 44 L 202 50 L 192 56" fill="none" stroke-width="2.5"
											stroke-linecap="round" stroke-linejoin="round" />
									</g>
								</g>
							<?php endforeach; ?>
						</g>

						<!-- 5 Nodes positioned around the circle -->
						<?php foreach ( $nodes as $i => $node ) : ?>
							<g class="turn-node" data-index="<?php echo esc_attr( $i ); ?>" data-desc="<?php echo esc_attr( $node['desc'] ); ?>"
								style="cursor:pointer">
								<circle cx="<?php echo esc_attr( $node['cx'] ); ?>" cy="<?php echo esc_attr( $node['cy'] ); ?>" r="44" fill="transparent" stroke="none" />
								<circle cx="<?php echo esc_attr( $node['cx'] ); ?>" cy="<?php echo esc_attr( $node['cy'] ); ?>" r="38" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.25)"
									stroke-width="1.5" class="node-visible" />
								<text x="<?php echo esc_attr( $node['cx'] ); ?>" y="<?php echo esc_attr( $node['cy'] - 6 ); ?>" text-anchor="middle" fill="white" font-family="Inter" font-weight="700"
									font-size="14" letter-spacing="0.06em"><?php echo esc_html( $node['label1'] ); ?></text>
								<text x="<?php echo esc_attr( $node['cx'] ); ?>" y="<?php echo esc_attr( $node['cy'] + 12 ); ?>" text-anchor="middle" fill="white" font-family="Inter" font-weight="700"
									font-size="14" letter-spacing="0.06em"><?php echo esc_html( $node['label2'] ); ?></text>
							</g>
						<?php endforeach; ?>
					</svg>

					<!-- Center overlay -->
					<div class="turn-center-overlay" id="turn-center-overlay">
						<div class="turn-center-default" id="turn-center-default">
							<span class="turn-center-label">CHUỖI GIÁ TRỊ</span>
							<span class="turn-center-label">KHÉP KÍN</span>
							<span class="turn-center-brand">XANH — D&amp;B</span>
						</div>
						<div class="turn-center-detail" id="turn-center-detail">
							<span class="turn-center-detail__title" id="turn-detail-title"></span>
							<span class="turn-center-detail__desc" id="turn-detail-desc"></span>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</section>
