<?php
/**
 * Template Part: Section — Services (Lĩnh Vực Thực Hiện).
 *
 * Centered header + 4-card grid with image, title, description, link.
 * ACF fields: services_eyebrow, services_headline, services_subtitle, services_items (Repeater).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$eyebrow = get_field('services_eyebrow') ?: 'Lĩnh Vực Hoạt Động';
$headline = get_field('services_headline') ?: 'Giải Pháp Khép Kín.<br>Dấu Ấn Độc Bản.';
$subtitle = get_field('services_subtitle') ?: 'Từ ý tưởng kiến trúc sơ khai đến khi trao tay chiếc chìa khóa tổ ấm — Xanh đồng hành cùng bạn trong mọi giai đoạn.';

$default_items = [
	[
		'image' => null,
		'title' => 'Thiết Kế<br>Kiến Trúc & Nội Thất',
		'desc' => 'Kiến tạo không gian sống cá nhân hoá, chú trọng thẩm mỹ, công năng và phong thuỷ — nơi mọi đường nét đều kể câu chuyện của gia chủ.',
		'link_text' => 'Khám Phá Dịch Vụ',
		'link_url' => '#',
		'fallback' => 'service-architecture.png',
		'alt' => 'Thiết kế kiến trúc biệt thự hiện đại — XANH Design & Build',
	],
	[
		'image' => null,
		'title' => 'Thi Công<br>Xây Dựng Trọn Gói',
		'desc' => 'Giám sát kỹ thuật 3 lớp, đúng tiến độ cam kết, 100% không phát sinh — mang đến sự an tâm tuyệt đối cho gia chủ suốt hành trình.',
		'link_text' => 'Khám Phá Dịch Vụ',
		'link_url' => '#',
		'fallback' => 'service-construction.png',
		'alt' => 'Thi công xây dựng nhà trọn gói — XANH Design & Build',
	],
	[
		'image' => null,
		'title' => 'Sản Xuất &<br>Thi Công Nội Thất',
		'desc' => 'Xưởng mộc trực tiếp, vật liệu chuẩn An Cường — hoàn thiện tinh xảo từng chi tiết, nâng tầm không gian sống theo phong cách riêng.',
		'link_text' => 'Khám Phá Dịch Vụ',
		'link_url' => '#',
		'fallback' => 'service-interior.png',
		'alt' => 'Sản xuất thi công nội thất cao cấp — XANH Design & Build',
	],
	[
		'image' => null,
		'title' => 'Cải Tạo &<br>Nâng Cấp',
		'desc' => 'Thổi hồn mới vào không gian cũ — từ cải tạo cục bộ đến tái thiết toàn diện, bảo tồn ký ức và kiến tạo giá trị mới cho ngôi nhà.',
		'link_text' => 'Khám Phá Dịch Vụ',
		'link_url' => '#',
		'fallback' => 'service-renovation.png',
		'alt' => 'Cải tạo nâng cấp nhà ở — XANH Design & Build',
	],
];

$items = get_field('services_items') ?: $default_items;
?>

<section id="services" class="relative section bg-white">
	<div class="site-container">

		<!-- Section Header -->
		<div class="section-header section-header--center">
			<p class="anim-fade-up section-eyebrow">
				<?php echo esc_html($eyebrow); ?>
			</p>
			<h2 class="anim-fade-up section-title text-primary">
				<?php echo wp_kses_post($headline); ?>
			</h2>
			<p class="anim-fade-up section-subtitle">
				<?php echo esc_html($subtitle); ?>
			</p>
		</div>

		<!-- 4-Card Grid -->
		<div class="services-grid">
			<?php foreach ($items as $index => $item):
	$image = $item['image'] ?? null;
	$img_id = is_array($image) ? ($image['ID'] ?? null) : null;
	$fallback = $item['fallback'] ?? $default_items[$index]['fallback'] ?? '';
	$alt = is_array($image) ? ($image['alt'] ?? '') : ($item['alt'] ?? $default_items[$index]['alt'] ?? '');
	$title = wp_kses_post($item['title'] ?? '');
	$desc = esc_html($item['desc'] ?? '');
	$link_text = esc_html($item['link_text'] ?? 'Tìm Hiểu Thêm');
	$link_url = esc_url($item['link_url'] ?? '#');
?>
				<div class="service-card">
					<div class="service-card__img-wrap">
						<?php if ($img_id):
		echo wp_get_attachment_image($img_id, 'medium_large', false, [
			'class' => 'service-card__img',
			'loading' => 'lazy',
		]);
	else: ?>
							<img src="<?php echo esc_url(content_url('/uploads/2026/03/' . $fallback)); ?>"
								alt="<?php echo esc_attr($alt); ?>"
								class="service-card__img" width="400" height="520" loading="lazy" />
						<?php
	endif; ?>
					</div>
					<div class="service-card__body">
						<h3 class="service-card__title"><?php echo $title; ?></h3>
						<p class="service-card__desc"><?php echo $desc; ?></p>
						<a href="<?php echo $link_url; ?>" class="service-card__link">
							<?php echo $link_text; ?>
							<i data-lucide="arrow-right" class="w-4 h-4"></i>
						</a>
					</div>
				</div>
			<?php
endforeach; ?>
		</div>
	</div>
</section>
