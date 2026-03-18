<?php
/**
 * Template Part: Section — Process Steps (Quy Trình 6 Bước).
 *
 * Horizontal accordion with 6 panels. Each panel expands on click.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uploads = content_url( '/uploads/2026/03/' );

$steps = [
	[
		'num'   => '01',
		'title' => 'Tư Vấn',
		'desc'  => 'Gặp gỡ và lắng nghe — để hiểu mong ước, phong cách sống và ngân sách của bạn. Mỗi tổ ấm bắt đầu từ một cuộc trò chuyện.',
		'img'   => $uploads . 'process-01.png',
	],
	[
		'num'   => '02',
		'title' => 'Thiết Kế',
		'desc'  => 'Biến ý tưởng thành bản vẽ — kiến trúc sư tận tâm phác hoạ không gian sống phù hợp với bản sắc riêng của gia đình bạn.',
		'img'   => $uploads . 'process-02.png',
	],
	[
		'num'   => '03',
		'title' => 'Ký Kết',
		'desc'  => 'Minh bạch từng hạng mục — hợp đồng rõ ràng, chi phí cố định, không phát sinh ngoài cam kết.',
		'img'   => $uploads . 'process-03.png',
	],
	[
		'num'   => '04',
		'title' => 'Thi Công',
		'desc'  => 'Giám sát kỹ thuật 3 lớp, đúng tiến độ cam kết — để bạn an tâm tận hưởng hành trình kiến tạo tổ ấm.',
		'img'   => $uploads . 'process-04.png',
	],
	[
		'num'   => '05',
		'title' => 'Bàn Giao',
		'desc'  => 'Nghiệm thu tỉ mỉ từng chi tiết — chìa khoá trao tay khi mọi thứ hoàn hảo đúng như bản vẽ 3D.',
		'img'   => $uploads . 'process-05.png',
	],
	[
		'num'   => '06',
		'title' => 'Bảo Trì',
		'desc'  => 'Đồng hành sau bàn giao — bảo trì định kỳ, hỗ trợ trọn đời, để ngôi nhà luôn vẹn nguyên giá trị.',
		'img'   => $uploads . 'process-06.png',
	],
];
?>

<section id="process" class="process-section bg-white">
	<div class="site-container">

		<!-- Section Header -->
		<div class="section-header section-header--center">
			<p class="anim-fade-up section-eyebrow">Quy Trình</p>
			<h2 class="anim-fade-up section-title text-primary">
				Chúng Tôi Đồng Hành Cùng Bạn<br>Đến Từng Viên Gạch Cuối Cùng
			</h2>
			<p class="anim-fade-up section-subtitle">
				Hành trình kiến tạo tổ ấm bắt đầu từ một cuộc trò chuyện — mỗi bước đi đều được đồng hành tận tâm.
			</p>
		</div>

		<!-- Horizontal Accordion -->
		<div class="process-accordion anim-fade-up">
			<?php foreach ( $steps as $idx => $step ) : ?>
				<div class="process-panel<?php echo 0 === $idx ? ' is-active' : ''; ?>" data-step="<?php echo esc_attr( $idx ); ?>">
					<div class="process-panel__expanded">
						<img src="<?php echo esc_url( $step['img'] ); ?>"
							alt="Bước <?php echo esc_attr( $step['num'] ); ?> — <?php echo esc_attr( $step['title'] ); ?>"
							class="process-panel__bg" />
						<div class="process-panel__overlay"></div>
						<div class="process-panel__content">
							<span class="process-panel__num"><?php echo esc_html( $step['num'] ); ?></span>
							<h3 class="process-panel__title"><?php echo esc_html( $step['title'] ); ?></h3>
							<p class="process-panel__desc"><?php echo esc_html( $step['desc'] ); ?></p>
							<a href="#" class="process-panel__cta">
								Khám Phá Quy Trình
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M5 12h14" />
									<path d="m12 5 7 7-7 7" />
								</svg>
							</a>
						</div>
					</div>
					<div class="process-panel__collapsed">
						<svg class="process-panel__collapsed-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
							<path d="M12 5v14" />
							<path d="M5 12h14" />
						</svg>
						<span class="process-panel__collapsed-num"><?php echo esc_html( $step['num'] ); ?></span>
						<span class="process-panel__collapsed-title"><?php echo esc_html( $step['title'] ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
