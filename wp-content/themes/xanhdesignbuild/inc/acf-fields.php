<?php
/**
 * ACF Fields — Options Page registration & field helpers.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Register ACF Options Page: "Cài Đặt XANH".
 *
 * @return void
 */
function xanh_register_options_pages()
{
	if (!function_exists('acf_add_options_page')) {
		return;
	}

	acf_add_options_page([
		'page_title' => __('Cài Đặt XANH', 'xanh'),
		'menu_title' => __('Cài Đặt XANH', 'xanh'),
		'menu_slug'  => 'xanh-settings',
		'capability' => 'edit_posts',
		'redirect'   => false,
		'icon_url'   => 'dashicons-admin-customizer',
		'position'   => 2,
	]);
}
add_action('acf/init', 'xanh_register_options_pages');

/**
 * Safe getter for ACF option fields.
 *
 * @param  string $field_name ACF field name.
 * @param  string $default    Default value if field is empty.
 * @return string
 */
function xanh_get_option($field_name, $default = '')
{
	if (!function_exists('get_field')) {
		return $default;
	}

	$value = get_field($field_name, 'option');
	return $value ? $value : $default;
}

/**
 * Safe getter for ACF image fields (returns image array or null).
 *
 * @param  string   $field_name ACF field name.
 * @param  int|null $post_id    Post ID (null = current post).
 * @return array|null Image array with 'ID', 'url', 'alt' keys, or null.
 */
function xanh_get_image($field_name, $post_id = null)
{
	if (!function_exists('get_field')) {
		return null;
	}

	$image = $post_id ? get_field($field_name, $post_id) : get_field($field_name);

	if ($image && isset($image['ID'])) {
		return $image;
	}

	return null;
}

/**
 * Safe getter for ACF image fields from Options Page.
 *
 * @param  string $field_name ACF field name on the Options Page.
 * @return array|null Image array with 'ID', 'url', 'alt' keys, or null.
 */
function xanh_get_option_image($field_name)
{
	if (!function_exists('get_field')) {
		return null;
	}

	$image = get_field($field_name, 'option');

	if ($image && isset($image['ID'])) {
		return $image;
	}

	return null;
}

/**
 * ─────────────────────────────────────────────────────────
 * ACF Field Groups — Homepage (Front Page).
 *
 * Registered via PHP for version-control.
 * Tabs: Hero | Marquee (B1a), remaining tabs added in later sprints.
 * ─────────────────────────────────────────────────────────
 */
function xanh_register_homepage_fields()
{
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key' => 'group_homepage',
		'title' => 'Homepage — Nội dung trang chủ',
		'fields' => [

			/* ── Tab: Hero ── */
			[
				'key' => 'field_hp_tab_hero',
				'label' => 'Hero',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_hp_hero_slides',
				'label' => 'Hero Slides',
				'name' => 'hero_slides',
				'type' => 'repeater',
				'instructions' => 'Thêm ảnh nền cho Hero Swiper slider (khuyến nghị 2-4 ảnh, 1920×1080px).',
				'min' => 0,
				'max' => 6,
				'layout' => 'block',
				'button_label' => 'Thêm Slide',
				'sub_fields' => [
					[
						'key' => 'field_hp_hero_slide_image',
						'label' => 'Ảnh nền',
						'name' => 'image',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'medium',
						'mime_types' => 'jpg,jpeg,png,webp',
						'instructions' => 'Kích thước tối ưu: 1920×1080px. Format: WebP hoặc JPG.',
					],
				],
			],
			[
				'key' => 'field_hp_hero_headline',
				'label' => 'Headline',
				'name' => 'hero_headline',
				'type' => 'textarea',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng, &lt;span class="font-light"&gt;…&lt;/span&gt; cho chữ nhạt.',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Đừng Chỉ Xây Một Ngôi Nhà.<br><span class="font-light">Hãy Xây Dựng Sự Bình Yên.</span>',
			],
			[
				'key' => 'field_hp_hero_subheadline',
				'label' => 'Sub-headline',
				'name' => 'hero_subheadline',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => 'Tại Xanh, chúng tôi tin rằng hành trình kiến tạo tổ ấm không nên bắt đầu bằng sự lo âu...',
			],
			[
				'key' => 'field_hp_hero_cta_text',
				'label' => 'CTA Text',
				'name' => 'hero_cta_text',
				'type' => 'text',
				'placeholder' => 'Lắng Nghe Câu Chuyện Của Xanh',
			],
			[
				'key' => 'field_hp_hero_cta_url',
				'label' => 'CTA URL',
				'name' => 'hero_cta_url',
				'type' => 'url',
				'instructions' => 'URL hoặc anchor (#empathy). Để trống = mặc định #empathy.',
				'placeholder' => '#empathy',
			],

			/* ── Tab: Marquee ── */
			[
				'key' => 'field_hp_tab_marquee',
				'label' => 'Marquee',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_hp_marquee_items',
				'label' => 'Marquee Items',
				'name' => 'marquee_items',
				'type' => 'repeater',
				'instructions' => 'Các câu ngắn chạy liên tục trên dải marquee xanh lá. Tối thiểu 3-4 câu.',
				'min' => 0,
				'max' => 8,
				'layout' => 'table',
				'button_label' => 'Thêm Marquee Text',
				'sub_fields' => [
					[
						'key' => 'field_hp_marquee_text',
						'label' => 'Text',
						'name' => 'text',
						'type' => 'text',
						'placeholder' => 'kiến tạo tổ ấm bình yên',
					],
				],
			],

			/* ── Tab: Empathy ── */
			[
				'key' => 'field_hp_tab_empathy',
				'label' => 'Empathy',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_hp_empathy_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'empathy_eyebrow',
				'type' => 'text',
				'placeholder' => 'Chúng Tôi Hiểu',
			],
			[
				'key' => 'field_hp_empathy_headline',
				'label' => 'Headline',
				'name' => 'empathy_headline',
				'type' => 'text',
				'placeholder' => 'Chúng Tôi Hiểu — Xây Nhà Là Quyết Định Lớn Nhất Đời.',
			],
			[
				'key' => 'field_hp_empathy_paragraphs',
				'label' => 'Paragraphs',
				'name' => 'empathy_paragraphs',
				'type' => 'repeater',
				'instructions' => 'Các đoạn văn mô tả nỗi trăn trở.',
				'min' => 0,
				'max' => 5,
				'layout' => 'block',
				'button_label' => 'Thêm đoạn văn',
				'sub_fields' => [
					[
						'key' => 'field_hp_empathy_para_text',
						'label' => 'Nội dung',
						'name' => 'text',
						'type' => 'textarea',
						'rows' => 3,
					],
				],
			],
			[
				'key' => 'field_hp_empathy_quote',
				'label' => 'Quote',
				'name' => 'empathy_quote',
				'type' => 'textarea',
				'instructions' => 'Dùng &lt;strong&gt;Xanh&lt;/strong&gt; để in đậm.',
				'rows' => 2,
				'placeholder' => '"Đó không phải là cách một tổ ấm được sinh ra..."',
			],
			[
				'key' => 'field_hp_empathy_image',
				'label' => 'Ảnh Portrait',
				'name' => 'empathy_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Ảnh cột phải, tỉ lệ 4:5 (800×1000px).',
			],

			/* ── Tab: 4 Xanh Values ── */
			[
				'key' => 'field_hp_tab_values',
				'label' => '4 Xanh Values',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_hp_values_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'values_eyebrow',
				'type' => 'text',
				'placeholder' => 'Triết Lý Cốt Lõi',
			],
			[
				'key' => 'field_hp_values_headline',
				'label' => 'Headline',
				'name' => 'values_headline',
				'type' => 'textarea',
				'rows' => 2,
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
				'placeholder' => 'CON ĐƯỜNG CỦA XANH',
			],
			[
				'key' => 'field_hp_values_tagline',
				'label' => 'Tagline',
				'name' => 'values_tagline',
				'type' => 'text',
				'placeholder' => 'Xây Bằng Sự Tử Tế & Tầm Nhìn Thế Hệ.',
			],
			[
				'key' => 'field_hp_values_items',
				'label' => 'Value Items',
				'name' => 'values_items',
				'type' => 'repeater',
				'instructions' => '4 giá trị cốt lõi.',
				'min' => 4,
				'max' => 4,
				'layout' => 'block',
				'button_label' => 'Thêm Value',
				'sub_fields' => [
					[
						'key' => 'field_hp_values_number',
						'label' => 'Số thứ tự',
						'name' => 'number',
						'type' => 'text',
						'placeholder' => '01',
						'wrapper' => ['width' => '15'],
					],
					[
						'key' => 'field_hp_values_icon',
						'label' => 'Lucide Icon',
						'name' => 'icon',
						'type' => 'text',
						'instructions' => 'Nhập tên icon Lucide. Tra cứu tại <a href="https://lucide.dev/icons" target="_blank">lucide.dev/icons</a>. Ví dụ: circle-dollar-sign, leaf, sun, handshake',
						'placeholder' => 'circle-dollar-sign',
						'wrapper' => ['width' => '35'],
					],
					[
						'key' => 'field_hp_values_title',
						'label' => 'Title',
						'name' => 'title',
						'type' => 'text',
						'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
						'placeholder' => 'Chi Phí<br>Xanh',
						'wrapper' => ['width' => '50'],
					],
					[
						'key' => 'field_hp_values_desc',
						'label' => 'Mô tả',
						'name' => 'desc',
						'type' => 'textarea',
						'rows' => 3,
					],
				],
			],

			/* ── Tab: Services ── */
			[
				'key' => 'field_hp_tab_services',
				'label' => 'Services',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_hp_services_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'services_eyebrow',
				'type' => 'text',
				'placeholder' => 'Lĩnh Vực Hoạt Động',
			],
			[
				'key' => 'field_hp_services_headline',
				'label' => 'Headline',
				'name' => 'services_headline',
				'type' => 'textarea',
				'rows' => 2,
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
				'placeholder' => 'Giải Pháp Khép Kín. Dấu Ấn Độc Bản.',
			],
			[
				'key' => 'field_hp_services_subtitle',
				'label' => 'Subtitle',
				'name' => 'services_subtitle',
				'type' => 'textarea',
				'rows' => 2,
			],
			[
				'key' => 'field_hp_services_items',
				'label' => 'Service Cards',
				'name' => 'services_items',
				'type' => 'repeater',
				'instructions' => '4 dịch vụ chính.',
				'min' => 0,
				'max' => 4,
				'layout' => 'block',
				'button_label' => 'Thêm Dịch Vụ',
				'sub_fields' => [
					[
						'key' => 'field_hp_services_image',
						'label' => 'Ảnh',
						'name' => 'image',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'medium',
						'wrapper' => ['width' => '30'],
					],
					[
						'key' => 'field_hp_services_title',
						'label' => 'Title',
						'name' => 'title',
						'type' => 'text',
						'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
						'wrapper' => ['width' => '70'],
					],
					[
						'key' => 'field_hp_services_desc',
						'label' => 'Mô tả',
						'name' => 'desc',
						'type' => 'textarea',
						'rows' => 2,
					],
					[
						'key' => 'field_hp_services_link_text',
						'label' => 'Link Text',
						'name' => 'link_text',
						'type' => 'text',
						'placeholder' => 'Tìm Hiểu Thêm',
						'wrapper' => ['width' => '50'],
					],
					[
						'key' => 'field_hp_services_link_url',
						'label' => 'Link URL',
						'name' => 'link_url',
						'type' => 'url',
						'wrapper' => ['width' => '50'],
					],
				],
			],

			/* ── Tab: CTA ── */
			[
				'key' => 'field_hp_tab_cta',
				'label' => 'CTA',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_hp_cta_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'cta_eyebrow',
				'type' => 'text',
				'placeholder' => 'Bắt Đầu Hành Trình Của Bạn',
			],
			[
				'key' => 'field_hp_cta_headline',
				'label' => 'Headline',
				'name' => 'cta_headline',
				'type' => 'textarea',
				'rows' => 2,
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
				'placeholder' => 'Không Gian Lý Tưởng Bắt Đầu Từ Một Cuộc Trò Chuyện.',
			],
			[
				'key' => 'field_hp_cta_body',
				'label' => 'Body',
				'name' => 'cta_body',
				'type' => 'textarea',
				'rows' => 2,
			],
			[
				'key' => 'field_hp_cta_primary_text',
				'label' => 'Nút chính — Text',
				'name' => 'cta_primary_text',
				'type' => 'text',
				'placeholder' => 'Đặt Lịch Trao Đổi Riêng',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_hp_cta_primary_url',
				'label' => 'Nút chính — URL',
				'name' => 'cta_primary_url',
				'type' => 'url',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_hp_cta_secondary_text',
				'label' => 'Nút phụ — Text',
				'name' => 'cta_secondary_text',
				'type' => 'text',
				'placeholder' => 'Khám Phá Các Tác Phẩm',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_hp_cta_secondary_url',
				'label' => 'Nút phụ — URL',
				'name' => 'cta_secondary_url',
				'type' => 'url',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_hp_cta_badges',
				'label' => 'Trust Badges',
				'name' => 'cta_badges',
				'type' => 'repeater',
				'instructions' => 'Các con số nổi bật (ví dụ: 10+ năm, 200+ dự án).',
				'min' => 0,
				'max' => 5,
				'layout' => 'table',
				'button_label' => 'Thêm Badge',
				'sub_fields' => [
					[
						'key' => 'field_hp_cta_badge_number',
						'label' => 'Số',
						'name' => 'number',
						'type' => 'text',
						'placeholder' => '200',
						'wrapper' => ['width' => '25'],
					],
					[
						'key' => 'field_hp_cta_badge_suffix',
						'label' => 'Hậu tố',
						'name' => 'suffix',
						'type' => 'text',
						'placeholder' => '+',
						'wrapper' => ['width' => '15'],
					],
					[
						'key' => 'field_hp_cta_badge_label',
						'label' => 'Label',
						'name' => 'label',
						'type' => 'text',
						'placeholder' => 'Dự án hoàn thành',
						'wrapper' => ['width' => '60'],
					],
				],
			],
			[
				'key' => 'field_hp_cta_image',
				'label' => 'Ảnh',
				'name' => 'cta_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Ảnh panel phải, tỉ lệ 4:5.',
			],
			[
				'key' => 'field_hp_cta_quote',
				'label' => 'Quote trên ảnh',
				'name' => 'cta_quote',
				'type' => 'textarea',
				'rows' => 2,
				'placeholder' => '"Thiết kế không chỉ là vẻ đẹp bên ngoài..."',
			],

			/* ── Tab: Portfolio Featured ── */
			[
				'key' => 'field_hp_tab_portfolio',
				'label' => 'Dự Án Tiêu Biểu',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_hp_portfolio_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'portfolio_eyebrow',
				'type' => 'text',
				'placeholder' => 'Dự Án Tiêu Biểu',
			],
			[
				'key' => 'field_hp_portfolio_headline',
				'label' => 'Headline',
				'name' => 'portfolio_headline',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
				'placeholder' => 'Mỗi Công Trình Là Một<br>Câu Chuyện Của Sự Tử Tế.',
			],
			[
				'key' => 'field_hp_portfolio_subtitle',
				'label' => 'Subtitle',
				'name' => 'portfolio_subtitle',
				'type' => 'textarea',
				'rows' => 2,
			],
			[
				'key' => 'field_hp_portfolio_projects',
				'label' => 'Projects',
				'name' => 'portfolio_projects',
				'type' => 'repeater',
				'instructions' => 'Tối đa 4 dự án tiêu biểu.',
				'min' => 0,
				'max' => 4,
				'layout' => 'block',
				'button_label' => 'Thêm Dự Án',
				'sub_fields' => [
					[
						'key' => 'field_hp_portfolio_before_img',
						'label' => 'Ảnh Before',
						'name' => 'before_img',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'medium',
						'instructions' => 'Ảnh trước thi công (tỉ lệ 4:3).',
						'wrapper' => ['width' => '50'],
					],
					[
						'key' => 'field_hp_portfolio_after_img',
						'label' => 'Ảnh After',
						'name' => 'after_img',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'medium',
						'instructions' => 'Ảnh sau thi công (tỉ lệ 4:3).',
						'wrapper' => ['width' => '50'],
					],
					[
						'key' => 'field_hp_portfolio_thumb_img',
						'label' => 'Ảnh Thumbnail',
						'name' => 'thumb_img',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'thumbnail',
						'instructions' => 'Ảnh nhỏ ở thanh thumbnail.',
						'wrapper' => ['width' => '30'],
					],
					[
						'key' => 'field_hp_portfolio_tag',
						'label' => 'Tag',
						'name' => 'tag',
						'type' => 'text',
						'placeholder' => 'NHÀ PHỐ',
						'wrapper' => ['width' => '35'],
					],
					[
						'key' => 'field_hp_portfolio_title',
						'label' => 'Tên dự án',
						'name' => 'title',
						'type' => 'text',
						'placeholder' => 'Villa Bình An',
						'wrapper' => ['width' => '35'],
					],
					[
						'key' => 'field_hp_portfolio_area',
						'label' => 'Diện tích',
						'name' => 'area',
						'type' => 'text',
						'placeholder' => '320 m²',
						'wrapper' => ['width' => '25'],
					],
					[
						'key' => 'field_hp_portfolio_duration',
						'label' => 'Thời gian',
						'name' => 'duration',
						'type' => 'text',
						'placeholder' => '8 tháng',
						'wrapper' => ['width' => '25'],
					],
					[
						'key' => 'field_hp_portfolio_type',
						'label' => 'Loại công trình',
						'name' => 'type',
						'type' => 'text',
						'placeholder' => 'Biệt thự hiện đại',
						'wrapper' => ['width' => '25'],
					],
					[
						'key' => 'field_hp_portfolio_quote',
						'label' => 'Trích dẫn',
						'name' => 'quote',
						'type' => 'textarea',
						'rows' => 2,
						'placeholder' => '"Xanh đã biến giấc mơ của chúng tôi thành hiện thực..."',
					],
					[
						'key' => 'field_hp_portfolio_author',
						'label' => 'Tác giả trích dẫn',
						'name' => 'author',
						'type' => 'text',
						'placeholder' => '— Anh Nguyễn Văn A, Chủ nhà',
						'wrapper' => ['width' => '50'],
					],
					[
						'key' => 'field_hp_portfolio_link',
						'label' => 'Link chi tiết',
						'name' => 'link',
						'type' => 'url',
						'wrapper' => ['width' => '50'],
					],
				],
			],

			/* ── Tab: Process ── */
			[
				'key' => 'field_hp_tab_process',
				'label' => 'Quy Trình',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_hp_process_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'process_eyebrow',
				'type' => 'text',
				'placeholder' => 'Quy Trình',
			],
			[
				'key' => 'field_hp_process_headline',
				'label' => 'Headline',
				'name' => 'process_headline',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
				'placeholder' => 'Chúng Tôi Đồng Hành Cùng Bạn<br>Đến Từng Viên Gạch Cuối Cùng',
			],
			[
				'key' => 'field_hp_process_subtitle',
				'label' => 'Subtitle',
				'name' => 'process_subtitle',
				'type' => 'textarea',
				'rows' => 2,
			],
			[
				'key' => 'field_hp_process_steps',
				'label' => 'Các bước',
				'name' => 'process_steps',
				'type' => 'repeater',
				'instructions' => '6 bước quy trình.',
				'min' => 0,
				'max' => 6,
				'layout' => 'block',
				'button_label' => 'Thêm Bước',
				'sub_fields' => [
					[
						'key' => 'field_hp_process_num',
						'label' => 'Số thứ tự',
						'name' => 'num',
						'type' => 'text',
						'placeholder' => '01',
						'wrapper' => ['width' => '15'],
					],
					[
						'key' => 'field_hp_process_title',
						'label' => 'Tiêu đề',
						'name' => 'title',
						'type' => 'text',
						'placeholder' => 'Tư Vấn',
						'wrapper' => ['width' => '35'],
					],
					[
						'key' => 'field_hp_process_desc',
						'label' => 'Mô tả',
						'name' => 'desc',
						'type' => 'textarea',
						'rows' => 2,
						'wrapper' => ['width' => '50'],
					],
					[
						'key' => 'field_hp_process_image',
						'label' => 'Ảnh nền',
						'name' => 'image',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'medium',
						'instructions' => 'Ảnh nền panel, tối ưu 800×600px.',
					],
				],
			],

			/* ── Tab: CTA Contact ── */
			[
				'key' => 'field_hp_tab_cta_contact',
				'label' => 'CTA Contact',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_hp_ctac_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'ctac_eyebrow',
				'type' => 'text',
				'placeholder' => 'Bắt Đầu Hành Trình',
			],
			[
				'key' => 'field_hp_ctac_heading',
				'label' => 'Heading',
				'name' => 'ctac_heading',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
				'placeholder' => 'Mọi Tổ Ấm Bình Yên<br>Đều Bắt Đầu Từ<br>Một Cuộc Trò Chuyện.',
			],
			[
				'key' => 'field_hp_ctac_body',
				'label' => 'Body',
				'name' => 'ctac_body',
				'type' => 'textarea',
				'rows' => 2,
			],
			[
				'key' => 'field_hp_ctac_bg_image',
				'label' => 'Ảnh nền',
				'name' => 'ctac_bg_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Ảnh nền full-width (1920×1080px khuyến nghị).',
			],
			[
				'key' => 'field_hp_ctac_btn_text',
				'label' => 'Nút CTA — Text',
				'name' => 'ctac_btn_text',
				'type' => 'text',
				'placeholder' => 'Đặt Lịch Tư Vấn Riêng',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_hp_ctac_btn_url',
				'label' => 'Nút CTA — URL',
				'name' => 'ctac_btn_url',
				'type' => 'url',
				'wrapper' => ['width' => '50'],
			],

			/* ── Tab: Partners ── */
			[
				'key' => 'field_hp_tab_partners',
				'label' => 'Đối Tác',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_hp_partners_items',
				'label' => 'Partner Logos',
				'name' => 'partners_items',
				'type' => 'repeater',
				'instructions' => 'Logo đối tác chiến lược. Upload ảnh PNG/SVG nền trong suốt, kích thước ~200×80px.',
				'min' => 0,
				'max' => 12,
				'layout' => 'table',
				'button_label' => 'Thêm Đối Tác',
				'sub_fields' => [
					[
						'key' => 'field_hp_partners_name',
						'label' => 'Tên',
						'name' => 'name',
						'type' => 'text',
						'placeholder' => 'Dulux',
						'wrapper' => ['width' => '40'],
					],
					[
						'key' => 'field_hp_partners_logo',
						'label' => 'Logo',
						'name' => 'logo',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'thumbnail',
						'mime_types' => 'png,svg,webp',
						'wrapper' => ['width' => '40'],
					],
					[
						'key' => 'field_hp_partners_url',
						'label' => 'Website',
						'name' => 'url',
						'type' => 'url',
						'wrapper' => ['width' => '20'],
					],
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'page_type',
					'operator' => '==',
					'value' => 'front_page',
				],
			],
		],
		'style' => 'default',
		'position' => 'normal',
		'label_placement' => 'top',
		'menu_order' => 0,
		'active' => true,
	]);
}
add_action('acf/init', 'xanh_register_homepage_fields');

/**
 * Auto-populate default values for the "4 Xanh Values" repeater
 * when ACF loads and finds empty/incomplete rows.
 *
 * @param  mixed  $value   Current field value.
 * @param  int    $post_id Post ID.
 * @param  array  $field   ACF field config.
 * @return mixed
 */
function xanh_values_items_defaults($value, $post_id, $field)
{
	$defaults = [
		[
			'number' => '01',
			'icon' => 'circle-dollar-sign',
			'title' => 'Chi Phí<br>Xanh',
			'desc' => 'Dự toán minh bạch từ viên gạch đầu tiên. Cam kết không phát sinh ngoài hợp đồng — để gia chủ luôn an tâm về tài chính.',
		],
		[
			'number' => '02',
			'icon' => 'leaf',
			'title' => 'Vật Liệu<br>Xanh',
			'desc' => 'Chọn lọc vật liệu chính hãng, thân thiện môi trường — tạo ra những giải pháp kiến trúc bền vững, tiên phong.',
		],
		[
			'number' => '03',
			'icon' => 'sun',
			'title' => 'Vận Hành<br>Xanh',
			'desc' => 'Quy trình chuẩn ISO. Giám sát chất lượng 3 lớp, cập nhật tiến độ hàng tuần qua ứng dụng — minh bạch tuyệt đối.',
		],
		[
			'number' => '04',
			'icon' => 'handshake',
			'title' => 'Giá Trị<br>Xanh',
			'desc' => 'Đồng hành tận tâm từ tư vấn đến bảo hành trọn đời — mỗi ngôi nhà XANH gia tăng giá trị cho thế hệ tiếp theo.',
		],
	];

	// If value is empty or not an array, return all defaults.
	if (empty($value) || !is_array($value)) {
		return $defaults;
	}

	// Fill in missing sub-field values from defaults.
	foreach ($value as $i => &$row) {
		if (!isset($defaults[$i])) {
			break;
		}
		foreach ($defaults[$i] as $key => $default_val) {
			if (empty($row[$key])) {
				$row[$key] = $default_val;
			}
		}
	}

	return $value;
}
add_filter('acf/load_value/key=field_hp_values_items', 'xanh_values_items_defaults', 10, 3);

/**
 * ─────────────────────────────────────────────────────────
 * ACF Field Groups — About Page (Giới Thiệu).
 *
 * Registered via PHP for version-control.
 * Location: Page slug = gioi-thieu.
 * ─────────────────────────────────────────────────────────
 */
function xanh_register_about_fields()
{
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key' => 'group_about_page',
		'title' => 'About — Nội dung trang Giới Thiệu',
		'fields' => [

			/* ── Tab: Hero ── */
			[
				'key' => 'field_about_tab_hero',
				'label' => 'Hero',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_about_hero_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'about_hero_eyebrow',
				'type' => 'text',
				'placeholder' => 'Câu Chuyện Của Chúng Tôi',
			],
			[
				'key' => 'field_about_hero_title',
				'label' => 'Headline',
				'name' => 'about_hero_title',
				'type' => 'textarea',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng, &lt;span class="font-light"&gt;…&lt;/span&gt; cho chữ nhạt.',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Xanh — Design & Build<br /><span class="font-light">Câu Chuyện Của Sự Liền Mạch & Bền Vững.</span>',
			],
			[
				'key' => 'field_about_hero_subtitle',
				'label' => 'Sub-headline',
				'name' => 'about_hero_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Chúng tôi là thương hiệu cung cấp giải pháp nội thất và xây dựng hoàn thiện theo hướng bền vững.',
			],
			[
				'key' => 'field_about_hero_image',
				'label' => 'Ảnh nền Hero',
				'name' => 'about_hero_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'mime_types' => 'jpg,jpeg,png,webp',
				'instructions' => 'Kích thước tối ưu: 1920×1080px. Format: WebP hoặc JPG.',
			],
			[
				'key' => 'field_about_hero_cta_text',
				'label' => 'CTA Text',
				'name' => 'about_hero_cta_text',
				'type' => 'text',
				'placeholder' => 'Khám Phá Hành Trình Xanh',
			],
			[
				'key' => 'field_about_hero_video_url',
				'label' => 'Video URL (YouTube)',
				'name' => 'about_hero_video_url',
				'type' => 'url',
				'instructions' => 'URL YouTube (watch hoặc embed). Để trống = ẩn nút Play. Ví dụ: https://www.youtube.com/watch?v=xxxxx',
				'placeholder' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
			],

			/* ── Tab: Pain Points (Section 2) ── */
			[
				'key' => 'field_about_tab_pain',
				'label' => 'Nỗi Trăn Trở',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_about_pain_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'about_pain_eyebrow',
				'type' => 'text',
				'placeholder' => 'Khởi nguồn từ những trăn trở',
			],
			[
				'key' => 'field_about_pain_title',
				'label' => 'Tiêu đề',
				'name' => 'about_pain_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => 'Mọi Điểm Chạm Bắt Đầu Từ Một Sự Thật...',
			],
			[
				'key' => 'field_about_pain_subtitle',
				'label' => 'Mô tả',
				'name' => 'about_pain_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Xuất phát là những chuyên gia trong lĩnh vực giải pháp nội thất hoàn thiện và vật tư, trải qua nhiều dự án lớn nhỏ, chúng tôi đã chứng kiến 5 "nỗi đau" lớn mà các chủ đầu tư thường xuyên phải đối mặt:',
			],
			[
				'key' => 'field_about_pain_items',
				'label' => 'Danh sách nỗi đau',
				'name' => 'about_pain_items',
				'type' => 'repeater',
				'min' => 0,
				'max' => 6,
				'layout' => 'block',
				'button_label' => 'Thêm nỗi đau',
				'sub_fields' => [
					['key' => 'field_about_pain_icon', 'label' => 'Lucide Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'image-off (vd: trending-up, split, hammer, shield-alert)'],
					['key' => 'field_about_pain_title2', 'label' => 'Tiêu đề', 'name' => 'title', 'type' => 'text', 'placeholder' => 'Bản vẽ tính thực thi thấp'],
					['key' => 'field_about_pain_quote', 'label' => 'Trích dẫn', 'name' => 'quote', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '', 'placeholder' => '"Bản vẽ thiết kế đẹp nhưng thi công không giống, hoặc hoàn toàn không thể thực thi trong điều kiện thực tế."'],
				],
			],

			/* ── Tab: Turning Point (Section 3) ── */
			[
				'key' => 'field_about_tab_turning',
				'label' => 'Bước Ngoặt',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_about_turn_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'about_turn_eyebrow',
				'type' => 'text',
				'placeholder' => 'Bước Ngoặt',
			],
			[
				'key' => 'field_about_turn_title',
				'label' => 'Tiêu đề',
				'name' => 'about_turn_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => 'Lời Giải Cho Sự "Đứt Gãy" Của Thị Trường',
			],
			[
				'key' => 'field_about_turn_subtitle',
				'label' => 'Mô tả',
				'name' => 'about_turn_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Chúng tôi nhận ra rằng, rễ sâu của rủi ro không nằm ở từng khâu riêng lẻ — mà ở sự đứt gãy giữa các khâu đó.',
			],
			[
				'key' => 'field_about_turn_bg_image',
				'label' => 'Ảnh nền (20% opacity)',
				'name' => 'about_turn_bg_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
			],

			/* ── Tab: Promise (Section 4) ── */
			[
				'key' => 'field_about_tab_promise',
				'label' => 'Sứ Mệnh',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_about_promise_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'about_promise_eyebrow',
				'type' => 'text',
				'placeholder' => 'Sứ Mệnh Xuyên Suốt',
			],
			[
				'key' => 'field_about_promise_title',
				'label' => 'Tiêu đề',
				'name' => 'about_promise_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => 'Đầu Mối Duy Nhất. Trách Nhiệm Trọn Vẹn.',
			],
			[
				'key' => 'field_about_promise_lead',
				'label' => 'Đoạn dẫn (lead)',
				'name' => 'about_promise_lead',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Xanh ra đời với sứ mệnh trở thành đầu mối duy nhất chịu trách nhiệm xuyên suốt — từ bản vẽ đầu tiên đến ngày bàn giao chìa khóa.',
			],
			[
				'key' => 'field_about_promise_body',
				'label' => 'Đoạn thân',
				'name' => 'about_promise_body',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Sự liền mạch này chính là chìa khóa để giảm thiểu rủi ro, tối ưu giá trị cho gia chủ, và kiến tạo nên những không gian sống "Xanh" — nơi mỗi chi tiết đều có ý nghĩa.',
			],
			[
				'key' => 'field_about_promise_cta_text',
				'label' => 'CTA Text',
				'name' => 'about_promise_cta_text',
				'type' => 'text',
				'placeholder' => 'Khám Phá Hành Trình Của Bạn',
			],
			[
				'key' => 'field_about_promise_cta_url',
				'label' => 'CTA URL',
				'name' => 'about_promise_cta_url',
				'type' => 'url',
				'placeholder' => '#',
			],

			/* ── Tab: Philosophy (Section 5) ── */
			[
				'key' => 'field_about_tab_philo',
				'label' => 'Triết Lý 4 Xanh',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_about_philo_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'about_philo_eyebrow',
				'type' => 'text',
				'placeholder' => 'Triết lý cốt lõi',
			],
			[
				'key' => 'field_about_philo_title',
				'label' => 'Tiêu đề',
				'name' => 'about_philo_title',
				'type' => 'text',
				'placeholder' => 'Không Gian Kiến Tạo Cho Sự Bình Yên',
			],
			[
				'key' => 'field_about_philo_subtitle',
				'label' => 'Mô tả',
				'name' => 'about_philo_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Không mang ý nghĩa phô trương, "Xanh" trong ngôn ngữ thiết kế của chúng tôi là sự vừa vặn hoàn hảo giữa con người, không gian và môi trường.',
			],
			[
				'key' => 'field_about_philo_items',
				'label' => 'Triết lý Cards (đúng 4 card)',
				'name' => 'about_philo_items',
				'type' => 'repeater',
				'min' => 4,
				'max' => 4,
				'layout' => 'block',
				'button_label' => 'Thêm Card',
				'sub_fields' => [
					['key' => 'field_about_philo_icon', 'label' => 'Lucide Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'leaf  (vd: leaf | box | zap | sun)'],
					['key' => 'field_about_philo_title2', 'label' => 'Tiêu đề', 'name' => 'title', 'type' => 'text', 'placeholder' => 'Không Gian Sống Trong Lành'],
					['key' => 'field_about_philo_desc', 'label' => 'Mô tả', 'name' => 'desc', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '', 'placeholder' => 'Nơi ánh sáng tự nhiên và luồng khí tươi ôm trọn mỗi khoảnh khắc, kiến tạo một tổ ấm thực sự tốt cho sức khỏe và tinh thần.'],
					['key' => 'field_about_philo_image', 'label' => 'Ảnh nền', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => 'Upload ảnh tương ứng: philo-1.png / philo-2.png / philo-3.png / philo-4.png'],
				],
			],

			/* ── Tab: Core Values (Section 5.5) ── */
			[
				'key' => 'field_about_tab_cv',
				'label' => 'Cam Kết Cốt Lõi',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_about_cv_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'about_cv_eyebrow',
				'type' => 'text',
				'placeholder' => 'Cam Kết Của Chúng Tôi',
			],
			[
				'key' => 'field_about_cv_title',
				'label' => 'Tiêu đề',
				'name' => 'about_cv_title',
				'type' => 'text',
				'placeholder' => 'Bản Sắc Cốt Lõi — Lời Cam Kết Bền Vững',
			],
			[
				'key' => 'field_about_cv_subtitle',
				'label' => 'Mô tả',
				'name' => 'about_cv_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Đây không chỉ là giá trị — đây là lời tuyên thệ. Mỗi công trình Xanh bàn giao đều mang trong mình bốn nền tảng không thể thương lượng.',
			],
			[
				'key' => 'field_about_cv_items',
				'label' => 'Core Value Cards',
				'name' => 'about_cv_items',
				'type' => 'repeater',
				'min' => 0,
				'max' => 6,
				'layout' => 'block',
				'button_label' => 'Thêm Card',
				'sub_fields' => [
					['key' => 'field_about_cv_number', 'label' => 'Số', 'name' => 'number', 'type' => 'text', 'placeholder' => '01  (vd: 01 | 02 | 03 | 04)'],
					['key' => 'field_about_cv_icon', 'label' => 'Lucide Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'target  (vd: target | eye | trees | handshake)'],
					['key' => 'field_about_cv_title2', 'label' => 'Tiêu đề', 'name' => 'title', 'type' => 'text', 'placeholder' => 'Hiệu Quả Thực Tế'],
					['key' => 'field_about_cv_desc', 'label' => 'Mô tả', 'name' => 'desc', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '', 'placeholder' => 'Chúng tôi không làm cho đẹp chỉ trên giấy. Mỗi bản vẽ đều được kiểm chứng — chỉ thực hiện những gì hoàn toàn có thể thi công được.'],
				],
			],

			/* ── Tab: Team (Section 6) ── */
			[
				'key' => 'field_about_tab_team',
				'label' => 'Đội Ngũ',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_about_team_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'about_team_eyebrow',
				'type' => 'text',
				'placeholder' => 'Con Người Kiến Tạo',
			],
			[
				'key' => 'field_about_team_title',
				'label' => 'Tiêu đề',
				'name' => 'about_team_title',
				'type' => 'text',
				'placeholder' => 'Đội Ngũ Chuyên Gia',
			],
			[
				'key' => 'field_about_team_subtitle',
				'label' => 'Mô tả',
				'name' => 'about_team_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Những kiến trúc sư và kỹ sư đam mê sự hoàn mỹ. Chúng tôi không chỉ xây dựng không gian, chúng tôi kiến tạo những giá trị bền vững vượt thời gian.',
			],
			[
				'key' => 'field_about_team_members',
				'label' => 'Thành viên',
				'name' => 'about_team_members',
				'type' => 'repeater',
				'min' => 0,
				'max' => 4,
				'layout' => 'block',
				'button_label' => 'Thêm thành viên',
				'sub_fields' => [
					['key' => 'field_about_team_name', 'label' => 'Tên', 'name' => 'name', 'type' => 'text', 'placeholder' => 'Minh Tuấn'],
					['key' => 'field_about_team_role', 'label' => 'Chức vụ', 'name' => 'role', 'type' => 'text', 'placeholder' => 'Giám đốc điều hành (CEO)'],
					['key' => 'field_about_team_quote', 'label' => 'Quote', 'name' => 'quote', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '', 'placeholder' => '"Kiến tạo không gian, gieo mầm bình yên cho mỗi gia chủ."'],
					['key' => 'field_about_team_image', 'label' => 'Ảnh', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => 'Tỷ lệ 4:5 (portrait). Ảnh: tuan-ceo / yen-architect / bao-pm / huy-engineer'],
					['key' => 'field_about_team_highlight', 'label' => 'Highlight role?', 'name' => 'role_highlight', 'type' => 'true_false', 'default_value' => 0, 'ui' => 1],
				],
			],

			/* ── Tab: Final CTA (Section 7) ── */
			[
				'key' => 'field_about_tab_cta',
				'label' => 'CTA Cuối',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_about_cta_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'about_cta_eyebrow',
				'type' => 'text',
				'placeholder' => 'Khởi Đầu Hành Trình',
			],
			[
				'key' => 'field_about_cta_title',
				'label' => 'Tiêu đề',
				'name' => 'about_cta_title',
				'type' => 'text',
				'placeholder' => 'Hành trình kiến tạo không gian bắt đầu bằng một cuộc trò chuyện.',
			],
			[
				'key' => 'field_about_cta_subtitle',
				'label' => 'Mô tả',
				'name' => 'about_cta_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Chúng tôi hiểu — xây nhà là quyết định lớn nhất đời. Đặt lịch trao đổi riêng để mỗi mong ước của bạn được lắng nghe.',
			],
			[
				'key' => 'field_about_cta_btn1_text',
				'label' => 'Nút chính - Text',
				'name' => 'about_cta_btn1_text',
				'type' => 'text',
				'placeholder' => 'Đặt Lịch Tư Vấn Riêng',
			],
			[
				'key' => 'field_about_cta_btn1_url',
				'label' => 'Nút chính - URL',
				'name' => 'about_cta_btn1_url',
				'type' => 'url',
				'placeholder' => '/lien-he/',
			],
			[
				'key' => 'field_about_cta_btn2_text',
				'label' => 'Nút phụ - Text',
				'name' => 'about_cta_btn2_text',
				'type' => 'text',
				'placeholder' => 'Khám Phá Các Tác Phẩm',
			],
			[
				'key' => 'field_about_cta_btn2_url',
				'label' => 'Nút phụ - URL',
				'name' => 'about_cta_btn2_url',
				'type' => 'url',
				'placeholder' => '/du-an/',
			],
			[
				'key' => 'field_about_cta_image',
				'label' => 'Ảnh portrait CTA',
				'name' => 'about_cta_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Tỷ lệ 4:5 (portrait). Kích thước tối ưu: 480×600px.',
			],

		],
		'location' => [
			[
				[
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'page-about.php',
				],
			],
		],
		'style' => 'default',
		'position' => 'normal',
		'label_placement' => 'top',
		'menu_order' => 0,
		'active' => true,
	]);
}
add_action('acf/init', 'xanh_register_about_fields');

/**
 * ─────────────────────────────────────────────────────────
 * ACF Field Groups — Portfolio Archive (Dự Án).
 *
 * All settings fields on a single Options page with vertical tabs.
 * ─────────────────────────────────────────────────────────
 */
function xanh_register_settings_fields()
{
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key' => 'group_xanh_settings',
		'title' => 'Cài Đặt XANH',
		'fields' => [

			/* ══════ TAB 1: Trang Dự Án ══════ */
			[
				'key' => 'field_tab_portfolio',
				'label' => 'Trang Dự Án',
				'type' => 'tab',
				'placement' => 'left',
			],

			/* ── Section: Hero ── */
			[
				'key' => 'field_pf_hero_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'portfolio_hero_eyebrow',
				'type' => 'text',
				'placeholder' => 'Portfolio — Tác Phẩm Thực Tế',
			],
			[
				'key' => 'field_pf_hero_title',
				'label' => 'Headline',
				'name' => 'portfolio_hero_title',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br class="hidden sm:block"&gt; để xuống dòng trên desktop.',
				'placeholder' => 'Tác Phẩm Thực Tế.<br class="hidden sm:block" /> Giá Trị Khởi Nguồn Từ Sự Thật.',
			],
			[
				'key' => 'field_pf_hero_subtitle',
				'label' => 'Sub-headline',
				'name' => 'portfolio_hero_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Mỗi công trình là một hành trình — từ bản vẽ 3D đến không gian sống thực tế, minh bạch từng chi tiết, trọn vẹn từng kỳ vọng.',
			],
			[
				'key' => 'field_pf_hero_image',
				'label' => 'Ảnh nền Hero',
				'name' => 'portfolio_hero_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'mime_types' => 'jpg,jpeg,png,webp',
				'instructions' => 'Kích thước tối ưu: 1920×1080px. Format: WebP hoặc JPG.',
			],

			/* ── Tab: Counter Strip ── */
			[
				'key' => 'field_pf_counter_projects',
				'label' => 'Số Dự Án',
				'name' => 'portfolio_counter_projects',
				'type' => 'text',
				'instructions' => 'Để trống = tự đếm từ CPT xanh_project (published).',
				'placeholder' => '47',
				'wrapper' => ['width' => '33'],
			],
			[
				'key' => 'field_pf_counter_3d',
				'label' => '% Sát 3D',
				'name' => 'portfolio_counter_3d',
				'type' => 'text',
				'placeholder' => '98',
				'wrapper' => ['width' => '33'],
			],
			[
				'key' => 'field_pf_counter_overrun',
				'label' => '% Phát Sinh',
				'name' => 'portfolio_counter_overrun',
				'type' => 'text',
				'placeholder' => '0',
				'wrapper' => ['width' => '33'],
			],

			/* ── Tab: CTA ── */
			[
				'key' => 'field_pf_cta_title',
				'label' => 'Headline',
				'name' => 'portfolio_cta_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br class="hidden sm:block"&gt; để xuống dòng.',
				'placeholder' => 'Bạn Cũng Muốn Một Không Gian Sống<br class="hidden sm:block" /> Trọn Vẹn Và Minh Bạch?',
			],
			[
				'key' => 'field_pf_cta_subtitle',
				'label' => 'Mô tả',
				'name' => 'portfolio_cta_subtitle',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => 'Hãy bắt đầu hành trình kiến tạo tổ ấm cùng đội ngũ XANH — minh bạch từ dự toán đến bàn giao.',
			],
			[
				'key' => 'field_pf_cta_bg_image',
				'label' => 'Ảnh nền CTA',
				'name' => 'portfolio_cta_bg_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Ảnh panorama công trình (1920×600px).',
			],
			[
				'key' => 'field_pf_cta_btn1_text',
				'label' => 'Nút chính — Text',
				'name' => 'portfolio_cta_btn1_text',
				'type' => 'text',
				'placeholder' => 'Khám Phá Dự Toán Của Bạn',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_pf_cta_btn1_url',
				'label' => 'Nút chính — URL',
				'name' => 'portfolio_cta_btn1_url',
				'type' => 'url',
				'placeholder' => '/du-toan/',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_pf_cta_btn2_text',
				'label' => 'Nút phụ — Text',
				'name' => 'portfolio_cta_btn2_text',
				'type' => 'text',
				'placeholder' => 'Chat Với Kỹ Sư Trưởng',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_pf_cta_btn2_url',
				'label' => 'Nút phụ — URL',
				'name' => 'portfolio_cta_btn2_url',
				'type' => 'url',
				'instructions' => 'URL Zalo OA hoặc link liên hệ.',
				'wrapper' => ['width' => '50'],
			],


			/* ══════ TAB 2: Header & Footer ══════ */
			[
				'key' => 'field_tab_header_footer',
				'label' => 'Header & Footer',
				'type' => 'tab',
				'placement' => 'left',
			],

			/* ── Section: Logo ── */
			[
				'key' => 'field_hf_logo_header_white',
				'label' => 'Logo Header (Trắng)',
				'name' => 'xanh_logo_header_white',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Logo SVG/PNG nền trong suốt, hiển thị trên header trong suốt. Kích thước ~200×36px.',
				'mime_types' => 'svg,png,webp',
			],
			[
				'key' => 'field_hf_logo_header_dark',
				'label' => 'Logo Header (Tối)',
				'name' => 'xanh_logo_header_dark',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Logo cho header khi cuộn (nền trắng). Kích thước ~200×36px.',
				'mime_types' => 'svg,png,webp',
			],
			[
				'key' => 'field_hf_logo_footer',
				'label' => 'Logo Footer',
				'name' => 'xanh_logo_footer',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Logo SVG/PNG cho footer (nền tối). Kích thước ~200×36px.',
				'mime_types' => 'svg,png,webp',
			],

			/* ── Tab: Thông tin liên hệ ── */
			[
				'key' => 'field_hf_hotline',
				'label' => 'Hotline',
				'name' => 'xanh_hotline',
				'type' => 'text',
				'placeholder' => '0909 123 456',
				'instructions' => 'Số điện thoại chính — hiển thị ở header, footer, contact.',
			],
			[
				'key' => 'field_hf_email',
				'label' => 'Email',
				'name' => 'xanh_email',
				'type' => 'email',
				'placeholder' => 'info@xanhdesignbuild.vn',
			],
			[
				'key' => 'field_hf_address',
				'label' => 'Địa chỉ',
				'name' => 'xanh_address',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => '123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh',
			],

			/* ── Tab: Mạng xã hội ── */
			[
				'key' => 'field_hf_facebook',
				'label' => 'Facebook URL',
				'name' => 'xanh_facebook',
				'type' => 'url',
				'placeholder' => 'https://facebook.com/xanhdesignbuild',
			],
			[
				'key' => 'field_hf_instagram',
				'label' => 'Instagram URL',
				'name' => 'xanh_instagram',
				'type' => 'url',
				'placeholder' => 'https://instagram.com/xanhdesignbuild',
			],
			[
				'key' => 'field_hf_youtube',
				'label' => 'YouTube URL',
				'name' => 'xanh_youtube',
				'type' => 'url',
				'placeholder' => 'https://youtube.com/@xanhdesignbuild',
			],
			[
				'key' => 'field_hf_zalo',
				'label' => 'Zalo OA URL',
				'name' => 'xanh_zalo_oa',
				'type' => 'url',
				'placeholder' => 'https://zalo.me/xanhdesignbuild',
			],

			/* ── Tab: Footer ── */
			[
				'key' => 'field_hf_footer_desc',
				'label' => 'Mô tả Footer',
				'name' => 'xanh_footer_desc',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Kiến tạo tổ ấm bình yên — minh bạch từ viên gạch đầu tiên.',
				'instructions' => 'Đoạn mô tả ngắn hiển thị dưới logo footer.',
			],
			[
				'key' => 'field_hf_footer_badges',
				'label' => 'Footer Badges',
				'name' => 'xanh_footer_badges',
				'type' => 'repeater',
				'min' => 0,
				'max' => 5,
				'layout' => 'table',
				'button_label' => 'Thêm Badge',
				'instructions' => 'Các huy hiệu/chứng nhận hiển thị ở footer (ví dụ: ISO 9001:2015, 10+ Năm).',
				'sub_fields' => [
					['key' => 'field_hf_badge_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text', 'placeholder' => 'ISO 9001:2015'],
				],
			],

			/* ══════ TAB 3: Blog ══════ */
			[
				'key' => 'field_tab_blog',
				'label' => 'Blog',
				'type' => 'tab',
				'placement' => 'left',
			],

			/* ── Section: Hero ── */
			[
				'key' => 'field_blog_hero_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'blog_hero_eyebrow',
				'type' => 'text',
				'placeholder' => 'Blog & Cảm Hứng',
			],
			[
				'key' => 'field_blog_hero_title',
				'label' => 'Headline',
				'name' => 'blog_hero_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
				'placeholder' => 'Chia Sẻ Cảm Hứng Kiến Tạo',
			],
			[
				'key' => 'field_blog_hero_subtitle',
				'label' => 'Sub-headline',
				'name' => 'blog_hero_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Khám phá kiến thức, kinh nghiệm và cảm hứng từ những dự án thực tế.',
			],
			[
				'key' => 'field_blog_hero_image',
				'label' => 'Ảnh nền Hero',
				'name' => 'blog_hero_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'mime_types' => 'jpg,jpeg,png,webp',
				'instructions' => 'Kích thước tối ưu: 1920×600px.',
			],

			/* ── Tab: Sidebar ── */
			[
				'key' => 'field_blog_posts_per_page',
				'label' => 'Số bài mỗi trang',
				'name' => 'blog_posts_per_page',
				'type' => 'number',
				'default_value' => 9,
				'min' => 3,
				'max' => 24,
				'step' => 3,
				'instructions' => 'Số bài viết hiển thị mỗi trang (mặc định 9).',
			],
			[
				'key' => 'field_blog_show_sidebar',
				'label' => 'Hiện Sidebar?',
				'name' => 'blog_show_sidebar',
				'type' => 'true_false',
				'default_value' => 0,
				'ui' => 1,
				'ui_on_text' => 'Có',
				'ui_off_text' => 'Không',
			],

			/* ══════ TAB 4: Liên Hệ ══════ */
			[
				'key' => 'field_tab_contact',
				'label' => 'Liên Hệ',
				'type' => 'tab',
				'placement' => 'left',
			],

			/* ── Section: Hero ── */
			[
				'key' => 'field_ct_hero_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'contact_hero_eyebrow',
				'type' => 'text',
				'placeholder' => 'Liên Hệ Với Chúng Tôi',
			],
			[
				'key' => 'field_ct_hero_title',
				'label' => 'Headline',
				'name' => 'contact_hero_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => 'Bắt Đầu Hành Trình Kiến Tạo Tổ Ấm',
			],
			[
				'key' => 'field_ct_hero_subtitle',
				'label' => 'Sub-headline',
				'name' => 'contact_hero_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
			],

			/* ── Tab: Thông tin ── */
			[
				'key' => 'field_ct_office_name',
				'label' => 'Tên văn phòng',
				'name' => 'contact_office_name',
				'type' => 'text',
				'placeholder' => 'Văn phòng XANH Design & Build',
			],
			[
				'key' => 'field_ct_office_address',
				'label' => 'Địa chỉ chi tiết',
				'name' => 'contact_office_address',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'instructions' => 'Nếu để trống, sẽ dùng địa chỉ từ Header & Footer.',
			],
			[
				'key' => 'field_ct_phone_2',
				'label' => 'Số điện thoại phụ',
				'name' => 'contact_phone_2',
				'type' => 'text',
				'placeholder' => '028 1234 5678',
				'instructions' => 'Số điện thoại bàn văn phòng (nếu có).',
			],

			/* ── Tab: Bản đồ ── */
			[
				'key' => 'field_ct_map_embed',
				'label' => 'Google Maps Embed URL',
				'name' => 'contact_map_embed',
				'type' => 'url',
				'instructions' => 'URL iframe embed từ Google Maps. Ví dụ: https://www.google.com/maps/embed?pb=...',
				'placeholder' => 'https://www.google.com/maps/embed?pb=...',
			],
			[
				'key' => 'field_ct_map_link',
				'label' => 'Google Maps Link',
				'name' => 'contact_map_link',
				'type' => 'url',
				'instructions' => 'Link mở Google Maps khi click "Xem bản đồ".',
				'placeholder' => 'https://maps.google.com/?q=...',
			],

			/* ── Tab: Giờ làm việc ── */
			[
				'key' => 'field_ct_working_hours',
				'label' => 'Giờ làm việc',
				'name' => 'contact_working_hours',
				'type' => 'repeater',
				'min' => 0,
				'max' => 4,
				'layout' => 'table',
				'button_label' => 'Thêm dòng',
				'instructions' => 'Ví dụ: "Thứ 2 – Thứ 6" → "8:00 – 17:30"',
				'sub_fields' => [
					['key' => 'field_ct_hours_day', 'label' => 'Ngày', 'name' => 'day', 'type' => 'text', 'placeholder' => 'Thứ 2 – Thứ 6', 'wrapper' => ['width' => '50']],
					['key' => 'field_ct_hours_time', 'label' => 'Giờ', 'name' => 'time', 'type' => 'text', 'placeholder' => '8:00 – 17:30', 'wrapper' => ['width' => '50']],
				],
			],

			/* ══════ TAB 5: SEO & Scripts ══════ */
			[
				'key' => 'field_tab_seo',
				'label' => 'SEO & Scripts',
				'type' => 'tab',
				'placement' => 'left',
			],

			/* ── Section: Meta mặc định ── */
			[
				'key' => 'field_seo_default_title',
				'label' => 'Title mặc định',
				'name' => 'seo_default_title',
				'type' => 'text',
				'instructions' => 'Title SEO mặc định cho các trang chưa có title riêng.',
				'placeholder' => 'XANH Design & Build — Kiến Tạo Tổ Ấm Bình Yên',
			],
			[
				'key' => 'field_seo_default_desc',
				'label' => 'Meta Description mặc định',
				'name' => 'seo_default_desc',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'instructions' => 'Tối đa 160 ký tự. Áp dụng cho trang không có description riêng.',
				'placeholder' => 'XANH Design & Build — thiết kế và xây dựng nhà ở bền vững, minh bạch từ viên gạch đầu tiên.',
				'maxlength' => 160,
			],
			[
				'key' => 'field_seo_og_image',
				'label' => 'Ảnh OG mặc định',
				'name' => 'seo_og_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Ảnh Open Graph mặc định khi chia sẻ lên Facebook/Zalo (1200×630px).',
				'mime_types' => 'jpg,jpeg,png,webp',
			],

			/* ── Tab: Scripts ── */
			[
				'key' => 'field_seo_head_scripts',
				'label' => 'Scripts trong &lt;head&gt;',
				'name' => 'seo_head_scripts',
				'type' => 'textarea',
				'rows' => 8,
				'instructions' => 'Mã theo dõi chèn vào &lt;head&gt; (Google Tag Manager, Meta Pixel…). Bao gồm thẻ &lt;script&gt;.',
				'placeholder' => '<!-- Google Tag Manager -->',
			],
			[
				'key' => 'field_seo_body_scripts',
				'label' => 'Scripts trước &lt;/body&gt;',
				'name' => 'seo_body_scripts',
				'type' => 'textarea',
				'rows' => 8,
				'instructions' => 'Mã chèn trước thẻ đóng &lt;/body&gt; (chat widgets, analytics…). Bao gồm thẻ &lt;script&gt;.',
				'placeholder' => '<!-- Google Tag Manager (noscript) -->',
			],
			[
				'key' => 'field_seo_ga_id',
				'label' => 'Google Analytics ID',
				'name' => 'seo_ga_id',
				'type' => 'text',
				'placeholder' => 'G-XXXXXXXXXX',
				'instructions' => 'Google Analytics 4 Measurement ID. Để trống nếu dùng GTM.',
			],
		],
		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'xanh-settings',
				],
			],
		],
		'style' => 'default',
		'position' => 'normal',
		'label_placement' => 'top',
		'menu_order' => 0,
		'active' => true,
	]);
}
add_action('acf/init', 'xanh_register_settings_fields');
