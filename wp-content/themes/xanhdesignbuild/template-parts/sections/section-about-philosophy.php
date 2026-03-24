<?php
/**
 * Template Part: Section 5 — Philosophy "4 Xanh" (Triết Lý).
 *
 * 2×2 grid of philosophy cards with hover-reveal descriptions.
 * ACF repeater: about_philo_items (image, icon, title, description).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$philo_eyebrow = get_field( 'about_philo_eyebrow' ) ?: 'Triết lý cốt lõi';
$philo_title   = get_field( 'about_philo_title' ) ?: 'Không Gian Kiến Tạo Cho Sự Bình Yên';
$philo_desc    = get_field( 'about_philo_subtitle' ) ?: 'Không mang ý nghĩa phô trương, "Xanh" trong ngôn ngữ thiết kế của chúng tôi là sự vừa vặn hoàn hảo giữa con người, không gian và môi trường. Kiến tạo một chốn trở về thực sự thuộc về bạn.';

// Philosophy cards — ACF repeater or fallback.
$philo_items = get_field( 'about_philo_items' );
$defaults = [
	[ 'icon' => 'leaf', 'title' => 'Không Gian Sống<br>Trong Lành', 'desc' => 'Nơi ánh sáng tự nhiên và luồng khí tươi ôm trọn mỗi khoảnh khắc, kiến tạo một tổ ấm thực sự tốt cho sức khỏe và tinh thần.', 'image' => null ],
	[ 'icon' => 'box',  'title' => 'Vật Liệu Chuẩn Mực<br>Bền Vững', 'desc' => 'Khắt khe trong từng lựa chọn. Chúng tôi ưu tiên những giải pháp vật liệu mang tính di sản, an toàn tuyệt đối và tôn trọng tự nhiên.', 'image' => null ],
	[ 'icon' => 'zap',  'title' => 'Giải Pháp Năng<br>Lượng Tối Ưu', 'desc' => 'Tối giản chi phí vận hành thông qua các ứng dụng thiết kế thông minh, trả lại cho bạn đặc quyền tận hưởng cuộc sống không âu lo.', 'image' => null ],
	[ 'icon' => 'sun',  'title' => 'Cảnh Quan Hòa Hợp<br>Thiên Nhiên', 'desc' => 'Ranh giới giữa trong và ngoài được xóa nhòa. Mỗi góc nhìn đều là một bức tranh sống động, kết nối con người với thiên nhiên nguyên bản.', 'image' => null ],
];

if ( empty( $philo_items ) ) {
	$philo_items = $defaults;
}
?>

<section id="about-philosophy" class="bg-light relative w-full overflow-hidden py-12 md:py-16 lg:py-20">
	<div class="site-container">

		<!-- Section Header -->
		<div class="section-header section-header--center anim-fade-up max-w-3xl mx-auto mb-12 md:mb-16">
			<span class="section-eyebrow text-primary/50 block mb-4">
				<?php echo esc_html( $philo_eyebrow ); ?>
			</span>
			<h2 class="section-title text-primary mb-6">
				<?php echo esc_html( $philo_title ); ?>
			</h2>
			<p class="section-subtitle text-dark/80 mx-auto w-full max-w-none">
				<?php echo esc_html( $philo_desc ); ?>
			</p>
		</div>

		<!-- 2×2 Grid -->
		<div class="philo-grid grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
			<?php foreach ( $philo_items as $k => $card ) :
				$default  = $defaults[ $k % 4 ] ?? $defaults[0];
				$icon     = ! empty( $card['icon'] ) ? $card['icon'] : $default['icon'];
				$title    = ! empty( $card['title'] ) ? $card['title'] : $default['title'];
				$desc     = ! empty( $card['desc'] ) ? $card['desc'] : $default['desc'];
				
				$img      = $card['image'] ?? null;
				$img_url  = '';
				if ( is_array( $img ) && ! empty( $img['url'] ) ) {
					$img_url = $img['url'];
				} elseif ( is_numeric( $img ) ) {
					$img_url = wp_get_attachment_url( $img );
				}
				if ( ! $img_url ) {
					$img_url = content_url( 'uploads/2026/03/philo-' . ( $k + 1 ) . '.png' );
				}
				
				$delay_class = ( $k % 2 === 1 ) ? ' philo-card--delay' : '';
			?>
				<div
					class="philo-card anim-fade-up group relative overflow-hidden aspect-[4/5] sm:aspect-[4/3] md:aspect-square xl:aspect-[4/3] cursor-pointer<?php echo $delay_class; ?>">
					<?php if ( is_numeric( $img ) && $img ) :
						echo wp_get_attachment_image( (int) $img, 'xanh-card', false, [
							'class'   => 'philo-card__img absolute inset-0 w-full h-full object-cover',
							'loading' => 'lazy',
						] );
					elseif ( is_array( $img ) && ! empty( $img['ID'] ) ) :
						echo wp_get_attachment_image( $img['ID'], 'xanh-card', false, [
							'class'   => 'philo-card__img absolute inset-0 w-full h-full object-cover',
							'loading' => 'lazy',
						] );
					else : ?>
						<img src="<?php echo esc_url( $img_url ); ?>"
							alt="<?php echo esc_attr( wp_strip_all_tags( $title ) ); ?>"
							class="philo-card__img absolute inset-0 w-full h-full object-cover"
							width="640" height="480" loading="lazy">
					<?php endif; ?>
					<div class="philo-card__overlay absolute inset-x-0 bottom-0 pointer-events-none"></div>
					<div class="philo-card__content absolute inset-x-0 bottom-0 p-6 md:p-8 flex flex-col justify-end">
						<div class="philo-card__icon-wrap">
							<i data-lucide="<?php echo esc_attr( $icon ); ?>" class="philo-card__icon"></i>
						</div>
						<h3 class="philo-card__title"><?php echo wp_kses_post( $title ); ?></h3>
						<p class="philo-card__desc">
							<?php echo esc_html( $desc ); ?>
						</p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
