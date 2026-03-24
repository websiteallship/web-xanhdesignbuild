<?php
/**
 * Custom Functions — xanh_get_*() data helpers.
 *
 * Business logic lives HERE, not in templates.
 * Templates call these helpers and render the returned data.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get company hotline from ACF Options.
 *
 * @return string Phone number or empty string.
 */
function xanh_get_hotline() {
	return xanh_get_option( 'xanh_hotline' );
}

/**
 * Get company email from ACF Options.
 *
 * @return string Email or empty string.
 */
function xanh_get_email() {
	return xanh_get_option( 'xanh_email' );
}

/**
 * Get company address from ACF Options.
 *
 * @return string Address or empty string.
 */
function xanh_get_address() {
	return xanh_get_option( 'xanh_address' );
}

/**
 * Get social media links from ACF Options.
 *
 * @return array Associative array of social links.
 */
function xanh_get_social_links() {
	return [
		'facebook'  => xanh_get_option( 'xanh_facebook' ),
		'instagram' => xanh_get_option( 'xanh_instagram' ),
		'youtube'   => xanh_get_option( 'xanh_youtube' ),
		'zalo'      => xanh_get_option( 'xanh_zalo_oa' ),
	];
}

/**
 * Get featured projects (Relationship field from Homepage).
 *
 * Uses transient caching to avoid repeated queries.
 *
 * @return array Array of WP_Post objects or empty array.
 */
function xanh_get_featured_projects() {
	if ( ! function_exists( 'get_field' ) ) {
		return [];
	}

	$cached = get_transient( 'xanh_featured_projects' );
	if ( false !== $cached ) {
		return $cached;
	}

	$front_page_id = (int) get_option( 'page_on_front' );
	$projects      = get_field( 'featured_projects', $front_page_id );

	if ( ! $projects ) {
		$projects = [];
	}

	set_transient( 'xanh_featured_projects', $projects, HOUR_IN_SECONDS );

	return $projects;
}

/**
 * Get latest blog posts.
 *
 * @param  int $count Number of posts to fetch.
 * @return WP_Post[] Array of post objects.
 */
function xanh_get_latest_posts( $count = 3 ) {
	return get_posts( [
		'post_type'      => 'post',
		'posts_per_page' => absint( $count ),
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	] );
}

/**
 * Clear featured projects transient when a project is saved.
 *
 * @param  int $post_id Post ID.
 * @return void
 */
function xanh_clear_projects_cache( $post_id ) {
	$type = get_post_type( $post_id );
	if ( 'xanh_project' === $type || 'page' === $type ) {
		delete_transient( 'xanh_featured_projects' );
	}
	// Clear blog transient when any post is saved.
	if ( 'post' === $type ) {
		delete_transient( 'xanh_home_blog_latest' );
	}
	// Clear popup count transient when popup is saved/updated.
	if ( 'xanh_popup' === $type ) {
		delete_transient( 'xanh_popup_count' );
	}
}
add_action( 'save_post', 'xanh_clear_projects_cache' );

/**
 * Auto-assign default placeholder thumbnail on save.
 *
 * When a post, page, or CPT is saved/published without a featured image,
 * this function automatically sets placeholder-project.png as the thumbnail.
 * The image is uploaded to the Media Library only once and reused via option.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an existing post being updated.
 */
function xanh_auto_assign_default_thumbnail( $post_id, $post, $update ) {
	// Skip autosave, revisions, and non-public post types.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	// Only apply to published posts.
	if ( 'publish' !== $post->post_status ) {
		return;
	}

	// Skip post types that don't support thumbnails.
	if ( ! post_type_supports( $post->post_type, 'thumbnail' ) ) {
		return;
	}

	// Skip popups — they don't need thumbnails.
	if ( 'xanh_popup' === $post->post_type ) {
		return;
	}

	// Already has a thumbnail — do nothing.
	if ( has_post_thumbnail( $post_id ) ) {
		return;
	}

	// Get or create the placeholder attachment.
	$placeholder_id = xanh_get_placeholder_attachment_id();

	if ( $placeholder_id ) {
		set_post_thumbnail( $post_id, $placeholder_id );
	}
}
add_action( 'save_post', 'xanh_auto_assign_default_thumbnail', 20, 3 );

/**
 * Get (or create) the Media Library attachment ID for the placeholder image.
 *
 * Uploads placeholder-project.png to the Media Library once,
 * stores the resulting attachment ID in wp_options for reuse.
 *
 * @return int Attachment ID, or 0 on failure.
 */
function xanh_get_placeholder_attachment_id() {
	$option_key = 'xanh_placeholder_attachment_id';
	$att_id     = (int) get_option( $option_key, 0 );

	// Verify the attachment still exists.
	if ( $att_id && get_post( $att_id ) ) {
		return $att_id;
	}

	// Source file in the theme.
	$source = XANH_THEME_DIR . '/assets/images/placeholder-project.png';

	if ( ! file_exists( $source ) ) {
		return 0;
	}

	// Copy to uploads directory.
	$upload_dir = wp_upload_dir();
	$filename   = 'placeholder-project.png';
	$dest       = $upload_dir['path'] . '/' . $filename;

	// Avoid duplicate uploads — check if file already exists in uploads.
	if ( ! file_exists( $dest ) ) {
		copy( $source, $dest );
	}

	// Require media handling functions.
	if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	$filetype = wp_check_filetype( $filename, null );

	$attachment = [
		'guid'           => $upload_dir['url'] . '/' . $filename,
		'post_mime_type' => $filetype['type'],
		'post_title'     => 'XANH Placeholder',
		'post_content'   => '',
		'post_status'    => 'inherit',
	];

	$att_id = wp_insert_attachment( $attachment, $dest );

	if ( ! is_wp_error( $att_id ) && $att_id ) {
		$metadata = wp_generate_attachment_metadata( $att_id, $dest );
		wp_update_attachment_metadata( $att_id, $metadata );
		update_option( $option_key, $att_id );
	}

	return (int) $att_id;
}

/**
 * ── Inline CTA Banner — Dynamic Content Injection ──
 *
 * Injects CTA banners into single blog post content based on word count.
 * Rules:
 *   - < 1000 words → 0 banners
 *   - 1000–1999    → 1 banner (at ~midpoint)
 *   - 2000–2999    → 2 banners (midpoint of each 1000-word segment)
 *   - …up to max 5 banners.
 *
 * Safe placement: banners only appear between two <p> tags,
 * never directly after headings or before images/blockquotes/lists/tables.
 *
 * @param  string $content Post content HTML.
 * @return string Modified content with injected banners.
 */
function xanh_inject_inline_cta_banners( $content ) {
	// Only on single blog posts, in the main query.
	if ( ! is_singular( 'post' ) || ! is_main_query() ) {
		return $content;
	}

	// Check ACF toggle.
	if ( ! function_exists( 'get_field' ) ) {
		return $content;
	}
	$show_inline = get_field( 'blog_show_inline_cta', 'option' );
	if ( ! $show_inline ) {
		return $content;
	}

	// ── Count words (Vietnamese-aware: split by whitespace) ──
	$stripped   = wp_strip_all_tags( $content );
	$word_count = count( preg_split( '/\s+/u', $stripped, -1, PREG_SPLIT_NO_EMPTY ) );

	$banner_count = (int) floor( $word_count / 1000 );
	if ( $banner_count < 1 ) {
		return $content;
	}
	$banner_count = min( $banner_count, 5 );

	// ── Build banner HTML ──
	$banner_html = xanh_build_inline_cta_html();

	// ── Split content into top-level blocks ──
	// Match every top-level HTML tag (self-closing or paired).
	$blocks = preg_split(
		'/(<\/?(?:p|h[1-6]|figure|img|blockquote|ul|ol|table|div|hr|pre|aside|section|details|dl)[^>]*>)/i',
		$content,
		-1,
		PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
	);

	// ── Rebuild into logical elements & track word positions ──
	$elements   = [];   // { html, word_start, word_end, tag }
	$word_cursor = 0;
	$buffer      = '';
	$current_tag = '';
	$depth       = 0;

	foreach ( $blocks as $part ) {
		// Detect opening/closing tags.
		if ( preg_match( '/^<(\/?)(\w+)/i', $part, $m ) ) {
			$is_close = ( '/' === $m[1] );
			$tag_name = strtolower( $m[2] );

			// Self-closing tags (hr, img).
			$self_closing = in_array( $tag_name, [ 'hr', 'img' ], true );

			if ( $self_closing ) {
				if ( 0 === $depth ) {
					// Flush buffer first.
					if ( '' !== $buffer ) {
						$elements[] = [
							'html'       => $buffer,
							'word_start' => $word_cursor,
							'word_end'   => $word_cursor,
							'tag'        => '',
						];
						$buffer = '';
					}
					$elements[] = [
						'html'       => $part,
						'word_start' => $word_cursor,
						'word_end'   => $word_cursor,
						'tag'        => $tag_name,
					];
					continue;
				}
				$buffer .= $part;
				continue;
			}

			if ( ! $is_close ) {
				if ( 0 === $depth ) {
					// Flush loose text.
					if ( '' !== $buffer ) {
						$elements[] = [
							'html'       => $buffer,
							'word_start' => $word_cursor,
							'word_end'   => $word_cursor,
							'tag'        => '',
						];
						$buffer = '';
					}
					$current_tag = $tag_name;
				}
				$depth++;
				$buffer .= $part;
			} else {
				$depth = max( 0, $depth - 1 );
				$buffer .= $part;

				if ( 0 === $depth ) {
					// Complete element — count words.
					$text       = wp_strip_all_tags( $buffer );
					$words      = preg_split( '/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY );
					$wc         = count( $words );
					$word_start = $word_cursor;
					$word_cursor += $wc;

					$elements[] = [
						'html'       => $buffer,
						'word_start' => $word_start,
						'word_end'   => $word_cursor,
						'tag'        => $current_tag,
					];
					$buffer      = '';
					$current_tag = '';
				}
			}
		} else {
			$buffer .= $part;
		}
	}
	// Flush remaining.
	if ( '' !== $buffer ) {
		$elements[] = [
			'html'       => $buffer,
			'word_start' => $word_cursor,
			'word_end'   => $word_cursor,
			'tag'        => '',
		];
	}

	// ── Forbidden tags for neighbors ──
	$forbidden_before = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ];
	$forbidden_after  = [ 'img', 'figure', 'blockquote', 'ul', 'ol', 'table', 'hr', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ];

	// ── Calculate ideal insertion word positions ──
	$target_positions = [];
	for ( $i = 0; $i < $banner_count; $i++ ) {
		// Midpoint of each 1000-word segment.
		$target_positions[] = ( $i * 1000 ) + 500;
	}

	// ── Find safe insertion indices ──
	$insert_indices = [];
	$el_count       = count( $elements );

	foreach ( $target_positions as $target_word ) {
		$best_idx  = -1;
		$best_dist = PHP_INT_MAX;

		for ( $j = 0; $j < $el_count - 1; $j++ ) {
			// Skip if already used.
			if ( in_array( $j, $insert_indices, true ) ) {
				continue;
			}

			$current = $elements[ $j ];
			$next    = $elements[ $j + 1 ];

			// Must be a <p> element.
			if ( 'p' !== $current['tag'] ) {
				continue;
			}

			// The next element must also be safe (not forbidden).
			if ( in_array( $next['tag'], $forbidden_after, true ) ) {
				continue;
			}

			// Prev element (current) must not be a heading (it's a <p> so this is OK).
			// But check: the previous element before current must not make this awkward.
			if ( $j > 0 && in_array( $elements[ $j ]['tag'], $forbidden_before, true ) ) {
				continue;
			}

			// Distance from target word position.
			$dist = abs( $current['word_end'] - $target_word );
			if ( $dist < $best_dist ) {
				$best_dist = $dist;
				$best_idx  = $j;
			}
		}

		if ( $best_idx >= 0 ) {
			$insert_indices[] = $best_idx;
		}
	}

	// ── No valid positions found ──
	if ( empty( $insert_indices ) ) {
		return $content;
	}

	// Sort descending so insertions don't shift indices.
	rsort( $insert_indices );

	// ── Rebuild content with banners ──
	foreach ( $insert_indices as $idx ) {
		// Insert banner HTML after elements[$idx].
		array_splice( $elements, $idx + 1, 0, [
			[
				'html'       => $banner_html,
				'word_start' => 0,
				'word_end'   => 0,
				'tag'        => 'inline-banner',
			],
		] );
	}

	$output = '';
	foreach ( $elements as $el ) {
		$output .= $el['html'];
	}

	return $output;
}
add_filter( 'the_content', 'xanh_inject_inline_cta_banners', 20 );

/**
 * Build the inline CTA banner HTML markup.
 *
 * Reads values from ACF Options with fallback defaults.
 *
 * @return string Banner HTML.
 */
function xanh_build_inline_cta_html() {
	$cta_title    = get_field( 'blog_inline_cta_title', 'option' ) ?: 'Bạn đang gặp khó khăn trong việc tính toán dự toán dự án?';
	$cta_subtitle = get_field( 'blog_inline_cta_subtitle', 'option' ) ?: 'Nhận báo giá thiết kế chi tiết với sai số dưới 5% từ đội ngũ KTS XANH ngay hôm nay.';
	$cta_btn_text = get_field( 'blog_inline_cta_btn_text', 'option' ) ?: 'Tính Dự Toán';
	$cta_btn_url  = get_field( 'blog_inline_cta_btn_url', 'option' ) ?: '/lien-he/';
	$cta_icon     = get_field( 'blog_inline_cta_icon', 'option' ) ?: 'calculator';

	ob_start();
	?>
	<div class="inline-banner my-10 bg-primary/5 border border-primary/20 p-6 md:p-8 flex flex-col md:flex-row items-center gap-6 justify-between relative overflow-hidden">
		<!-- Decorative bg icon -->
		<div class="absolute -right-10 -bottom-10 opacity-5" aria-hidden="true">
			<i data-lucide="<?php echo esc_attr( $cta_icon ); ?>" class="w-40 h-40"></i>
		</div>
		<div class="relative z-10 flex-1">
			<h4 class="font-heading font-bold text-xl text-primary mb-2"><?php echo esc_html( $cta_title ); ?></h4>
			<p class="text-sm text-dark/70 mb-0"><?php echo esc_html( $cta_subtitle ); ?></p>
		</div>
		<a href="<?php echo esc_url( $cta_btn_url ); ?>" class="btn btn--primary group relative z-10 whitespace-nowrap">
			<span><?php echo esc_html( $cta_btn_text ); ?></span>
			<i data-lucide="arrow-right" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5"></i>
		</a>
	</div>
	<?php
	return ob_get_clean();
}

