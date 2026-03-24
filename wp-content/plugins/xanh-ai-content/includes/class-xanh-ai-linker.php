<?php
/**
 * Internal Linking + RankMath — dynamic link injection + related posts.
 *
 * Provides 3 link layers:
 *   1. Service pages (priority, sales conversion)
 *   2. Utility pages  (CTA & brand awareness)
 *   3. Related posts  (dynamic, same category + keyword scoring)
 *
 * @package Xanh_AI_Content
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xanh_AI_Linker {

	/*--------------------------------------------------------------
	 * Link Target Registry
	 *------------------------------------------------------------*/

	/**
	 * Get the default built-in link targets (8 targets, 2 groups).
	 *
	 * @return array<string, array{url: string, anchor: string, group: string, enabled: bool}>
	 */
	public static function get_default_link_targets(): array {
		return [
			// Service pages (priority — sales conversion).
			'svc-design'       => [
				'url'     => '/dich-vu/thiet-ke-kien-truc/',
				'anchor'  => 'Thiết kế kiến trúc & nội thất trọn gói',
				'group'   => 'service',
				'enabled' => true,
			],
			'svc-construction' => [
				'url'     => '/dich-vu/thi-cong-xay-dung/',
				'anchor'  => 'Thi công xây dựng trọn gói',
				'group'   => 'service',
				'enabled' => true,
			],
			'svc-renovation'   => [
				'url'     => '/dich-vu/cai-tao-nang-cap/',
				'anchor'  => 'Cải tạo & nâng cấp công trình',
				'group'   => 'service',
				'enabled' => true,
			],
			'svc-furniture'    => [
				'url'     => '/dich-vu/thi-cong-noi-that/',
				'anchor'  => 'Sản xuất & thi công nội thất',
				'group'   => 'service',
				'enabled' => true,
			],
			// Utility pages (CTA & brand).
			'contact'          => [
				'url'     => '/lien-he/',
				'anchor'  => 'Đặt lịch tư vấn riêng',
				'group'   => 'utility',
				'enabled' => true,
			],
			'portfolio'        => [
				'url'     => '/du-an/',
				'anchor'  => 'Xem các tác phẩm XANH',
				'group'   => 'utility',
				'enabled' => true,
			],
			'estimator'        => [
				'url'     => '/du-toan/',
				'anchor'  => 'Khám phá dự toán của bạn',
				'group'   => 'utility',
				'enabled' => true,
			],
			'about'            => [
				'url'     => '/gioi-thieu/',
				'anchor'  => 'Tìm hiểu về XANH',
				'group'   => 'utility',
				'enabled' => true,
			],
		];
	}

	/**
	 * Get the active link map (saved overrides or defaults).
	 *
	 * Admin can edit targets via Settings → Internal Links.
	 * Stored in `wp_options` as `xanh_ai_link_targets` (JSON).
	 *
	 * @return array<string, array>
	 */
	public static function get_link_map(): array {
		$saved = get_option( 'xanh_ai_link_targets', [] );

		if ( empty( $saved ) || ! is_array( $saved ) ) {
			$saved = self::get_default_link_targets();
		}

		return apply_filters( 'xanh_ai_link_map', $saved );
	}

	/**
	 * Get all link keys grouped for UI display.
	 *
	 * @return array{ service: array, utility: array }
	 */
	public static function get_grouped_link_keys(): array {
		$map    = self::get_link_map();
		$groups = [ 'service' => [], 'utility' => [] ];

		foreach ( $map as $key => $config ) {
			$group = $config['group'] ?? 'utility';
			if ( ! isset( $groups[ $group ] ) ) {
				$groups[ $group ] = [];
			}
			$groups[ $group ][ $key ] = [
				'anchor'  => $config['anchor'] ?? $key,
				'url'     => $config['url'] ?? '',
				'enabled' => $config['enabled'] ?? true,
			];
		}

		return $groups;
	}

	/*--------------------------------------------------------------
	 * Static Page Links Injection (Layer 1 + 2)
	 *------------------------------------------------------------*/

	/**
	 * Inject missing internal page links into content.
	 *
	 * Uses angle defaults unless $custom_links is provided (per-post override).
	 *
	 * @param string   $content      HTML content.
	 * @param string   $angle_id     Content angle ID.
	 * @param string[] $custom_links Optional per-post link keys override.
	 * @return string Content with injected page links.
	 */
	public static function inject_links( string $content, string $angle_id, array $custom_links = [] ): string {
		$angle = Xanh_AI_Angles::get( $angle_id );

		// Use custom links if provided; otherwise fall back to angle defaults.
		$active_link_keys = ! empty( $custom_links )
			? $custom_links
			: ( $angle['internal_links'] ?? [] );

		if ( empty( $active_link_keys ) ) {
			return $content;
		}

		$link_map = self::get_link_map();
		$injected = [];

		foreach ( $active_link_keys as $link_key ) {
			if ( ! isset( $link_map[ $link_key ] ) ) {
				continue;
			}

			$link = $link_map[ $link_key ];

			// Skip disabled targets.
			if ( empty( $link['enabled'] ) ) {
				continue;
			}

			// Skip if link URL already exists in content.
			if ( stripos( $content, $link['url'] ) !== false ) {
				continue;
			}

			$injected[] = sprintf(
				'<p><a href="%s">%s →</a></p>',
				esc_url( home_url( $link['url'] ) ),
				esc_html( $link['anchor'] )
			);
		}

		if ( ! empty( $injected ) ) {
			$content .= "\n" . implode( "\n", $injected );
		}

		return $content;
	}

	/*--------------------------------------------------------------
	 * Related Posts Injection (Layer 3 — Dynamic)
	 *------------------------------------------------------------*/

	/**
	 * Find and inject related post links based on angle category + keyword.
	 *
	 * Queries published posts in the same WP category (from angle config),
	 * scores them by keyword relevance, and appends a "Bài Viết Liên Quan"
	 * block at the end of content.
	 *
	 * @param string $content    HTML content.
	 * @param string $angle_id   Content angle ID.
	 * @param string $keyword    Primary keyword for relevance scoring.
	 * @param int    $exclude_id Post ID to exclude (0 = none).
	 * @param int    $max        Max related links to inject.
	 * @return string Content with related post links appended.
	 */
	public static function inject_related_posts(
		string $content,
		string $angle_id,
		string $keyword = '',
		int $exclude_id = 0,
		int $max = 3
	): string {
		$angle    = Xanh_AI_Angles::get( $angle_id );
		$cat_slug = $angle['category'] ?? '';

		if ( empty( $cat_slug ) ) {
			return $content;
		}

		$cat = get_category_by_slug( $cat_slug );
		if ( ! $cat ) {
			return $content;
		}

		// Query published posts in same category.
		$args = [
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $max * 3, // Fetch extra for scoring + de-dup.
			'category__in'   => [ $cat->term_id ],
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true, // Performance: skip counting.
		];

		if ( $exclude_id > 0 ) {
			$args['post__not_in'] = [ $exclude_id ];
		}

		$posts = get_posts( $args );

		if ( empty( $posts ) ) {
			return $content;
		}

		// Score by keyword relevance if keyword provided.
		if ( ! empty( $keyword ) ) {
			usort( $posts, function ( $a, $b ) use ( $keyword ) {
				return self::keyword_match_score( $b, $keyword )
				     - self::keyword_match_score( $a, $keyword );
			} );
		}

		// Collect top N, skip posts already linked in content.
		$related_items = [];
		foreach ( $posts as $post ) {
			if ( count( $related_items ) >= $max ) {
				break;
			}

			$url = get_permalink( $post );

			// Skip if this URL is already present in the content.
			if ( stripos( $content, $url ) !== false ) {
				continue;
			}

			$related_items[] = sprintf(
				'<li><a href="%s">%s</a></li>',
				esc_url( $url ),
				esc_html( $post->post_title )
			);
		}

		if ( ! empty( $related_items ) ) {
			$block  = "\n" . '<div class="xanh-ai-related">' . "\n";
			$block .= '<h3>Bài Viết Liên Quan</h3>' . "\n";
			$block .= '<ul>' . "\n" . implode( "\n", $related_items ) . "\n" . '</ul>' . "\n";
			$block .= '</div>';

			$content .= $block;
		}

		return $content;
	}

	/**
	 * Score a post by keyword match in title and excerpt.
	 *
	 * @param WP_Post $post    Post object.
	 * @param string  $keyword Keyword to match.
	 * @return int Score (higher = more relevant).
	 */
	private static function keyword_match_score( $post, string $keyword ): int {
		$score    = 0;
		$kw_lower = mb_strtolower( $keyword );

		if ( mb_stripos( $post->post_title, $kw_lower ) !== false ) {
			$score += 3;
		}
		if ( ! empty( $post->post_excerpt ) && mb_stripos( $post->post_excerpt, $kw_lower ) !== false ) {
			$score += 1;
		}

		return $score;
	}

	/*--------------------------------------------------------------
	 * RankMath Integration
	 *------------------------------------------------------------*/

	/**
	 * Auto-fill RankMath SEO fields for a post.
	 *
	 * Only runs if RankMath plugin is active.
	 *
	 * @param int   $post_id  Post ID.
	 * @param array $seo_data { title, meta_description, keyword }.
	 */
	public static function set_rankmath_meta( int $post_id, array $seo_data ): void {
		if ( ! class_exists( 'RankMath' ) ) {
			return;
		}

		if ( ! empty( $seo_data['title'] ) ) {
			update_post_meta( $post_id, 'rank_math_title', sanitize_text_field( $seo_data['title'] ) );
		}

		if ( ! empty( $seo_data['meta_description'] ) ) {
			update_post_meta( $post_id, 'rank_math_description', sanitize_text_field( $seo_data['meta_description'] ) );
		}

		if ( ! empty( $seo_data['keyword'] ) ) {
			update_post_meta( $post_id, 'rank_math_focus_keyword', sanitize_text_field( $seo_data['keyword'] ) );
		}

		// Set schema type.
		update_post_meta( $post_id, 'rank_math_rich_snippet', 'article' );
		update_post_meta( $post_id, 'rank_math_snippet_article_type', 'BlogPosting' );
	}

	/**
	 * Set AI-specific post meta for tracking.
	 *
	 * @param int   $post_id   Post ID.
	 * @param array $meta_data AI generation metadata.
	 */
	public static function set_ai_meta( int $post_id, array $meta_data ): void {
		update_post_meta( $post_id, '_xanh_ai_generated', 1 );
		update_post_meta( $post_id, '_xanh_ai_angle', sanitize_text_field( $meta_data['angle_id'] ?? '' ) );
		update_post_meta( $post_id, '_xanh_ai_score', absint( $meta_data['score'] ?? 0 ) );
		update_post_meta( $post_id, '_xanh_ai_tokens', absint( $meta_data['tokens'] ?? 0 ) );

		if ( ! empty( $meta_data['keyword'] ) ) {
			update_post_meta( $post_id, '_xanh_ai_keyword', sanitize_text_field( $meta_data['keyword'] ) );
		}
	}
}
