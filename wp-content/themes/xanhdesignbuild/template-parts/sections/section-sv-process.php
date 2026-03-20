<?php
/**
 * Template Part: Section SV Process (S4).
 *
 * Sticky left panel + vertical timeline right.
 * Uses .anim-fade-up and .section-eyebrow from components.css.
 *
 * ACF fields: sv_process_eyebrow, sv_process_title, sv_process_desc,
 *             sv_process_stat_number, sv_process_stat_label,
 *             sv_process_steps (repeater: title, desc, icon_1, icon_2, image).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow    = get_field( 'sv_process_eyebrow' ) ?: 'Xây Dựng Niềm Tin';
$title      = get_field( 'sv_process_title' ) ?: 'Quy Trình<br>Bài Bản —<br>Minh Bạch<br>Tuyệt Đối';
$desc       = get_field( 'sv_process_desc' ) ?: 'Hành trình <strong>thiết kế kiến trúc và nội thất</strong> tại XANH luôn đề cao sự minh bạch. Bạn luôn nắm quyền kiểm soát tại mọi giai đoạn kiến tạo di sản.';
$stat_num   = get_field( 'sv_process_stat_number' ) ?: '5';
$stat_label = get_field( 'sv_process_stat_label' ) ?: 'Cột Mốc<br>Rõ Ràng';

$steps = get_field( 'sv_process_steps' );
if ( ! $steps ) {
	$steps = [
		[ 'title' => 'Lắng Nghe & Thấu Hiểu', 'desc' => 'Khảo sát hiện trạng, tìm hiểu sâu về nếp sinh hoạt và ngân sách. Ghi nhận mọi mong muốn để làm nền tảng vững chắc cho bản vẽ kiến trúc sau này.', 'icon_1' => 'headphones', 'icon_2' => 'map-pin' ],
		[ 'title' => 'Quy Hoạch Không Gian', 'desc' => 'Đề xuất phương án mặt bằng và phong cách thiết kế sơ bộ. Tối ưu hóa triệt để công năng sử dụng, luồng sinh khí và nguồn sáng tự nhiên.', 'icon_1' => 'layout', 'icon_2' => 'compass' ],
		[ 'title' => 'Phát Triển Thiết Kế', 'desc' => 'Thể hiện ý tưởng qua bản vẽ 3D sắc nét. Tinh chỉnh từng chi tiết vật liệu, góc sáng và màu sắc cho đến khi gia chủ thực sự ưng ý và đồng điệu.', 'icon_1' => 'pen-tool', 'icon_2' => 'layers' ],
		[ 'title' => 'Hoàn Thiện Hồ Sơ', 'desc' => 'Ban hành hồ sơ kỹ thuật thi công chuẩn xác, bản vẽ MEP và shop drawing. Đi kèm bảng dự toán khối lượng chi tiết, cam kết an toàn tài chính.', 'icon_1' => 'file-text', 'icon_2' => 'shield-check' ],
		[ 'title' => 'Bàn Giao & Đồng Hành', 'desc' => 'Bàn giao hồ sơ thiết kế hoàn chỉnh. XANH tiếp tục giám sát tác giả trong quá trình thi công, đảm bảo mọi không gian sống đều hoàn mỹ đúng như kỳ vọng.', 'icon_1' => 'package-check', 'icon_2' => 'handshake' ],
	];
}

$total_steps = count( $steps );

// Fallback process images
$process_fallback_imgs = [
	site_url( '/wp-content/uploads/2026/03/process-01.png' ),
	site_url( '/wp-content/uploads/2026/03/process-02.png' ),
	site_url( '/wp-content/uploads/2026/03/process-03.png' ),
	site_url( '/wp-content/uploads/2026/03/process-04.png' ),
	site_url( '/wp-content/uploads/2026/03/process-05.png' ),
];
?>

<section id="service-process" class="process-section">
	<div class="site-container">
		<div class="process-layout">

			<!-- LEFT: Sticky Title Panel -->
			<div class="process-sticky">
				<div class="process-sticky__inner">
					<span class="section-eyebrow text-primary/50 block mb-4 anim-fade-up">
						<?php echo esc_html( $eyebrow ); ?>
					</span>
					<h2 class="process-sticky__title anim-fade-up">
						<?php echo wp_kses_post( $title ); ?>
					</h2>
					<p class="process-sticky__desc anim-fade-up">
						<?php echo wp_kses_post( $desc ); ?>
					</p>
					<div class="process-sticky__accent anim-fade-up">
						<span class="process-sticky__stat"><?php echo esc_html( $stat_num ); ?></span>
						<span class="process-sticky__stat-label"><?php echo wp_kses_post( $stat_label ); ?></span>
					</div>
				</div>
			</div>

			<!-- RIGHT: Vertical Timeline -->
			<div class="process-timeline">
				<?php foreach ( $steps as $i => $step ) :
					$num       = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
					$is_active = ( $i === 2 ); // Default: step 3 is active
					$is_last   = ( $i === $total_steps - 1 );
					$step_img  = isset( $step['image'] ) ? $step['image'] : null;
					$fb_img    = $process_fallback_imgs[ $i ] ?? '';
				?>
					<div class="process-step<?php echo $is_active ? ' is-active' : ''; ?>" data-step="<?php echo esc_attr( $i + 1 ); ?>">
						<div class="process-step__dot-col">
							<span class="process-step__number"><?php echo esc_html( $num ); ?></span>
							<div class="process-step__dot"></div>
							<?php if ( ! $is_last ) : ?>
								<div class="process-step__line"></div>
							<?php endif; ?>
						</div>
						<div class="process-step__content">
							<div class="process-step__text">
								<h3 class="process-step__title"><?php echo esc_html( $step['title'] ?? '' ); ?></h3>
								<p class="process-step__desc"><?php echo esc_html( $step['desc'] ?? '' ); ?></p>
								<div class="process-step__icons">
									<?php if ( ! empty( $step['icon_1'] ) ) : ?>
										<i data-lucide="<?php echo esc_attr( $step['icon_1'] ); ?>" class="process-step__icon"></i>
									<?php endif; ?>
									<?php if ( ! empty( $step['icon_2'] ) ) : ?>
										<i data-lucide="<?php echo esc_attr( $step['icon_2'] ); ?>" class="process-step__icon"></i>
									<?php endif; ?>
								</div>
							</div>
							<div class="process-step__thumb">
								<?php if ( $step_img && isset( $step_img['ID'] ) ) : ?>
									<?php
									echo wp_get_attachment_image( $step_img['ID'], 'medium', false, [
										'loading' => 'lazy',
										'width'   => 280,
										'height'  => 180,
									] );
									?>
								<?php else : ?>
									<img src="<?php echo esc_url( $fb_img ); ?>"
										alt="<?php echo esc_attr( $step['title'] ?? '' ); ?>"
										width="280" height="180" loading="lazy" />
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div><!-- /.process-timeline -->

		</div><!-- /.process-layout -->
	</div>
</section>
