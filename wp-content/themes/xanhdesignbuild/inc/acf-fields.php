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
		'menu_slug' => 'xanh-settings',
		'capability' => 'edit_posts',
		'redirect' => false,
		'icon_url' => 'dashicons-admin-site-alt3',
		'position' => 2,

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
				'key' => 'field_hp_hero_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'hero_eyebrow',
				'type' => 'text',
				'instructions' => 'Dòng text nhỏ lẻ phía trên Headline.',
				'placeholder' => 'Kiến Tạo Tổ Ấm Bình Yên',
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
				'type' => 'text',
				'instructions' => 'URL hoặc anchor (#empathy). Để trống = mặc định #empathy.',
				'placeholder' => '#empathy',
			],
			[
				'key' => 'field_hp_hero_video_url',
				'label' => 'Video URL',
				'name' => 'hero_video_url',
				'type' => 'url',
				'instructions' => 'URL video YouTube/Vimeo. Nếu nhập sẽ hiện nút "Xem Video".',
				'placeholder' => 'https://www.youtube.com/watch?v=...',
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
				'key' => 'field_hp_services_more_url',
				'label' => 'URL nút "Xem Thêm Dịch Vụ"',
				'name' => 'services_more_url',
				'type' => 'text',
				'instructions' => 'Link nút xem thêm dịch vụ. Mặc định: /dich-vu/',
				'placeholder' => '/dich-vu/',
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
				'type' => 'text',
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
				'type' => 'text',
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
						'type' => 'text',
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
				'type' => 'text',
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
						'type' => 'text',
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
				'type' => 'text',
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
				'type' => 'text',
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
				'type' => 'text',
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

			/* ══════ TAB 1: Header & Footer ══════ */
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

			/* ── Section: Header CTA ── */
			[
				'key' => 'field_hf_header_cta_text',
				'label' => 'Header CTA — Text',
				'name' => 'xanh_header_cta_text',
				'type' => 'text',
				'instructions' => 'Nội dung nút CTA trên header. Để trống = mặc định "Đặt Lịch Tư Vấn".',
				'placeholder' => 'Đặt Lịch Tư Vấn',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_hf_header_cta_url',
				'label' => 'Header CTA — URL',
				'name' => 'xanh_header_cta_url',
				'type' => 'text',
				'instructions' => 'URL nút CTA trên header. Để trống = mặc định trang Liên Hệ.',
				'placeholder' => '/lien-he/',
				'wrapper' => ['width' => '50'],
			],

			/* ── Section: Preloader ── */
			[
				'key' => 'field_hf_preloader_enabled',
				'label' => 'Preloader — Bật/Tắt',
				'name' => 'xanh_preloader_enabled',
				'type' => 'true_false',
				'default_value' => 1,
				'ui' => 1,
				'ui_on_text' => 'Bật',
				'ui_off_text' => 'Tắt',
				'instructions' => 'Bật hiệu ứng SVG logo animation khi tải trang (1 lần/session). Tự động ẩn trên trang chi tiết bài viết/dự án/dịch vụ.',
			],
			[
				'key' => 'field_hf_preloader_theme',
				'label' => 'Preloader — Theme',
				'name' => 'xanh_preloader_theme',
				'type' => 'button_group',
				'choices' => [
					'dark'  => '🌲 Dark (Nền xanh)',
					'light' => '☀️ Light (Nền trắng)',
				],
				'default_value' => 'dark',
				'layout' => 'horizontal',
				'instructions' => 'Dark = nền primary + logo trắng. Light = nền trắng + logo xanh.',
				'conditional_logic' => [
					[['field' => 'field_hf_preloader_enabled', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_hf_preloader_mode',
				'label' => 'Preloader — Chế độ hiển thị',
				'name' => 'xanh_preloader_mode',
				'type' => 'button_group',
				'choices' => [
					'session'  => '🔁 1 lần / session',
					'per-page' => '📄 Mỗi trang 1 lần',
					'always'   => '♾️ Luôn hiện mọi trang',
				],
				'default_value' => 'per-page',
				'layout' => 'horizontal',
				'instructions' => 'Session = hiện 1 lần duy nhất. Mỗi trang = hiện 1 lần cho mỗi trang khác nhau. Luôn hiện = hiệu ứng chạy mỗi lần tải. Đóng trình duyệt = reset lại tất cả.',
				'conditional_logic' => [
					[['field' => 'field_hf_preloader_enabled', 'operator' => '==', 'value' => '1']],
				],
			],

			[
				'key' => 'field_hf_hotline',
				'label' => 'Hotline',
				'name' => 'xanh_hotline',
				'type' => 'text',
				'placeholder' => '0978.303.025',
				'instructions' => 'Số điện thoại chính — hiển thị ở header, footer, contact.',
			],
			[
				'key' => 'field_hf_email',
				'label' => 'Email',
				'name' => 'xanh_email',
				'type' => 'email',
				'placeholder' => 'contact@xanhdesignbuild.vn',
			],
			[
				'key' => 'field_hf_address',
				'label' => 'Địa chỉ',
				'name' => 'xanh_address',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => '49 Nguyễn Tất Thành, Phường Phước Long, Tỉnh Khánh Hòa, Việt Nam',
			],

			/* ── Tab: Mạng xã hội ── */
			[
				'key' => 'field_hf_facebook',
				'label' => 'Facebook URL',
				'name' => 'xanh_facebook',
				'type' => 'text',
				'placeholder' => 'https://facebook.com/xanhdesignbuild',
			],
			[
				'key' => 'field_hf_instagram',
				'label' => 'Instagram URL',
				'name' => 'xanh_instagram',
				'type' => 'text',
				'placeholder' => 'https://instagram.com/xanhdesignbuild',
			],
			[
				'key' => 'field_hf_youtube',
				'label' => 'YouTube URL',
				'name' => 'xanh_youtube',
				'type' => 'text',
				'placeholder' => 'https://youtube.com/@xanhdesignbuild',
			],
			[
				'key' => 'field_hf_zalo',
				'label' => 'Zalo OA URL',
				'name' => 'xanh_zalo_oa',
				'type' => 'text',
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
			[
				'key' => 'field_hf_footer_copyright',
				'label' => 'Footer Copyright',
				'name' => 'xanh_footer_copyright',
				'type' => 'text',
				'instructions' => 'Text copyright footer. Để trống = mặc định "© {năm} {tên site}. All rights reserved."',
				'placeholder' => '© 2026 XANH Design & Build. All rights reserved.',
			],
			[
				'key' => 'field_hf_legal_privacy_url',
				'label' => 'Chính sách bảo mật — URL',
				'name' => 'xanh_legal_privacy_url',
				'type' => 'text',
				'instructions' => 'URL trang chính sách bảo mật. Để trống = mặc định /chinh-sach-bao-mat/.',
				'placeholder' => '/chinh-sach-bao-mat/',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_hf_legal_terms_url',
				'label' => 'Điều khoản sử dụng — URL',
				'name' => 'xanh_legal_terms_url',
				'type' => 'text',
				'instructions' => 'URL trang điều khoản sử dụng. Để trống = mặc định /dieu-khoan-su-dung/.',
				'placeholder' => '/dieu-khoan-su-dung/',
				'wrapper' => ['width' => '50'],
			],

			/* ══════ TAB 2: SEO & Scripts ══════ */
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
			/* ══════ TAB 3: Trang Dự Án ══════ */
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
				'type' => 'text',
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
				'type' => 'text',
				'instructions' => 'URL Zalo OA hoặc link liên hệ.',
				'wrapper' => ['width' => '50'],
			],


			/* ══════ TAB 3b: Dịch Vụ — CTA ══════ */
			[
				'key' => 'field_tab_services',
				'label' => 'Dịch Vụ',
				'type' => 'tab',
				'placement' => 'left',
			],

			/* ── Accordion: Services CTA ── */
			[
				'key' => 'field_services_acc_cta',
				'label' => 'CTA Trang Dịch Vụ',
				'type' => 'accordion',
				'open' => 1,
				'multi_expand' => 1,
			],
			[
				'key' => 'field_services_cta_title',
				'label' => 'Tiêu đề CTA',
				'name' => 'services_cta_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => 'Bạn Đã Sẵn Sàng Kiến Tạo Không Gian Sống Mơ Ước?',
				'instructions' => 'Cho phép HTML (br, em, strong).',
			],
			[
				'key' => 'field_services_cta_subtitle',
				'label' => 'Subtitle CTA',
				'name' => 'services_cta_subtitle',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => 'Liên hệ ngay để được tư vấn miễn phí.',
			],
			[
				'key' => 'field_services_cta_bg_image',
				'label' => 'Ảnh nền CTA',
				'name' => 'services_cta_bg_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
			],
			[
				'key' => 'field_services_cta_btn1_text',
				'label' => 'Nút chính — Text',
				'name' => 'services_cta_btn1_text',
				'type' => 'text',
				'placeholder' => 'Nhận Tư Vấn Miễn Phí',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_services_cta_btn1_url',
				'label' => 'Nút chính — URL',
				'name' => 'services_cta_btn1_url',
				'type' => 'text',
				'placeholder' => '/lien-he/',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_services_cta_btn2_text',
				'label' => 'Nút phụ — Text',
				'name' => 'services_cta_btn2_text',
				'type' => 'text',
				'placeholder' => 'Xem Dự Án Thực Tế',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_services_cta_btn2_url',
				'label' => 'Nút phụ — URL',
				'name' => 'services_cta_btn2_url',
				'type' => 'text',
				'placeholder' => '/du-an/',
				'wrapper' => ['width' => '50'],
			],

			/* ── Accordion: End ── */
			[
				'key' => 'field_services_acc_end',
				'label' => 'Accordion End',
				'type' => 'accordion',
				'endpoint' => 1,
			],

			/* ══════ TAB 4: Blog ══════ */
			[
				'key' => 'field_tab_blog',
				'label' => 'Blog',
				'type' => 'tab',
				'placement' => 'left',
			],

			/* ── Accordion: Hero ── */
			[
				'key' => 'field_blog_acc_hero',
				'label' => 'Hero',
				'type' => 'accordion',
				'open' => 1,
				'multi_expand' => 1,
			],
			[
				'key' => 'field_blog_hero_image',
				'label' => 'Ảnh nền Hero',
				'name' => 'blog_hero_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'mime_types' => 'jpg,jpeg,png,webp',
				'instructions' => 'Kích thước tối ưu: 1920×1080px. Format: WebP hoặc JPG.',
			],
			[
				'key' => 'field_blog_hero_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'blog_hero_eyebrow',
				'type' => 'text',
				'placeholder' => 'Blog — Cẩm Nang & Tin Tức',
			],
			[
				'key' => 'field_blog_hero_headline',
				'label' => 'Headline',
				'name' => 'blog_hero_headline',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
				'placeholder' => 'Cẩm Nang Xây Dựng &<br class="hidden sm:block" /> Không Gian Sống Bền Vững.',
			],
			[
				'key' => 'field_blog_hero_subtitle',
				'label' => 'Subtitle',
				'name' => 'blog_hero_subtitle',
				'type' => 'textarea',
				'rows' => 2,
				'placeholder' => 'Trở thành chuyên gia cho chính ngôi nhà của bạn — kinh nghiệm thực chiến, vật liệu bền vững, xu hướng thiết kế mới nhất.',
			],

			/* ── Accordion: Lead Magnet ── */
			[
				'key' => 'field_blog_acc_lm',
				'label' => 'Lead Magnet',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
			],
			[
				'key' => 'field_blog_lm_headline',
				'label' => 'Headline',
				'name' => 'blog_lm_headline',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng, &lt;em&gt;…&lt;/em&gt; cho chữ nhấn beige.',
				'placeholder' => 'Xây Nhà Lần Đầu?<br /><em>Đừng Bỏ Qua Cuốn Cẩm Nang Này.</em>',
			],
			[
				'key' => 'field_blog_lm_subtext',
				'label' => 'Subtext',
				'name' => 'blog_lm_subtext',
				'type' => 'textarea',
				'rows' => 2,
				'instructions' => 'Dùng &lt;strong&gt;…&lt;/strong&gt; cho chữ in đậm.',
				'placeholder' => 'Ebook: <strong>"Bí Quyết Xây Nhà Không Phát Sinh Chi Phí & Tối Ưu Vận Hành"</strong>',
			],
			[
				'key' => 'field_blog_lm_book_title',
				'label' => 'Tên sách (bìa)',
				'name' => 'blog_lm_book_title',
				'type' => 'text',
				'placeholder' => 'Bí Quyết Xây Nhà Không Phát Sinh',
			],
			[
				'key' => 'field_blog_lm_trust_text',
				'label' => 'Cam kết bảo mật',
				'name' => 'blog_lm_trust_text',
				'type' => 'text',
				'placeholder' => 'Không spam — XANH cam kết bảo mật thông tin của bạn.',
			],

			/* ── Accordion: Inline CTA Banner ── */
			[
				'key' => 'field_blog_acc_inline_cta',
				'label' => 'Inline CTA Banner',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
			],
			[
				'key' => 'field_blog_show_inline_cta',
				'label' => 'Hiển thị Inline CTA trong bài viết',
				'name' => 'blog_show_inline_cta',
				'type' => 'true_false',
				'default_value' => 1,
				'ui' => 1,
				'instructions' => 'Bật để hiển thị khối CTA giữa bài viết trên mọi post.',
			],
			[
				'key' => 'field_blog_inline_cta_title',
				'label' => 'Tiêu đề',
				'name' => 'blog_inline_cta_title',
				'type' => 'text',
				'placeholder' => 'Bạn đang gặp khó khăn trong việc tính toán dự toán dự án?',
				'conditional_logic' => [
					[['field' => 'field_blog_show_inline_cta', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_blog_inline_cta_subtitle',
				'label' => 'Mô tả ngắn',
				'name' => 'blog_inline_cta_subtitle',
				'type' => 'textarea',
				'rows' => 2,
				'placeholder' => 'Nhận báo giá thiết kế chi tiết với sai số dưới 5% từ đội ngũ KTS XANH ngay hôm nay.',
				'conditional_logic' => [
					[['field' => 'field_blog_show_inline_cta', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_blog_inline_cta_btn_text',
				'label' => 'Nút CTA — Text',
				'name' => 'blog_inline_cta_btn_text',
				'type' => 'text',
				'placeholder' => 'Tính Dự Toán',
				'wrapper' => ['width' => '50'],
				'conditional_logic' => [
					[['field' => 'field_blog_show_inline_cta', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_blog_inline_cta_btn_url',
				'label' => 'Nút CTA — URL',
				'name' => 'blog_inline_cta_btn_url',
				'type' => 'text',
				'placeholder' => '/lien-he/',
				'wrapper' => ['width' => '50'],
				'conditional_logic' => [
					[['field' => 'field_blog_show_inline_cta', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_blog_inline_cta_icon',
				'label' => 'Lucide Icon (nền)',
				'name' => 'blog_inline_cta_icon',
				'type' => 'text',
				'placeholder' => 'calculator',
				'instructions' => 'Tên icon Lucide hiển thị nền mờ trang trí. Mặc định: calculator.',
				'conditional_logic' => [
					[['field' => 'field_blog_show_inline_cta', 'operator' => '==', 'value' => '1']],
				],
			],

			/* ── Accordion: Sidebar & Pagination ── */
			[
				'key' => 'field_blog_acc_sidebar',
				'label' => 'Sidebar & Pagination',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
			],
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


			/* ── Accordion: CTA Cuối Bài Viết ── */
			[
				'key' => 'field_blog_acc_detail_cta',
				'label' => 'CTA Cuối Bài Viết',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
			],
			[
				'key' => 'field_blog_detail_cta_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'blog_detail_cta_eyebrow',
				'type' => 'text',
				'placeholder' => 'Tư Vấn Miễn Phí',
				'instructions' => 'Dòng chữ nhỏ phía trên tiêu đề CTA cuối bài viết.',
			],
			[
				'key' => 'field_blog_detail_cta_headline',
				'label' => 'Tiêu đề',
				'name' => 'blog_detail_cta_headline',
				'type' => 'text',
				'placeholder' => 'Bạn Đang Lên Kế Hoạch Xây Nhà?',
			],
			[
				'key' => 'field_blog_detail_cta_description',
				'label' => 'Mô tả',
				'name' => 'blog_detail_cta_description',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'placeholder' => 'Để lại thông tin, chuyên gia XANH sẽ liên hệ tư vấn và lập bảng khái toán chi tiết cho dự án của bạn — hoàn toàn miễn phí.',
				'instructions' => 'Cho phép HTML đơn giản (strong, em).',
			],
			[
				'key' => 'field_blog_detail_cta_trust',
				'label' => 'Trust Text',
				'name' => 'blog_detail_cta_trust',
				'type' => 'text',
				'placeholder' => 'Bảo mật thông tin. XANH cam kết không spam.',
				'instructions' => 'Dòng nhỏ dưới form — tạo sự tin tưởng.',
			],

			/* ── Accordion: End ── */
			[
				'key' => 'field_blog_acc_end',
				'label' => 'Accordion End',
				'type' => 'accordion',
				'endpoint' => 1,
			],

			/* ══════ TAB 5: Liên Hệ ══════ */
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
				'placeholder' => '49 Nguyễn Tất Thành, Phường Phước Long, Tỉnh Khánh Hòa, Việt Nam',
				'instructions' => 'Nếu để trống, sẽ dùng địa chỉ từ Header & Footer.',
			],
			[
				'key' => 'field_ct_phone_2',
				'label' => 'Số điện thoại phụ',
				'name' => 'contact_phone_2',
				'type' => 'text',
				'placeholder' => '0978.303.025',
				'instructions' => 'Số điện thoại bàn văn phòng (nếu có).',
			],

			/* ── Tab: Bản đồ ── */
			[
				'key' => 'field_ct_map_embed',
				'label' => 'Google Maps Embed URL',
				'name' => 'contact_map_embed',
				'type' => 'text',
				'instructions' => 'URL iframe embed từ Google Maps. Ví dụ: https://www.google.com/maps/embed?pb=...',
				'placeholder' => 'https://www.google.com/maps/embed?pb=...',
			],
			[
				'key' => 'field_ct_map_link',
				'label' => 'Google Maps Link',
				'name' => 'contact_map_link',
				'type' => 'text',
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

/**
 * ─────────────────────────────────────────────────────────
 * ACF Field Group — Project Detail (single-xanh_project).
 *
 * Per-post fields for portfolio detail page sections.
 * Each section has a True/False toggle to show/hide.
 * ─────────────────────────────────────────────────────────
 */
function xanh_register_project_detail_fields()
{
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key' => 'group_project_detail',
		'title' => 'Chi Tiết Dự Án — Nội dung các section',
		'fields' => [

			/* ══════════════ Tab: Card Info (Archive Grid) ══════════════ */
			[
				'key' => 'field_pd_tab_card',
				'label' => 'Card Info',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_pd_project_location',
				'label' => 'Vị trí',
				'name' => 'project_location',
				'type' => 'text',
				'placeholder' => 'Quận 2, TP.HCM',
				'instructions' => 'Hiển thị trên card dự án (archive grid).',
			],
			[
				'key' => 'field_pd_project_area',
				'label' => 'Diện tích',
				'name' => 'project_area',
				'type' => 'text',
				'placeholder' => '320 m²',
			],
			[
				'key' => 'field_pd_project_duration',
				'label' => 'Thời gian',
				'name' => 'project_duration',
				'type' => 'text',
				'placeholder' => '8 tháng',
			],
			[
				'key' => 'field_pd_project_service',
				'label' => 'Dịch vụ liên quan',
				'name' => 'project_service',
				'type' => 'post_object',
				'post_type' => ['xanh_service'],
				'return_format' => 'object',
				'allow_null' => 1,
				'ui' => 1,
				'instructions' => 'Chọn dịch vụ mà dự án này thuộc về. Tên dịch vụ + icon sẽ hiển thị trên card dự án.',
			],
			[
				'key' => 'field_pd_project_tagline',
				'label' => 'Tagline (Card)',
				'name' => 'project_tagline',
				'type' => 'text',
				'placeholder' => 'Hoàn thiện sát 3D 98% ⎮ 0% Phát sinh',
				'instructions' => 'Mô tả ngắn trên card. Để trống = mặc định.',
			],

			/* ══════════════ Tab: Hero (D1+D2) ══════════════ */
			[
				'key' => 'field_pd_tab_hero',
				'label' => 'Hero',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_pd_hero_image',
				'label' => 'Ảnh Hero',
				'name' => 'pd_hero_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Ảnh nền hero full-width (1920×1080px). Nếu trống dùng Featured Image.',
			],
			[
				'key' => 'field_pd_eyebrow',
				'label' => 'Eyebrow (Loại hình)',
				'name' => 'pd_eyebrow',
				'type' => 'text',
				'placeholder' => 'Biệt Thự — Modern Tropical',
				'instructions' => 'Loại hình dự án hiển thị dưới title.',
			],
			[
				'key' => 'field_pd_tagline',
				'label' => 'Tagline',
				'name' => 'pd_tagline',
				'type' => 'text',
				'placeholder' => 'Hoàn thiện sát 3D 98%  |  0% Phát sinh chi phí  |  Quận 2, TP.HCM',
				'instructions' => 'Dòng thông tin ngắn dưới eyebrow.',
			],

			/* ══════════════ Tab: Stats (D3) ══════════════ */
			[
				'key' => 'field_pd_tab_stats',
				'label' => 'Stats Bar',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_pd_show_stats',
				'label' => 'Hiển thị Stats Bar',
				'name' => 'show_stats',
				'type' => 'true_false',
				'default_value' => 1,
				'ui' => 1,
			],
			[
				'key' => 'field_pd_stats',
				'label' => 'Thống Số',
				'name' => 'pd_stats',
				'type' => 'repeater',
				'min' => 0,
				'max' => 6,
				'layout' => 'table',
				'button_label' => 'Thêm Thống Số',
				'conditional_logic' => [
					[['field' => 'field_pd_show_stats', 'operator' => '==', 'value' => '1']],
				],
				'sub_fields' => [
					[
						'key' => 'field_pd_stat_icon',
						'label' => 'Lucide Icon',
						'name' => 'icon',
						'type' => 'text',
						'placeholder' => 'map-pin',
						'wrapper' => ['width' => '20'],
					],
					[
						'key' => 'field_pd_stat_value',
						'label' => 'Giá trị',
						'name' => 'value',
						'type' => 'text',
						'placeholder' => '120',
						'wrapper' => ['width' => '20'],
					],
					[
						'key' => 'field_pd_stat_unit',
						'label' => 'Đơn vị',
						'name' => 'unit',
						'type' => 'text',
						'placeholder' => 'm²',
						'wrapper' => ['width' => '15'],
					],
					[
						'key' => 'field_pd_stat_label',
						'label' => 'Nhãn',
						'name' => 'label',
						'type' => 'text',
						'placeholder' => 'Diện Tích',
						'wrapper' => ['width' => '25'],
					],
					[
						'key' => 'field_pd_stat_is_counter',
						'label' => 'Counter?',
						'name' => 'is_counter',
						'type' => 'true_false',
						'ui' => 1,
						'default_value' => 0,
						'instructions' => 'Bật nếu muốn hiệu ứng đếm số.',
						'wrapper' => ['width' => '10'],
					],
					[
						'key' => 'field_pd_stat_decimals',
						'label' => 'Số thập phân',
						'name' => 'decimals',
						'type' => 'number',
						'placeholder' => '0',
						'wrapper' => ['width' => '10'],
					],
				],
			],
			[
				'key' => 'field_pd_stats_highlight_value',
				'label' => 'Highlight Value',
				'name' => 'pd_stats_highlight_value',
				'type' => 'text',
				'placeholder' => '0%',
				'instructions' => 'Con số nổi bật (VD: 0%). Để trống = ẩn.',
				'conditional_logic' => [
					[['field' => 'field_pd_show_stats', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_stats_highlight_text',
				'label' => 'Highlight Text',
				'name' => 'pd_stats_highlight_text',
				'type' => 'text',
				'placeholder' => 'Phát sinh chi phí',
				'conditional_logic' => [
					[['field' => 'field_pd_show_stats', 'operator' => '==', 'value' => '1']],
				],
			],

			/* ══════════════ Tab: Story (D4) ══════════════ */
			[
				'key' => 'field_pd_tab_story',
				'label' => 'Câu Chuyện',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_pd_show_story',
				'label' => 'Hiển thị Câu Chuyện Dự Án',
				'name' => 'show_story',
				'type' => 'true_false',
				'default_value' => 1,
				'ui' => 1,
			],
			[
				'key' => 'field_pd_story_eyebrow',
				'label' => 'Section Eyebrow',
				'name' => 'pd_story_eyebrow',
				'type' => 'text',
				'placeholder' => 'Câu Chuyện Dự Án',
				'conditional_logic' => [
					[['field' => 'field_pd_show_story', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_story_title',
				'label' => 'Section Title',
				'name' => 'pd_story_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => 'Mỗi Không Gian Đều Bắt Đầu<br>Từ Một Bài Toán Thực Tế',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
				'conditional_logic' => [
					[['field' => 'field_pd_show_story', 'operator' => '==', 'value' => '1']],
				],
			],
			// Challenge panel
			[
				'key' => 'field_pd_challenge_title',
				'label' => 'Bài Toán — Tiêu đề',
				'name' => 'pd_challenge_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => 'Khu Đất Hướng Tây<br>Nắng Nóng Gay Gắt',
				'conditional_logic' => [
					[['field' => 'field_pd_show_story', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_challenge_body',
				'label' => 'Bài Toán — Nội dung',
				'name' => 'pd_challenge_body',
				'type' => 'textarea',
				'rows' => 4,
				'placeholder' => 'Anh Hoàng tìm đến XANH với khu đất hướng Tây tại trung tâm Nha Trang...',
				'conditional_logic' => [
					[['field' => 'field_pd_show_story', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_context_heading',
				'label' => 'Bối Cảnh — Heading',
				'name' => 'pd_context_heading',
				'type' => 'text',
				'placeholder' => 'Bối Cảnh & Giới Hạn',
				'conditional_logic' => [
					[['field' => 'field_pd_show_story', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_context_body',
				'label' => 'Bối Cảnh — Nội dung',
				'name' => 'pd_context_body',
				'type' => 'textarea',
				'rows' => 3,
				'placeholder' => 'Mặt tiền 6m hướng chính Tây, diện tích 120m²...',
				'conditional_logic' => [
					[['field' => 'field_pd_show_story', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_client_quote',
				'label' => 'Trích dẫn khách hàng',
				'name' => 'pd_client_quote',
				'type' => 'textarea',
				'rows' => 3,
				'placeholder' => '"Tôi đã đi xem 5–6 đơn vị, ai cũng nói \'hướng Tây không vấn đề gì.\'..."',
				'conditional_logic' => [
					[['field' => 'field_pd_show_story', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_client_name',
				'label' => 'Tên khách hàng',
				'name' => 'pd_client_name',
				'type' => 'text',
				'placeholder' => '— Anh Hoàng, Gia chủ',
				'conditional_logic' => [
					[['field' => 'field_pd_show_story', 'operator' => '==', 'value' => '1']],
				],
			],
			// Solution panel
			[
				'key' => 'field_pd_solution_title',
				'label' => 'Lời Giải — Tiêu đề',
				'name' => 'pd_solution_title',
				'type' => 'text',
				'placeholder' => 'Thiết Kế & Giải Pháp Kỹ Thuật',
				'conditional_logic' => [
					[['field' => 'field_pd_show_story', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_solutions',
				'label' => 'Giải pháp',
				'name' => 'pd_solutions',
				'type' => 'repeater',
				'min' => 0,
				'max' => 6,
				'layout' => 'block',
				'button_label' => 'Thêm Giải Pháp',
				'conditional_logic' => [
					[['field' => 'field_pd_show_story', 'operator' => '==', 'value' => '1']],
				],
				'sub_fields' => [
					[
						'key' => 'field_pd_sol_icon',
						'label' => 'Lucide Icon',
						'name' => 'icon',
						'type' => 'text',
						'placeholder' => 'grid-3x3',
						'wrapper' => ['width' => '25'],
					],
					[
						'key' => 'field_pd_sol_title',
						'label' => 'Tiêu đề',
						'name' => 'title',
						'type' => 'text',
						'placeholder' => 'Gạch Bông Gió Mặt Tiền',
						'wrapper' => ['width' => '35'],
					],
					[
						'key' => 'field_pd_sol_desc',
						'label' => 'Mô tả',
						'name' => 'desc',
						'type' => 'textarea',
						'rows' => 2,
						'placeholder' => 'Hệ gạch bông gió mặt tiền phía Tây vừa tạo thẩm mỹ...',
					],
				],
			],

			/* ══════════════ Tab: Before/After (D5) ══════════════ */
			[
				'key' => 'field_pd_tab_ba',
				'label' => 'Before/After',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_pd_show_before_after',
				'label' => 'Hiển thị Before/After',
				'name' => 'show_before_after',
				'type' => 'true_false',
				'default_value' => 1,
				'ui' => 1,
			],
			[
				'key' => 'field_pd_ba_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'pd_ba_eyebrow',
				'type' => 'text',
				'placeholder' => 'Concept 3D vs Thực Tế',
				'conditional_logic' => [
					[['field' => 'field_pd_show_before_after', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_ba_title',
				'label' => 'Title',
				'name' => 'pd_ba_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => 'Từ Bản Vẽ Đến<br>Ngôi Nhà Thực',
				'conditional_logic' => [
					[['field' => 'field_pd_show_before_after', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_ba_slides',
				'label' => 'Slides So Sánh',
				'name' => 'pd_ba_slides',
				'type' => 'repeater',
				'min' => 0,
				'max' => 10,
				'layout' => 'block',
				'button_label' => 'Thêm Slide',
				'conditional_logic' => [
					[['field' => 'field_pd_show_before_after', 'operator' => '==', 'value' => '1']],
				],
				'sub_fields' => [
					[
						'key' => 'field_pd_ba_before_img',
						'label' => 'Ảnh Before (Concept 3D)',
						'name' => 'before_img',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'medium',
						'wrapper' => ['width' => '33'],
					],
					[
						'key' => 'field_pd_ba_after_img',
						'label' => 'Ảnh After (Thực tế)',
						'name' => 'after_img',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'medium',
						'wrapper' => ['width' => '33'],
					],
					[
						'key' => 'field_pd_ba_thumb_img',
						'label' => 'Thumbnail',
						'name' => 'thumb_img',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'thumbnail',
						'instructions' => 'Ảnh nhỏ cho thanh thumbnail. Để trống = dùng ảnh After.',
						'wrapper' => ['width' => '34'],
					],
					[
						'key' => 'field_pd_ba_room_name',
						'label' => 'Tên không gian',
						'name' => 'room_name',
						'type' => 'text',
						'placeholder' => 'Phòng Khách Tầng 1',
					],
				],
			],

			/* ══════════════ Tab: Video (D5b) ══════════════ */
			[
				'key' => 'field_pd_tab_video',
				'label' => 'Video',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_pd_show_video',
				'label' => 'Hiển thị Video',
				'name' => 'show_video',
				'type' => 'true_false',
				'default_value' => 1,
				'ui' => 1,
			],
			[
				'key' => 'field_pd_video_title',
				'label' => 'Title',
				'name' => 'pd_video_title',
				'type' => 'text',
				'placeholder' => 'Không Gian Sống Động',
				'conditional_logic' => [
					[['field' => 'field_pd_show_video', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_video_url',
				'label' => 'YouTube URL',
				'name' => 'pd_video_url',
				'type' => 'url',
				'placeholder' => 'https://www.youtube.com/watch?v=...',
				'conditional_logic' => [
					[['field' => 'field_pd_show_video', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_video_subtext',
				'label' => 'Subtext',
				'name' => 'pd_video_subtext',
				'type' => 'text',
				'placeholder' => 'Thiết Kế Nội Thất Cao Cấp | Luxury Interior Design',
				'conditional_logic' => [
					[['field' => 'field_pd_show_video', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_video_bg',
				'label' => 'Ảnh nền Video',
				'name' => 'pd_video_bg',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'conditional_logic' => [
					[['field' => 'field_pd_show_video', 'operator' => '==', 'value' => '1']],
				],
			],

			/* ══════════════ Tab: Gallery (D7) ══════════════ */
			[
				'key' => 'field_pd_tab_gallery',
				'label' => 'Gallery',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_pd_show_gallery',
				'label' => 'Hiển thị Gallery',
				'name' => 'show_gallery',
				'type' => 'true_false',
				'default_value' => 1,
				'ui' => 1,
			],
			[
				'key' => 'field_pd_gallery_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'pd_gallery_eyebrow',
				'type' => 'text',
				'placeholder' => 'Thực Tế Thi Công',
				'conditional_logic' => [
					[['field' => 'field_pd_show_gallery', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_gallery_title',
				'label' => 'Title',
				'name' => 'pd_gallery_title',
				'type' => 'text',
				'placeholder' => 'Thành Quả Trọn Vẹn',
				'conditional_logic' => [
					[['field' => 'field_pd_show_gallery', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_gallery_subtitle',
				'label' => 'Subtitle',
				'name' => 'pd_gallery_subtitle',
				'type' => 'textarea',
				'rows' => 2,
				'placeholder' => 'Từng góc không gian được ghi lại sau khi hoàn thiện...',
				'conditional_logic' => [
					[['field' => 'field_pd_show_gallery', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_gallery_images',
				'label' => 'Ảnh Gallery',
				'name' => 'pd_gallery_images',
				'type' => 'repeater',
				'min' => 0,
				'max' => 20,
				'layout' => 'block',
				'button_label' => 'Thêm Ảnh',
				'conditional_logic' => [
					[['field' => 'field_pd_show_gallery', 'operator' => '==', 'value' => '1']],
				],
				'sub_fields' => [
					[
						'key' => 'field_pd_gal_image',
						'label' => 'Ảnh',
						'name' => 'image',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'medium',
						'wrapper' => ['width' => '40'],
					],
					[
						'key' => 'field_pd_gal_caption',
						'label' => 'Caption',
						'name' => 'caption',
						'type' => 'text',
						'placeholder' => 'Phòng Khách',
						'wrapper' => ['width' => '30'],
					],
					[
						'key' => 'field_pd_gal_layout',
						'label' => 'Layout',
						'name' => 'layout',
						'type' => 'select',
						'choices' => [
							'normal' => 'Normal',
							'wide' => 'Wide (2 cột)',
							'tall' => 'Tall (2 hàng)',
						],
						'default_value' => 'normal',
						'wrapper' => ['width' => '30'],
					],
				],
			],

			/* ══════════════ Tab: Testimonial (D8) ══════════════ */
			[
				'key' => 'field_pd_tab_testimonial',
				'label' => 'Cảm Nhận',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_pd_show_testimonial',
				'label' => 'Hiển thị Cảm Nhận Chủ Nhà',
				'name' => 'show_testimonial',
				'type' => 'true_false',
				'default_value' => 1,
				'ui' => 1,
			],
			[
				'key' => 'field_pd_testi_image',
				'label' => 'Ảnh khách hàng',
				'name' => 'pd_testi_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Ảnh portrait, tỉ lệ 4:5 (720×900px).',
				'conditional_logic' => [
					[['field' => 'field_pd_show_testimonial', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_testi_quote',
				'label' => 'Lời nhận xét',
				'name' => 'pd_testi_quote',
				'type' => 'textarea',
				'rows' => 4,
				'placeholder' => 'Ở được gần một năm rồi, điều mình thích nhất là...',
				'conditional_logic' => [
					[['field' => 'field_pd_show_testimonial', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_testi_name',
				'label' => 'Tên khách hàng',
				'name' => 'pd_testi_name',
				'type' => 'text',
				'placeholder' => 'Anh Hoàng & Chị Lan',
				'conditional_logic' => [
					[['field' => 'field_pd_show_testimonial', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_testi_role',
				'label' => 'Vai trò / Vị trí',
				'name' => 'pd_testi_role',
				'type' => 'text',
				'placeholder' => 'Chủ đầu tư · Villa Thảo Điền, Nha Trang',
				'conditional_logic' => [
					[['field' => 'field_pd_show_testimonial', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_testi_rating',
				'label' => 'Đánh giá (sao)',
				'name' => 'pd_testi_rating',
				'type' => 'number',
				'min' => 1,
				'max' => 5,
				'default_value' => 5,
				'conditional_logic' => [
					[['field' => 'field_pd_show_testimonial', 'operator' => '==', 'value' => '1']],
				],
			],

			/* ══════════════ Tab: CTA (D10) ══════════════ */
			[
				'key' => 'field_pd_tab_cta',
				'label' => 'CTA',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_pd_show_cta',
				'label' => 'Hiển thị CTA',
				'name' => 'show_cta',
				'type' => 'true_false',
				'default_value' => 1,
				'ui' => 1,
			],
			[
				'key' => 'field_pd_cta_headline',
				'label' => 'Headline',
				'name' => 'pd_cta_headline',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'placeholder' => 'Bạn cũng muốn một không gian sống<br><em>trọn vẹn và minh bạch</em> như thế này?',
				'instructions' => 'Dùng &lt;br&gt; và &lt;em&gt; để format.',
				'conditional_logic' => [
					[['field' => 'field_pd_show_cta', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_cta_btn1_text',
				'label' => 'Nút chính — Text',
				'name' => 'pd_cta_btn1_text',
				'type' => 'text',
				'placeholder' => 'Sử Dụng Công Cụ Dự Toán Xanh',
				'wrapper' => ['width' => '50'],
				'conditional_logic' => [
					[['field' => 'field_pd_show_cta', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_cta_btn1_url',
				'label' => 'Nút chính — URL',
				'name' => 'pd_cta_btn1_url',
				'type' => 'text',
				'placeholder' => 'https://',
				'wrapper' => ['width' => '50'],
				'conditional_logic' => [
					[['field' => 'field_pd_show_cta', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_cta_btn2_text',
				'label' => 'Nút phụ — Text',
				'name' => 'pd_cta_btn2_text',
				'type' => 'text',
				'placeholder' => 'Trao Đổi Riêng Với Chuyên Gia',
				'wrapper' => ['width' => '50'],
				'conditional_logic' => [
					[['field' => 'field_pd_show_cta', 'operator' => '==', 'value' => '1']],
				],
			],
			[
				'key' => 'field_pd_cta_btn2_url',
				'label' => 'Nút phụ — URL',
				'name' => 'pd_cta_btn2_url',
				'type' => 'text',
				'placeholder' => 'https://zalo.me/',
				'wrapper' => ['width' => '50'],
				'conditional_logic' => [
					[['field' => 'field_pd_show_cta', 'operator' => '==', 'value' => '1']],
				],
			],

			/* ══════════════ Tab: Related (D9) ══════════════ */
			[
				'key' => 'field_pd_tab_related',
				'label' => 'Liên Quan',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_pd_show_related',
				'label' => 'Hiển thị Dự Án Liên Quan',
				'name' => 'show_related',
				'type' => 'true_false',
				'default_value' => 1,
				'ui' => 1,
				'instructions' => 'Tự động lấy 3 dự án cùng danh mục.',
			],
		],
		'location' => [
			[
				[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'xanh_project',
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
add_action('acf/init', 'xanh_register_project_detail_fields');

/**
 * ─────────────────────────────────────────────────────────
 * ACF Field Groups — Contact Page (page-contact.php).
 *
 * Tabs: Hero | Contact Block | FAQ.
 * ─────────────────────────────────────────────────────────
 */
function xanh_register_contact_page_fields()
{
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key' => 'group_contact_page',
		'title' => 'Liên Hệ — Nội dung trang Contact',
		'fields' => [

			/* ══════════════ Tab: Hero ══════════════ */
			[
				'key' => 'field_ct_tab_hero',
				'label' => 'Hero',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_ct_hero_image',
				'label' => 'Ảnh nền Hero',
				'name' => 'contact_hero_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Kích thước tối ưu: 1920×960px. Format: WebP hoặc JPG.',
				'mime_types' => 'jpg,jpeg,png,webp',
			],
			[
				'key' => 'field_ct_hero_title',
				'label' => 'Tiêu đề Hero',
				'name' => 'contact_hero_title',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng, &lt;em&gt; cho chữ nhấn màu beige.',
				'placeholder' => 'Mọi Công Trình Bền Vững Đều Bắt Đầu<br>Từ Một <em>Cuộc Trò Chuyện.</em>',
			],
			[
				'key' => 'field_ct_hero_subtitle',
				'label' => 'Mô tả Hero',
				'name' => 'contact_hero_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'placeholder' => 'Bạn đang ấp ủ một không gian sống mới nhưng còn nhiều trăn trở? Hãy chia sẻ với chúng tôi...',
			],

			/* ══════════════ Tab: Contact Block ══════════════ */
			[
				'key' => 'field_ct_tab_block',
				'label' => 'Contact Block',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_ct_block_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'contact_block_eyebrow',
				'type' => 'text',
				'placeholder' => 'Liên Hệ Với Chúng Tôi',
			],
			[
				'key' => 'field_ct_block_title',
				'label' => 'Tiêu đề Section',
				'name' => 'contact_block_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng, &lt;em&gt; cho chữ nhấn.',
				'placeholder' => 'Kể Cho Chúng Tôi Nghe<br>Về <em style="font-style:normal;color:var(--color-primary);">Bài Toán Của Bạn</em>',
			],
			[
				'key' => 'field_ct_form_shortcode',
				'label' => 'Form Shortcode (Fluent Form)',
				'name' => 'contact_form_shortcode',
				'type' => 'text',
				'instructions' => 'Nhập shortcode Fluent Form. Ví dụ: [fluentform id="1"]. Để trống sẽ hiện form mặc định.',
				'placeholder' => '[fluentform id="1"]',
			],
			[
				'key' => 'field_ct_working_hours',
				'label' => 'Giờ Làm Việc',
				'name' => 'contact_working_hours',
				'type' => 'text',
				'placeholder' => 'Thứ 2 — Thứ 7: 08:00 – 17:30',
			],
			[
				'key' => 'field_ct_google_maps_url',
				'label' => 'Google Maps Embed URL',
				'name' => 'contact_google_maps_url',
				'type' => 'text',
				'instructions' => 'Lấy từ Google Maps → Chia sẻ → Nhúng bản đồ → Copy URL trong src="...".',
				'placeholder' => 'https://www.google.com/maps/embed?pb=...',
			],

			/* ══════════════ Tab: FAQ ══════════════ */
			[
				'key' => 'field_ct_tab_faq',
				'label' => 'FAQ',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_ct_faq_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'contact_faq_eyebrow',
				'type' => 'text',
				'placeholder' => 'Câu Hỏi Thường Gặp',
			],
			[
				'key' => 'field_ct_faq_title',
				'label' => 'Tiêu đề FAQ',
				'name' => 'contact_faq_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;em&gt; cho chữ nhấn.',
				'placeholder' => 'Những Điều Bạn <em style="font-style:normal;color:var(--color-primary);">Muốn Biết</em>',
			],
			[
				'key' => 'field_ct_faq_items',
				'label' => 'Câu hỏi & Trả lời',
				'name' => 'contact_faq_items',
				'type' => 'repeater',
				'instructions' => 'Thêm các câu hỏi thường gặp. Tối đa 10 câu. Dữ liệu này cũng được dùng cho FAQPage Schema (SEO).',
				'min' => 0,
				'max' => 10,
				'layout' => 'block',
				'button_label' => 'Thêm FAQ',
				'sub_fields' => [
					[
						'key' => 'field_ct_faq_question',
						'label' => 'Câu hỏi',
						'name' => 'question',
						'type' => 'text',
						'placeholder' => 'Tôi có phải trả phí cho buổi tư vấn ban đầu không?',
					],
					[
						'key' => 'field_ct_faq_answer',
						'label' => 'Trả lời',
						'name' => 'answer',
						'type' => 'textarea',
						'rows' => 3,
						'placeholder' => 'Hoàn toàn không. Buổi tư vấn đầu tiên tại XANH luôn miễn phí...',
					],
				],
			],

		],
		'location' => [
			[
				[
					'param' => 'page',
					'operator' => '==',
					'value' => 'lien-he',
				],
			],
		],
		'style' => 'default',
		'position' => 'normal',
		'label_placement' => 'top',
		'menu_order' => 0,
		'hide_on_screen' => [
			'',
			'the_content',
		],
		'active' => true,
	]);
}
add_action('acf/init', 'xanh_register_contact_page_fields');

/**
 * ─────────────────────────────────────────────────────────
 * ACF Field Groups — Service Detail Page.
 *
 * Tabs: Hero | Empathy | Features | Process | Portfolio |
 *       Testimonial | FAQ | CTA
 * Location: Page Template = page-service-detail.php
 * ─────────────────────────────────────────────────────────
 */
function xanh_register_service_detail_fields()
{
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key' => 'group_service_detail',
		'title' => 'Service Detail — Trang Chi Tiết Dịch Vụ',
		'fields' => [

			/* ── Tab: Hero ── */
			[
				'key' => 'field_sv_tab_hero',
				'label' => 'Hero',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_sv_hero_image',
				'label' => 'Ảnh nền Hero',
				'name' => 'sv_hero_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => '1920×1080px, WebP. Preload LCP.',
				'mime_types' => 'jpg,jpeg,png,webp',
			],
			[
				'key' => 'field_sv_hero_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'sv_hero_eyebrow',
				'type' => 'text',
				'placeholder' => 'Dịch Vụ — Thiết Kế Kiến Trúc & Nội Thất',
			],
			[
				'key' => 'field_sv_hero_title',
				'label' => 'H1 Title',
				'name' => 'sv_hero_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
				'placeholder' => 'Không Gian Sống Bản Sắc.<br>Hành Trình Kiến Tạo Di Sản.',
			],
			[
				'key' => 'field_sv_hero_desc',
				'label' => 'Mô tả Hero',
				'name' => 'sv_hero_desc',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'instructions' => 'Cho phép &lt;strong&gt;. Hiển thị dưới H1.',
			],
			[
				'key' => 'field_sv_hero_counters',
				'label' => 'Counter Strip',
				'name' => 'sv_hero_counters',
				'type' => 'repeater',
				'instructions' => 'Dải số liệu cuối hero (3 items).',
				'min' => 0,
				'max' => 5,
				'layout' => 'table',
				'button_label' => 'Thêm Counter',
				'sub_fields' => [
					[
						'key' => 'field_sv_counter_number',
						'label' => 'Số',
						'name' => 'number',
						'type' => 'text',
						'placeholder' => '98',
						'wrapper' => ['width' => '30'],
					],
					[
						'key' => 'field_sv_counter_suffix',
						'label' => 'Hậu tố',
						'name' => 'suffix',
						'type' => 'text',
						'placeholder' => '%',
						'wrapper' => ['width' => '20'],
					],
					[
						'key' => 'field_sv_counter_label',
						'label' => 'Label',
						'name' => 'label',
						'type' => 'text',
						'placeholder' => 'Sát 3D',
						'wrapper' => ['width' => '50'],
					],
				],
			],
			[
				'key' => 'field_sv_card_icon',
				'label' => 'Card Icon (Archive Grid)',
				'name' => 'sv_card_icon',
				'type' => 'text',
				'instructions' => 'Tên icon Lucide hiển thị trên card ở trang danh sách dịch vụ. Tra cứu: <a href="https://lucide.dev/icons" target="_blank">lucide.dev/icons</a>. Ví dụ: drafting-compass, paint-roller, hard-hat, ruler, home',
				'placeholder' => 'drafting-compass',
			],

			/* ── Tab: Empathy ── */
			[
				'key' => 'field_sv_tab_empathy',
				'label' => 'Empathy (S2)',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_sv_empathy_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'sv_empathy_eyebrow',
				'type' => 'text',
				'placeholder' => 'Sự Đồng Cảm Chuyên Sâu',
			],
			[
				'key' => 'field_sv_empathy_title',
				'label' => 'Headline (H2)',
				'name' => 'sv_empathy_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
			],
			[
				'key' => 'field_sv_empathy_body',
				'label' => 'Body (Wysiwyg)',
				'name' => 'sv_empathy_body',
				'type' => 'wysiwyg',
				'tabs' => 'all',
				'toolbar' => 'basic',
				'media_upload' => 0,
				'instructions' => 'Nội dung đồng cảm. Cho phép bold, italic, links.',
			],
			[
				'key' => 'field_sv_empathy_image',
				'label' => 'Ảnh bên phải',
				'name' => 'sv_empathy_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Tỉ lệ 4:3 (720×540px).',
			],

			/* ── Tab: Features ── */
			[
				'key' => 'field_sv_tab_features',
				'label' => 'Features (S3)',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_sv_features_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'sv_features_eyebrow',
				'type' => 'text',
				'placeholder' => 'Nền Tảng Giải Pháp',
			],
			[
				'key' => 'field_sv_features_title',
				'label' => 'Headline (H2)',
				'name' => 'sv_features_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
			],
			[
				'key' => 'field_sv_features_subtitle',
				'label' => 'Subtitle',
				'name' => 'sv_features_subtitle',
				'type' => 'textarea',
				'rows' => 2,
				'instructions' => 'Cho phép &lt;strong&gt;.',
			],
			[
				'key' => 'field_sv_features_bg_image',
				'label' => 'Ảnh nền Section',
				'name' => 'sv_features_bg_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'instructions' => 'Ảnh nền mờ phía sau section Features (opacity 20%).',
			],
			[
				'key' => 'field_sv_features_items',
				'label' => 'Feature Cards',
				'name' => 'sv_features',
				'type' => 'repeater',
				'instructions' => '6 thẻ năng lực chính.',
				'min' => 0,
				'max' => 8,
				'layout' => 'block',
				'button_label' => 'Thêm Feature',
				'sub_fields' => [
					[
						'key' => 'field_sv_feat_icon',
						'label' => 'Lucide Icon',
						'name' => 'icon',
						'type' => 'text',
						'instructions' => 'Tên icon Lucide. Tra tại <a href="https://lucide.dev/icons" target="_blank">lucide.dev</a>',
						'placeholder' => 'compass',
						'wrapper' => ['width' => '25'],
					],
					[
						'key' => 'field_sv_feat_title',
						'label' => 'Tiêu đề',
						'name' => 'title',
						'type' => 'text',
						'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
						'wrapper' => ['width' => '35'],
					],
					[
						'key' => 'field_sv_feat_desc',
						'label' => 'Mô tả',
						'name' => 'desc',
						'type' => 'textarea',
						'rows' => 2,
						'wrapper' => ['width' => '40'],
					],
				],
			],

			/* ── Tab: Process ── */
			[
				'key' => 'field_sv_tab_process',
				'label' => 'Process (S4)',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_sv_process_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'sv_process_eyebrow',
				'type' => 'text',
				'placeholder' => 'Xây Dựng Niềm Tin',
			],
			[
				'key' => 'field_sv_process_title',
				'label' => 'Headline (H2)',
				'name' => 'sv_process_title',
				'type' => 'textarea',
				'rows' => 3,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
			],
			[
				'key' => 'field_sv_process_desc',
				'label' => 'Mô tả',
				'name' => 'sv_process_desc',
				'type' => 'textarea',
				'rows' => 2,
				'instructions' => 'Cho phép &lt;strong&gt;.',
			],
			[
				'key' => 'field_sv_process_stat_number',
				'label' => 'Stat Number',
				'name' => 'sv_process_stat_number',
				'type' => 'text',
				'placeholder' => '5',
				'wrapper' => ['width' => '30'],
			],
			[
				'key' => 'field_sv_process_stat_label',
				'label' => 'Stat Label',
				'name' => 'sv_process_stat_label',
				'type' => 'text',
				'placeholder' => 'Cột Mốc Rõ Ràng',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
				'wrapper' => ['width' => '70'],
			],
			[
				'key' => 'field_sv_process_steps',
				'label' => 'Các bước',
				'name' => 'sv_process_steps',
				'type' => 'repeater',
				'instructions' => '5 bước quy trình.',
				'min' => 0,
				'max' => 8,
				'layout' => 'block',
				'button_label' => 'Thêm Bước',
				'sub_fields' => [
					[
						'key' => 'field_sv_step_title',
						'label' => 'Tiêu đề',
						'name' => 'title',
						'type' => 'text',
						'wrapper' => ['width' => '30'],
					],
					[
						'key' => 'field_sv_step_desc',
						'label' => 'Mô tả',
						'name' => 'desc',
						'type' => 'textarea',
						'rows' => 2,
						'wrapper' => ['width' => '40'],
					],
					[
						'key' => 'field_sv_step_icon1',
						'label' => 'Icon 1',
						'name' => 'icon_1',
						'type' => 'text',
						'placeholder' => 'headphones',
						'wrapper' => ['width' => '15'],
					],
					[
						'key' => 'field_sv_step_icon2',
						'label' => 'Icon 2',
						'name' => 'icon_2',
						'type' => 'text',
						'placeholder' => 'map-pin',
						'wrapper' => ['width' => '15'],
					],
					[
						'key' => 'field_sv_step_image',
						'label' => 'Ảnh minh hoạ',
						'name' => 'image',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'medium',
						'mime_types' => 'jpg,jpeg,png,webp',
						'instructions' => 'Ảnh minh hoạ cho bước quy trình (280×180px khuyến nghị).',
					],
				],
			],

			/* ── Tab: Portfolio ── */
			[
				'key' => 'field_sv_tab_portfolio',
				'label' => 'Portfolio (S5)',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_sv_portfolio_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'sv_portfolio_eyebrow',
				'type' => 'text',
				'placeholder' => 'Minh Chứng Chất Lượng',
			],
			[
				'key' => 'field_sv_portfolio_title',
				'label' => 'Headline (H2)',
				'name' => 'sv_portfolio_title',
				'type' => 'text',
				'placeholder' => 'Bộ Sưu Tập Di Sản',
			],
			[
				'key' => 'field_sv_portfolio_subtitle',
				'label' => 'Subtitle',
				'name' => 'sv_portfolio_subtitle',
				'type' => 'textarea',
				'rows' => 2,
				'instructions' => 'Cho phép &lt;strong&gt;.',
			],
			[
				'key' => 'field_sv_portfolio_mode',
				'label' => 'Chế độ chọn dự án',
				'name' => 'sv_portfolio_mode',
				'type' => 'button_group',
				'choices' => [
					'auto'     => '🔗 Tự động (theo dịch vụ)',
					'manual'   => '🎯 Chọn tay từng dự án',
					'taxonomy' => '📂 Theo danh mục dự án',
				],
				'default_value' => 'auto',
				'layout' => 'horizontal',
				'instructions' => 'Tự động = lấy dự án có gắn dịch vụ hiện tại. Chọn tay = pick từng dự án. Theo danh mục = lấy theo Loại Dự Án.',
			],
			[
				'key' => 'field_sv_related_projects',
				'label' => 'Chọn dự án',
				'name' => 'sv_related_projects',
				'type' => 'relationship',
				'post_type' => ['xanh_project'],
				'filters' => ['search'],
				'min' => 0,
				'max' => 6,
				'return_format' => 'object',
				'instructions' => 'Chọn 3-6 dự án tiêu biểu liên quan.',
				'conditional_logic' => [
					[['field' => 'field_sv_portfolio_mode', 'operator' => '==', 'value' => 'manual']],
				],
			],
			[
				'key' => 'field_sv_portfolio_category',
				'label' => 'Danh mục dự án',
				'name' => 'sv_portfolio_category',
				'type' => 'taxonomy',
				'taxonomy' => 'project_type',
				'field_type' => 'multi_select',
				'allow_null' => 1,
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'multiple' => 1,
				'instructions' => 'Chọn 1 hoặc nhiều Loại Dự Án. Hệ thống sẽ tự lấy tối đa 6 dự án mới nhất trong danh mục đã chọn.',
				'conditional_logic' => [
					[['field' => 'field_sv_portfolio_mode', 'operator' => '==', 'value' => 'taxonomy']],
				],
			],
			[
				'key' => 'field_sv_portfolio_count',
				'label' => 'Số dự án hiển thị',
				'name' => 'sv_portfolio_count',
				'type' => 'number',
				'default_value' => 6,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'instructions' => 'Số dự án tối đa hiển thị (mặc định: 6).',
				'wrapper' => ['width' => '30'],
				'conditional_logic' => [
					[['field' => 'field_sv_portfolio_mode', 'operator' => '==', 'value' => 'auto']],
					[['field' => 'field_sv_portfolio_mode', 'operator' => '==', 'value' => 'taxonomy']],
				],
			],

			/* ── Tab: Testimonial ── */
			[
				'key' => 'field_sv_tab_testimonial',
				'label' => 'Testimonial (S6)',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_sv_testi_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'sv_testi_eyebrow',
				'type' => 'text',
				'placeholder' => 'Dấu Ấn Sự Hài Lòng',
			],
			[
				'key' => 'field_sv_testi_title',
				'label' => 'Headline (H2)',
				'name' => 'sv_testi_title',
				'type' => 'text',
				'placeholder' => 'Tiếng Nói Khách Hàng',
			],
			[
				'key' => 'field_sv_testi_subtitle',
				'label' => 'Subtitle',
				'name' => 'sv_testi_subtitle',
				'type' => 'textarea',
				'rows' => 2,
				'instructions' => 'Cho phép &lt;strong&gt;.',
			],
			[
				'key' => 'field_sv_testi_bg_image',
				'label' => 'Ảnh nền section',
				'name' => 'sv_testi_bg_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
			],
			[
				'key' => 'field_sv_testimonials',
				'label' => 'Testimonials',
				'name' => 'sv_testimonials',
				'type' => 'repeater',
				'instructions' => 'Các lời chứng từ khách hàng.',
				'min' => 0,
				'max' => 6,
				'layout' => 'block',
				'button_label' => 'Thêm Testimonial',
				'sub_fields' => [
					[
						'key' => 'field_sv_testi_quote',
						'label' => 'Quote',
						'name' => 'quote',
						'type' => 'textarea',
						'rows' => 3,
					],
					[
						'key' => 'field_sv_testi_name',
						'label' => 'Tên',
						'name' => 'name',
						'type' => 'text',
						'placeholder' => 'Chị Thuỳ Linh',
						'wrapper' => ['width' => '50'],
					],
					[
						'key' => 'field_sv_testi_role',
						'label' => 'Vai trò / Dự án',
						'name' => 'role',
						'type' => 'text',
						'placeholder' => 'Khách Hàng Xác Minh ⎮ Villa Thảo Điền',
						'wrapper' => ['width' => '50'],
					],
					[
						'key' => 'field_sv_testi_avatar',
						'label' => 'Avatar',
						'name' => 'avatar',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'thumbnail',
						'instructions' => 'Ảnh tròn 72×72px.',
					],
					[
						'key' => 'field_sv_testi_image',
						'label' => 'Ảnh dự án',
						'name' => 'image',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'medium',
						'instructions' => 'Ảnh bên trái slide (optional).',
					],
				],
			],

			/* ── Tab: FAQ ── */
			[
				'key' => 'field_sv_tab_faq',
				'label' => 'FAQ (S7)',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_sv_faq_eyebrow',
				'label' => 'Eyebrow',
				'name' => 'sv_faq_eyebrow',
				'type' => 'text',
				'placeholder' => 'Giải Đáp Thắc Mắc',
			],
			[
				'key' => 'field_sv_faq_title',
				'label' => 'Headline (H2)',
				'name' => 'sv_faq_title',
				'type' => 'text',
				'instructions' => 'Cho phép &lt;em&gt;.',
				'placeholder' => 'Những Câu Hỏi Thường Gặp',
			],
			[
				'key' => 'field_sv_faq_items',
				'label' => 'FAQ Items',
				'name' => 'sv_faq_items',
				'type' => 'repeater',
				'instructions' => 'Schema FAQPage JSON-LD tự động.',
				'min' => 0,
				'max' => 10,
				'layout' => 'block',
				'button_label' => 'Thêm Câu Hỏi',
				'sub_fields' => [
					[
						'key' => 'field_sv_faq_question',
						'label' => 'Câu hỏi',
						'name' => 'question',
						'type' => 'text',
					],
					[
						'key' => 'field_sv_faq_answer',
						'label' => 'Trả lời',
						'name' => 'answer',
						'type' => 'textarea',
						'rows' => 4,
					],
				],
			],

			/* ── Tab: CTA ── */
			[
				'key' => 'field_sv_tab_cta',
				'label' => 'CTA (S8)',
				'type' => 'tab',
				'placement' => 'left',
			],
			[
				'key' => 'field_sv_cta_title',
				'label' => 'Headline',
				'name' => 'sv_cta_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => '',
				'instructions' => 'Dùng &lt;br&gt; để xuống dòng.',
			],
			[
				'key' => 'field_sv_cta_subtitle',
				'label' => 'Subtitle',
				'name' => 'sv_cta_subtitle',
				'type' => 'textarea',
				'rows' => 2,
			],
			[
				'key' => 'field_sv_cta_bg_image',
				'label' => 'Ảnh nền',
				'name' => 'sv_cta_bg_image',
				'type' => 'image',
				'return_format' => 'array',
				'preview_size' => 'medium',
			],
			[
				'key' => 'field_sv_cta_btn_text',
				'label' => 'Nút chính — Text',
				'name' => 'sv_cta_btn_text',
				'type' => 'text',
				'placeholder' => 'Khám Phá Dự Toán Của Bạn',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_sv_cta_btn_link',
				'label' => 'Nút chính — URL',
				'name' => 'sv_cta_btn_link',
				'type' => 'text',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_sv_cta_ghost_text',
				'label' => 'Nút phụ — Text',
				'name' => 'sv_cta_ghost_text',
				'type' => 'text',
				'placeholder' => 'Chat Với Kỹ Sư Trưởng',
				'wrapper' => ['width' => '50'],
			],
			[
				'key' => 'field_sv_cta_ghost_link',
				'label' => 'Nút phụ — URL',
				'name' => 'sv_cta_ghost_link',
				'type' => 'text',
				'wrapper' => ['width' => '50'],
			],
		],
		'location' => [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'xanh_service',
				],
			],
		],
		'style' => 'default',
		'position' => 'normal',
		'label_placement' => 'top',
		'menu_order' => 0,
		'hide_on_screen' => [
			'',
			'the_content',
		],
		'active' => true,
	]);
}
add_action('acf/init', 'xanh_register_service_detail_fields');

/**
 * ─────────────────────────────────────────────────────────
 * ACF Field Groups — Popup Modal (xanh_popup CPT).
 *
 * 5 Tabs: Content & Templates | Display | Trigger | Targeting | Frequency
 * ─────────────────────────────────────────────────────────
 */
function xanh_register_popup_fields()
{
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key'    => 'group_popup',
		'title'  => 'Popup Modal — Cài Đặt',
		'fields' => [

			/* ══════════════════════════════════════════
			   Tab 1: Nội Dung & Templates
			   ══════════════════════════════════════════ */
			[
				'key'       => 'field_popup_tab_content',
				'label'     => 'Nội Dung',
				'type'      => 'tab',
				'placement' => 'left',
			],
			[
				'key'           => 'field_popup_active',
				'label'         => 'Bật/Tắt Popup',
				'name'          => 'popup_active',
				'type'          => 'true_false',
				'default_value' => 1,
				'ui'            => 1,
				'ui_on_text'    => 'Active',
				'ui_off_text'   => 'Inactive',
				'instructions'  => 'Tắt nếu muốn ẩn popup tạm thời mà không cần xóa.',
			],
			[
				'key'           => 'field_popup_content_type',
				'label'         => 'Loại Nội Dung',
				'name'          => 'popup_content_type',
				'type'          => 'select',
				'instructions'  => 'Chọn loại nội dung hoặc template có sẵn.',
				'choices'       => [
					'Tùy Chỉnh'  => [
						'wysiwyg' => 'WYSIWYG Editor (Text/Ảnh tự do)',
						'image'   => 'Chỉ Hình Ảnh (Banner)',
						'html'    => 'Code HTML thuần',
					],
					'Templates Có Sẵn' => [
						'quote'   => 'Template: Báo Giá (FluentForm)',
						'ebook'   => 'Template: Ebook (Ảnh + FluentForm)',
						'video'   => 'Template: Video YouTube',
					],
				],
				'default_value' => 'wysiwyg',
				'return_format' => 'value',
				'wrapper'       => ['width' => '60'],
			],

			/* ── WYSIWYG Content ── */
			[
				'key'               => 'field_popup_wysiwyg',
				'label'             => 'Nội dung (WYSIWYG)',
				'name'              => 'popup_wysiwyg',
				'type'              => 'wysiwyg',
				'instructions'      => 'Nhập nội dung tự do: text, headings, ảnh, shortcode, v.v.',
				'tabs'              => 'all',
				'toolbar'           => 'full',
				'media_upload'      => 1,
				'conditional_logic' => [
					[['field' => 'field_popup_content_type', 'operator' => '==', 'value' => 'wysiwyg']],
				],
			],

			/* ── Image Only ── */
			[
				'key'               => 'field_popup_image',
				'label'             => 'Hình ảnh',
				'name'              => 'popup_image',
				'type'              => 'image',
				'return_format'     => 'array',
				'preview_size'      => 'medium',
				'mime_types'        => 'jpg,jpeg,png,webp,gif',
				'instructions'      => 'Upload ảnh banner/poster. Template Ebook: Bìa sách (tỉ lệ 3:4).',
				'conditional_logic' => [
					[['field' => 'field_popup_content_type', 'operator' => '==', 'value' => 'image']],
					[['field' => 'field_popup_content_type', 'operator' => '==', 'value' => 'ebook']],
				],
			],
			[
				'key'               => 'field_popup_image_link',
				'label'             => 'Link khi click ảnh',
				'name'              => 'popup_image_link',
				'type'              => 'text',
				'instructions'      => 'Tùy chọn: URL khi click vào ảnh (ví dụ: trang landing page).',
				'conditional_logic' => [
					[['field' => 'field_popup_content_type', 'operator' => '==', 'value' => 'image']],
				],
			],

			/* ── Raw HTML ── */
			[
				'key'               => 'field_popup_html',
				'label'             => 'Code HTML',
				'name'              => 'popup_html',
				'type'              => 'textarea',
				'rows'              => 12,
				'new_lines'         => '',
				'instructions'      => 'Nhập HTML/CSS/JS thuần. Shortcodes cũng hỗ trợ.',
				'conditional_logic' => [
					[['field' => 'field_popup_content_type', 'operator' => '==', 'value' => 'html']],
				],
			],

			/* ── Template fields: Quote & Ebook ── */
			[
				'key'               => 'field_popup_title',
				'label'             => 'Tiêu đề Popup',
				'name'              => 'popup_title',
				'type'              => 'text',
				'instructions'      => 'Tiêu đề hiển thị trong popup.',
				'placeholder'       => 'Nhận Báo Giá Cụ Thể',
				'conditional_logic' => [
					[['field' => 'field_popup_content_type', 'operator' => '==', 'value' => 'quote']],
					[['field' => 'field_popup_content_type', 'operator' => '==', 'value' => 'ebook']],
				],
			],
			[
				'key'               => 'field_popup_desc',
				'label'             => 'Mô tả ngắn',
				'name'              => 'popup_desc',
				'type'              => 'textarea',
				'rows'              => 3,
				'instructions'      => 'Mô tả ngắn xuất hiện dưới tiêu đề.',
				'placeholder'       => 'Nhập thông tin để nhận báo giá chi tiết từ đội ngũ XANH.',
				'conditional_logic' => [
					[['field' => 'field_popup_content_type', 'operator' => '==', 'value' => 'quote']],
					[['field' => 'field_popup_content_type', 'operator' => '==', 'value' => 'ebook']],
				],
			],
			[
				'key'               => 'field_popup_form_id',
				'label'             => 'Fluent Form ID',
				'name'              => 'popup_form_id',
				'type'              => 'number',
				'instructions'      => 'Nhập ID của Fluent Form. Xem tại Fluent Forms → All Forms → cột ID.',
				'placeholder'       => '1',
				'conditional_logic' => [
					[['field' => 'field_popup_content_type', 'operator' => '==', 'value' => 'quote']],
					[['field' => 'field_popup_content_type', 'operator' => '==', 'value' => 'ebook']],
				],
			],

			/* ── Template fields: Video ── */
			[
				'key'               => 'field_popup_video_url',
				'label'             => 'YouTube Video URL',
				'name'              => 'popup_video_url',
				'type'              => 'url',
				'instructions'      => 'Dán link YouTube. Ví dụ: https://www.youtube.com/watch?v=abc123',
				'placeholder'       => 'https://www.youtube.com/watch?v=...',
				'conditional_logic' => [
					[['field' => 'field_popup_content_type', 'operator' => '==', 'value' => 'video']],
				],
			],

			/* ══════════════════════════════════════════
			   Tab 2: Hiển Thị (Display)
			   ══════════════════════════════════════════ */
			[
				'key'       => 'field_popup_tab_display',
				'label'     => 'Hiển Thị',
				'type'      => 'tab',
				'placement' => 'left',
			],
			[
				'key'           => 'field_popup_size',
				'label'         => 'Kích Thước Popup',
				'name'          => 'popup_size',
				'type'          => 'select',
				'instructions'  => 'small = 480px · medium = 720px (Báo Giá) · large = 960px (Ebook 2 cột) · fullscreen = 90%/1200px (Video)',
				'choices'       => [
					'small'      => 'Small (480px)',
					'medium'     => 'Medium (720px)',
					'large'      => 'Large (960px)',
					'fullscreen' => 'Fullscreen (max 1200px)',
				],
				'default_value' => 'medium',
				'wrapper'       => ['width' => '33'],
			],
			[
				'key'           => 'field_popup_overlay',
				'label'         => 'Overlay Style',
				'name'          => 'popup_overlay',
				'type'          => 'select',
				'instructions'  => 'Phông nền mờ phía sau popup.',
				'choices'       => [
					'dark'  => 'Đen trong suốt (88%)',
					'light' => 'Trắng trong suốt (85%)',
					'blur'  => 'Blur (mờ nền)',
				],
				'default_value' => 'dark',
				'wrapper'       => ['width' => '33'],
			],
			[
				'key'           => 'field_popup_animation',
				'label'         => 'Hiệu Ứng Mở',
				'name'          => 'popup_animation',
				'type'          => 'select',
				'choices'       => [
					'fade-scale'  => 'Fade + Scale (mặc định)',
					'slide-up'    => 'Slide Up',
					'slide-right' => 'Slide Right',
				],
				'default_value' => 'fade-scale',
				'wrapper'       => ['width' => '34'],
			],
			[
				'key'           => 'field_popup_bg_color',
				'label'         => 'Màu Nền Popup',
				'name'          => 'popup_bg_color',
				'type'          => 'select',
				'instructions'  => 'Chọn màu nền cho nội dung popup.',
				'choices'       => [
					'light'   => 'Sáng (Trắng)',
					'primary' => 'Primary (Xanh đậm)',
				],
				'default_value' => 'light',
				'wrapper'       => ['width' => '33'],
			],

			/* ══════════════════════════════════════════
			   Tab 3: Kích Hoạt (Trigger)
			   ══════════════════════════════════════════ */
			[
				'key'       => 'field_popup_tab_trigger',
				'label'     => 'Kích Hoạt',
				'type'      => 'tab',
				'placement' => 'left',
			],
			[
				'key'           => 'field_popup_trigger',
				'label'         => 'Kiểu Kích Hoạt',
				'name'          => 'popup_trigger',
				'type'          => 'select',
				'instructions'  => 'Chọn sự kiện sẽ kích hoạt popup.',
				'choices'       => [
					'click'       => 'Click vào nút / link',
					'delay'       => 'Tự động sau X giây',
					'scroll'      => 'Cuộn trang đến X%',
					'exit_intent' => 'Exit Intent (ý định thoát)',
				],
				'default_value' => 'click',
			],
			[
				'key'               => 'field_popup_click_guide',
				'label'             => 'Hướng dẫn gắn nút',
				'type'              => 'message',
				'message'           => '<div style="background:#f0f7f4;border-left:4px solid #14513D;padding:12px 16px;border-radius:4px;margin:4px 0;">
					<strong>Cách gắn popup vào một nút bất kỳ:</strong><br>
					Chỉnh URL/Link của nút đó thành: <code style="background:#e8e8e8;padding:2px 8px;border-radius:3px;font-size:14px;">#xanh-popup-<strong>[POST_ID của popup này]</strong></code><br>
					<small>Ví dụ: <code>#xanh-popup-123</code> — Xem Popup ID ở thanh trình duyệt hoặc cột "Popup ID" trong danh sách.</small>
				</div>',
				'conditional_logic' => [
					[['field' => 'field_popup_trigger', 'operator' => '==', 'value' => 'click']],
				],
			],
			[
				'key'               => 'field_popup_click_selector',
				'label'             => 'CSS Selector bổ sung',
				'name'              => 'popup_click_selector',
				'type'              => 'text',
				'instructions'      => 'Tùy chọn thêm: Nhập CSS class hoặc ID để kích hoạt. Ví dụ: .btn-quote hoặc #open-form',
				'placeholder'       => '.btn-quote',
				'conditional_logic' => [
					[['field' => 'field_popup_trigger', 'operator' => '==', 'value' => 'click']],
				],
			],
			[
				'key'               => 'field_popup_delay_seconds',
				'label'             => 'Thời gian delay (giây)',
				'name'              => 'popup_delay_seconds',
				'type'              => 'number',
				'instructions'      => 'Popup tự hiện sau bao nhiêu giây khi trang load xong.',
				'default_value'     => 5,
				'min'               => 1,
				'max'               => 120,
				'conditional_logic' => [
					[['field' => 'field_popup_trigger', 'operator' => '==', 'value' => 'delay']],
				],
			],
			[
				'key'               => 'field_popup_scroll_percent',
				'label'             => 'Scroll Depth (%)',
				'name'              => 'popup_scroll_percent',
				'type'              => 'number',
				'instructions'      => 'Popup tự hiện khi người dùng cuộn đến bao nhiêu % trang.',
				'default_value'     => 50,
				'min'               => 1,
				'max'               => 100,
				'append'            => '%',
				'conditional_logic' => [
					[['field' => 'field_popup_trigger', 'operator' => '==', 'value' => 'scroll']],
				],
			],

			/* ══════════════════════════════════════════
			   Tab 4: Điều Kiện Hiển Thị (Targeting)
			   ══════════════════════════════════════════ */
			[
				'key'       => 'field_popup_tab_targeting',
				'label'     => 'Hiển Thị',
				'type'      => 'tab',
				'placement' => 'left',
			],
			[
				'key'           => 'field_popup_pages',
				'label'         => 'Hiện trên trang',
				'name'          => 'popup_pages',
				'type'          => 'select',
				'instructions'  => 'Popup sẽ hiển thị ở những trang nào.',
				'choices'       => [
					'all'      => 'Toàn bộ website',
					'home'     => 'Chỉ Trang chủ',
					'blog'     => 'Trang Blog & Bài viết',
					'services' => 'Trang Dịch vụ (archive + single)',
					'custom'   => 'Chọn trang cụ thể...',
				],
				'default_value' => 'all',
			],
			[
				'key'               => 'field_popup_custom_pages',
				'label'             => 'Trang cụ thể',
				'name'              => 'popup_custom_pages',
				'type'              => 'post_object',
				'instructions'      => 'Chọn các trang/bài viết cụ thể.',
				'post_type'         => ['page', 'post', 'xanh_service', 'xanh_project'],
				'multiple'          => 1,
				'return_format'     => 'id',
				'conditional_logic' => [
					[['field' => 'field_popup_pages', 'operator' => '==', 'value' => 'custom']],
				],
			],
			[
				'key'           => 'field_popup_hide_mobile',
				'label'         => 'Ẩn trên Mobile',
				'name'          => 'popup_hide_mobile',
				'type'          => 'true_false',
				'default_value' => 0,
				'ui'            => 1,
				'instructions'  => 'Bật ON để ẩn popup trên thiết bị di động (< 768px).',
			],

			/* ══════════════════════════════════════════
			   Tab 5: Tần Suất (Frequency)
			   ══════════════════════════════════════════ */
			[
				'key'       => 'field_popup_tab_frequency',
				'label'     => 'Tần Suất',
				'type'      => 'tab',
				'placement' => 'left',
			],
			[
				'key'           => 'field_popup_frequency',
				'label'         => 'Tần suất hiển thị',
				'name'          => 'popup_frequency',
				'type'          => 'select',
				'instructions'  => 'Sau khi đóng, bao lâu thì popup hiện lại cho cùng người dùng.',
				'choices'       => [
					'always'       => 'Luôn hiển thị (mỗi lần load trang)',
					'once_session' => 'Chỉ 1 lần/phiên truy cập',
					'once_day'     => 'Chỉ 1 lần/ngày',
					'once_week'    => 'Chỉ 1 lần/tuần',
				],
				'default_value' => 'once_session',
			],
		],
		'location' => [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'xanh_popup',
				],
			],
		],
		'style'           => 'default',
		'position'        => 'normal',
		'label_placement' => 'top',
		'menu_order'      => 0,
		'hide_on_screen'  => ['the_content', 'excerpt', 'featured_image', 'categories', 'tags'],
		'active'          => true,
	]);
}
add_action('acf/init', 'xanh_register_popup_fields');
