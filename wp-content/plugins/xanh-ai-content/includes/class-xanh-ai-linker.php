<?php
/**
 * Internal Linking + RankMath — auto-inject links & SEO meta.
 *
 * Logic from PLUGIN_AI_SEO.md §3 + §5.
 *
 * @package Xanh_AI_Content
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xanh_AI_Linker {

	/**
	 * Internal link map: key → URL + anchor text.
	 *
	 * @return array<string, array{url: string, anchor: string}>
	 */
	private static function get_link_map(): array {
		return apply_filters( 'xanh_ai_link_map', [
			'contact'        => [
				'url'    => '/lien-he/',
				'anchor' => 'Đặt lịch tư vấn riêng',
			],
			'portfolio'      => [
				'url'    => '/du-an/',
				'anchor' => 'Xem các tác phẩm XANH',
			],
			'estimator'      => [
				'url'    => '/du-toan/',
				'anchor' => 'Khám phá dự toán của bạn',
			],
			'green-solution' => [
				'url'    => '/giai-phap-xanh/',
				'anchor' => 'Tìm hiểu giải pháp xanh',
			],
		] );
	}

	/**
	 * Inject missing internal links into content based on angle config.
	 *
	 * Only adds links that the AI didn't already include.
	 *
	 * @param string $content  HTML content.
	 * @param string $angle_id Content angle ID.
	 * @return string Content with injected links.
	 */
	public static function inject_links( string $content, string $angle_id ): string {
		$angle = Xanh_AI_Angles::get( $angle_id );
		if ( ! $angle || empty( $angle['internal_links'] ) ) {
			return $content;
		}

		$link_map = self::get_link_map();
		$injected = [];

		foreach ( $angle['internal_links'] as $link_key ) {
			if ( ! isset( $link_map[ $link_key ] ) ) {
				continue;
			}

			$link = $link_map[ $link_key ];

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

	/**
	 * Auto-fill RankMath SEO fields for a post.
	 *
	 * Only runs if RankMath plugin is active.
	 * From PLUGIN_AI_SEO.md §5.
	 *
	 * @param int   $post_id  Post ID.
	 * @param array $seo_data {
	 *     @type string $title            SEO title.
	 *     @type string $meta_description Meta description.
	 *     @type string $keyword          Focus keyword.
	 * }
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
