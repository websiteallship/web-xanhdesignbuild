<?php
/**
 * Template Part: Section 6 — Team Members (Đội Ngũ).
 *
 * 4-column grid of team member cards with hover overlay quotes.
 * ACF repeater: about_team_members (image, name, role, quote).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$team_eyebrow = get_field( 'about_team_eyebrow' ) ?: 'Con Người Kiến Tạo';
$team_title   = get_field( 'about_team_title' ) ?: 'Đội Ngũ Chuyên Gia';
$team_desc    = get_field( 'about_team_subtitle' ) ?: 'Những kiến trúc sư và kỹ sư đam mê sự hoàn mỹ. Chúng tôi không chỉ xây dựng không gian, chúng tôi kiến tạo những giá trị bền vững vượt thời gian.';

$team_members = get_field( 'about_team_members' );
if ( empty( $team_members ) ) {
	$team_members = [
		[ 'name' => 'Minh Tuấn',  'role' => 'Giám đốc điều hành (CEO)',  'quote' => '"Kiến tạo không gian, gieo mầm bình yên cho mỗi gia chủ."',                       'image' => null, 'role_highlight' => true ],
		[ 'name' => 'Hoàng Yến',  'role' => 'Kiến trúc sư trưởng',         'quote' => '"Bản vẽ hoàn hảo nhất là bản vẽ truyền tải được nhịp sống thực tế."',                'image' => null, 'role_highlight' => false ],
		[ 'name' => 'Gia Bảo',    'role' => 'Quản lý dự án',                'quote' => '"Khởi nguồn từ khao khát mang đến sự trọn vẹn trong thiết kế tối giản."',          'image' => null, 'role_highlight' => false ],
		[ 'name' => 'Quốc Huy',   'role' => 'Kỹ sư trưởng',                'quote' => '"Cam kết thi công chuẩn xác, an toàn và bền vững tuyệt đối."',                      'image' => null, 'role_highlight' => false ],
	];
}
?>

<section id="about-team"
	class="bg-white relative w-full overflow-hidden py-12 md:py-16 lg:py-20 border-t border-dark/5">
	<div class="site-container">

		<!-- Section Header -->
		<div class="section-header section-header--center anim-fade-up max-w-3xl mx-auto mb-12 md:mb-16">
			<span class="section-eyebrow text-primary/50 block mb-4">
				<?php echo esc_html( $team_eyebrow ); ?>
			</span>
			<h2 class="section-title text-primary mb-6">
				<?php echo esc_html( $team_title ); ?>
			</h2>
			<p class="section-subtitle text-dark/80 mx-auto w-full max-w-none">
				<?php echo esc_html( $team_desc ); ?>
			</p>
		</div>

		<!-- 4-Column Team Grid -->
		<div class="team-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
			<?php foreach ( $team_members as $m => $member ) :
				$name           = $member['name'] ?? '';
				$role           = $member['role'] ?? '';
				$quote          = $member['quote'] ?? '';
				$role_highlight = ! empty( $member['role_highlight'] );
				$img            = $member['image'] ?? null;
				$img_id         = null;
				$team_fallbacks = [
					content_url( 'uploads/2026/03/tuan-ceo.png' ),
					content_url( 'uploads/2026/03/yen-architect.png' ),
					content_url( 'uploads/2026/03/bao-pm.png' ),
					content_url( 'uploads/2026/03/huy-engineer.png' ),
				];
				$img_fallback   = $team_fallbacks[ $m ] ?? content_url( 'uploads/2026/03/tuan-ceo.png' );
				if ( is_array( $img ) && ! empty( $img['ID'] ) ) {
					$img_id = $img['ID'];
				} elseif ( is_numeric( $img ) ) {
					$img_id = (int) $img;
				}
			?>
				<div class="team-card group">
					<div class="team-card__img-wrap aspect-[4/5] overflow-hidden relative mb-5 bg-light">
						<?php if ( $img_id ) :
							echo wp_get_attachment_image( $img_id, 'medium_large', false, [
								'class'   => 'team-card__img w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105',
								'loading' => 'lazy',
							] );
						else : ?>
							<img src="<?php echo esc_url( $img_fallback ); ?>" alt="<?php echo esc_attr( $name ); ?>"
								class="team-card__img w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
								width="400" height="500" loading="lazy">
						<?php endif; ?>

						<?php if ( $quote ) : ?>
							<!-- Hover Overlay -->
							<div
								class="team-card__overlay absolute inset-x-0 bottom-0 pt-32 pb-6 px-6 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent opacity-0 transition-opacity duration-300 ease-out flex flex-col justify-end group-hover:opacity-100 z-10">
								<p
									class="text-white/90 text-sm leading-relaxed mb-4 italic font-light opacity-0 translate-y-4 transition-all duration-500 ease-out group-hover:opacity-100 group-hover:translate-y-0 delay-100">
									<?php echo esc_html( $quote ); ?>
								</p>
							</div>
						<?php endif; ?>
					</div>
					<h3 class="font-heading text-xl font-bold text-dark mb-1"><?php echo esc_html( $name ); ?></h3>
					<p class="<?php echo $role_highlight ? 'text-accent' : 'text-dark/60'; ?> text-sm font-medium"><?php echo esc_html( $role ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
