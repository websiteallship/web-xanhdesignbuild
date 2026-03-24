<?php
/**
 * Template Part: Section SV Features (S3).
 *
 * 3×2 feature card grid on primary background.
 * Uses .anim-fade-up from components.css (revealed by main.js XanhBase.initScrollReveal).
 *
 * ACF fields: sv_features_eyebrow, sv_features_title,
 *             sv_features_subtitle, sv_features_bg_image,
 *             sv_features (repeater: icon, title, desc).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow  = get_field( 'sv_features_eyebrow' ) ?: 'Nền Tảng Giải Pháp';
$title    = get_field( 'sv_features_title' ) ?: 'Kiến Tạo Không Gian Hoàn Mỹ<br class="hidden md:block" /> — Mọi Đường Nét Đều Có Lý Do';
$subtitle = get_field( 'sv_features_subtitle' ) ?: 'Dịch vụ <strong>thiết kế kiến trúc và nội thất</strong> của XANH không định hình bạn vào khuôn mẫu có sẵn. Chúng tôi thiết kế dựa trên phong cách sống, đảm bảo sự tinh tế và tính ứng dụng trường tồn.';
$bg_image = xanh_get_image( 'sv_features_bg_image' );

$features = get_field( 'sv_features' );
if ( ! $features ) {
	$features = [
		[ 'icon' => 'compass',      'title' => 'Tư Vấn Phong Thuỷ<br>Chuyên Sâu',   'desc' => 'Đảm bảo sinh khí hài hòa và thu hút tài lộc, tôn trọng văn hóa Á Đông nhưng vẫn giữ được nét kiến trúc đương đại tinh tế.' ],
		[ 'icon' => 'layers',        'title' => 'Bản Vẽ 3D<br>Chân Thực',             'desc' => 'Cam kết 98% sát hiện trạng thi công. Trải nghiệm không gian sống tương lai sống động đến từng chi tiết vật liệu cao cấp.' ],
		[ 'icon' => 'shield-check',  'title' => 'Hồ Sơ Kỹ Thuật<br>Chuẩn Xác',       'desc' => 'Triển khai bản vẽ MEP, kết cấu chi tiết — nền tảng vững chắc để quá trình thi công minh bạch, triệt để không phát sinh chi phí.' ],
		[ 'icon' => 'pen-tool',      'title' => 'Cá Nhân Hóa<br>Dấu Ấn',             'desc' => 'Mỗi thiết kế là một tổ ấm độc bản — tôn vinh phong cách sống và cá tính riêng biệt của từng thành viên trong gia đình.' ],
		[ 'icon' => 'refresh-ccw',   'title' => 'Thiết Kế Đến<br>Khi Hài Lòng',      'desc' => 'Lắng nghe tận tâm và điều chỉnh bản vẽ. Sự hài lòng và an tâm của bạn là thước đo duy nhất cho thành công của chúng tôi.' ],
		[ 'icon' => 'award',         'title' => 'Đội Ngũ KTS<br>Tận Tâm',             'desc' => 'Hơn 47 công trình đã bàn giao. Mỗi kiến trúc sư tại XANH đều làm việc với tinh thần trách nhiệm và khát khao kiến tạo di sản.' ],
	];
}

// Fallback bg image URL
$bg_url = '';
if ( $bg_image ) {
	$bg_url = $bg_image['url'];
} else {
	$bg_url = site_url( '/wp-content/uploads/2026/03/project-after-1.png' );
}
?>

<section id="service-features" class="features-section relative overflow-hidden">

	<!-- Background image overlay (20% opacity) -->
	<div class="absolute inset-0">
		<?php if ( $bg_image ) : ?>
			<?php
			echo wp_get_attachment_image( $bg_image['ID'], 'large', false, [
				'class'       => 'w-full h-full object-cover opacity-20',
				'alt'         => '',
				'aria-hidden' => 'true',
				'loading'     => 'lazy',
			] );
			?>
		<?php else : ?>
			<img src="<?php echo esc_url( $bg_url ); ?>" alt="" class="w-full h-full object-cover opacity-20" aria-hidden="true" loading="lazy" />
		<?php endif; ?>
	</div>
	<!-- Primary color overlay on top of image -->
	<div class="absolute inset-0 bg-primary/80"></div>

	<div class="site-container relative z-10">

		<div class="section-header section-header--center anim-fade-up max-w-3xl mx-auto mb-12 md:mb-16">
			<span class="section-eyebrow text-white/40 block mb-4">
				<?php echo esc_html( $eyebrow ); ?>
			</span>
			<h2 class="section-title section-title--light text-white mb-6">
				<?php echo wp_kses_post( $title ); ?>
			</h2>
			<p class="section-subtitle text-white/60 mx-auto w-full max-w-none">
				<?php echo wp_kses_post( $subtitle ); ?>
			</p>
		</div>

		<!-- 3×2 Features Grid -->
		<?php if ( $features ) : ?>
			<div class="features-grid">
				<?php foreach ( $features as $i => $feature ) :
					$icon  = $feature['icon'] ?? 'star';
					$ftitle = $feature['title'] ?? '';
					$desc  = $feature['desc'] ?? '';
					$num   = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
					$delay = ( $i % 3 ) * 80;
				?>
					<div class="feature-card anim-fade-up group" data-index="<?php echo esc_attr( $i + 1 ); ?>"<?php echo $delay ? ' style="transition-delay: ' . esc_attr( $delay ) . 'ms;"' : ''; ?>>
						<div class="feature-card__inner">
							<span class="feature-card__number" aria-hidden="true"><?php echo esc_html( $num ); ?></span>
							<div class="feature-card__icon-wrap">
								<i data-lucide="<?php echo esc_attr( $icon ); ?>" class="feature-card__icon"></i>
							</div>
							<h3 class="feature-card__title"><?php echo wp_kses_post( $ftitle ); ?></h3>
							<p class="feature-card__desc">
								<?php echo wp_kses_post( $desc ); ?>
							</p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
