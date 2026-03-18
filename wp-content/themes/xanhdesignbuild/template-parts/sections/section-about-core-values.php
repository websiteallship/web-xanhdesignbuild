<?php
/**
 * Template Part: Section 5.5 — Core Values (Bản Sắc Cốt Lõi).
 *
 * 2×2 bento grid of core value cards on dark green background.
 * ACF repeater: about_cv_items (icon, number, title, description).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cv_eyebrow = get_field( 'about_cv_eyebrow' ) ?: 'Cam Kết Của Chúng Tôi';
$cv_title   = get_field( 'about_cv_title' ) ?: 'Bản Sắc Cốt Lõi — Lời Cam Kết Bền Vững';
$cv_desc    = get_field( 'about_cv_subtitle' ) ?: 'Đây không chỉ là giá trị — đây là lời tuyên thệ. Mỗi công trình Xanh bàn giao đều mang trong mình bốn nền tảng không thể thương lượng.';

$cv_items = get_field( 'about_cv_items' );
if ( empty( $cv_items ) ) {
	$cv_items = [
		[ 'number' => '01', 'icon' => 'target',    'title' => 'Hiệu Quả<br>Thực Tế',     'desc' => 'Chúng tôi không làm cho đẹp chỉ trên giấy. Mỗi bản vẽ đều được kiểm chứng — chỉ thực hiện những gì hoàn toàn có thể thi công được.' ],
		[ 'number' => '02', 'icon' => 'eye',       'title' => 'Minh Bạch<br>Tuyệt Đối',   'desc' => 'Mọi thứ đều rõ ràng — từ chi phí, vật liệu đến toàn bộ quy trình làm việc. Mỗi đồng bạn đầu tư đều được tôn trọng, không ngoại lệ.' ],
		[ 'number' => '03', 'icon' => 'trees',     'title' => 'Bền Vững<br>Trường Tồn',   'desc' => 'Công trình phải đạt chuẩn bền vững — về chất lượng thi công, công năng sử dụng và đảm bảo sự an toàn, bền vững về tài chính cho bạn.' ],
		[ 'number' => '04', 'icon' => 'handshake', 'title' => 'Đồng Hành<br>Trọn Vẹn',   'desc' => 'Không có khái niệm "bán xong là hết". Chúng tôi cam kết đồng hành cùng bạn trong suốt vòng đời của công trình.' ],
	];
}
?>

<section id="about-core-values" class="cv-section relative w-full overflow-hidden py-12 md:py-16 lg:py-20">

	<!-- Subtle background texture -->
	<div class="absolute inset-0 cv-bg-texture pointer-events-none"></div>

	<div class="site-container relative z-10">

		<!-- Section Header -->
		<div class="section-header section-header--center anim-fade-up max-w-3xl mx-auto mb-12 md:mb-16">
			<span class="section-eyebrow text-white/40 block mb-4">
				<?php echo esc_html( $cv_eyebrow ); ?>
			</span>
			<h2 class="section-title section-title--light mb-6">
				<?php echo esc_html( $cv_title ); ?>
			</h2>
			<p class="section-subtitle text-white/60 mx-auto w-full max-w-none">
				<?php echo esc_html( $cv_desc ); ?>
			</p>
		</div>

		<!-- 2×2 Bento Grid -->
		<div class="cv-grid grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">
			<?php foreach ( $cv_items as $cv ) :
				$number = $cv['number'] ?? '01';
				$icon   = $cv['icon'] ?? 'circle';
				$title  = $cv['title'] ?? '';
				$desc   = $cv['desc'] ?? '';
			?>
				<div class="cv-card group" data-index="<?php echo esc_attr( $number ); ?>">
					<div class="cv-card__inner">
						<span class="cv-card__number" aria-hidden="true"><?php echo esc_html( $number ); ?></span>
						<div class="cv-card__icon-wrap">
							<i data-lucide="<?php echo esc_attr( $icon ); ?>" class="cv-card__icon"></i>
						</div>
						<h3 class="cv-card__title"><?php echo wp_kses_post( $title ); ?></h3>
						<p class="cv-card__desc">
							<?php echo esc_html( $desc ); ?>
						</p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
