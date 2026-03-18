<?php
/**
 * Template Tags — Reusable render functions for templates.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render breadcrumb navigation.
 *
 * Outputs breadcrumb HTML for all pages except homepage.
 * Supports: pages, blog posts, projects, archives.
 *
 * @return void
 */
function xanh_breadcrumb() {
	if ( is_front_page() ) {
		return;
	}
	?>
	<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'xanh' ); ?>" class="breadcrumb py-4">
		<div class="max-w-7xl mx-auto px-4 flex items-center gap-2 text-sm text-gray-500">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-primary transition-colors">
				<?php esc_html_e( 'Trang Chủ', 'xanh' ); ?>
			</a>
			<span class="text-gray-300">›</span>

			<?php if ( is_singular( 'xanh_project' ) ) : ?>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'xanh_project' ) ); ?>"
				   class="hover:text-primary transition-colors">
					<?php esc_html_e( 'Dự Án', 'xanh' ); ?>
				</a>
				<span class="text-gray-300">›</span>
			<?php endif; ?>

			<?php if ( is_singular( 'post' ) ) : ?>
				<?php
				$blog_page_id = (int) get_option( 'page_for_posts' );
				if ( $blog_page_id ) :
				?>
					<a href="<?php echo esc_url( get_permalink( $blog_page_id ) ); ?>"
					   class="hover:text-primary transition-colors">
						<?php esc_html_e( 'Blog', 'xanh' ); ?>
					</a>
					<span class="text-gray-300">›</span>
				<?php endif; ?>
			<?php endif; ?>

			<span class="text-dark font-medium" aria-current="page">
				<?php
				if ( is_home() ) {
					esc_html_e( 'Blog', 'xanh' );
				} elseif ( is_post_type_archive( 'xanh_project' ) ) {
					esc_html_e( 'Dự Án', 'xanh' );
				} else {
					the_title();
				}
				?>
			</span>
		</div>
	</nav>
	<?php
}

/**
 * Render a responsive hero image with proper loading attributes.
 *
 * @param  int    $image_id   WP attachment ID.
 * @param  string $size       Image size (default: 'xanh-hero').
 * @param  bool   $is_lcp     Whether this is the LCP element (eager loading).
 * @param  string $class      Additional CSS classes.
 * @return void
 */
function xanh_hero_image( $image_id, $size = 'xanh-hero', $is_lcp = true, $class = 'w-full h-full object-cover' ) {
	if ( ! $image_id ) {
		return;
	}

	$attrs = [
		'class'    => $class,
		'loading'  => $is_lcp ? 'eager' : 'lazy',
		'decoding' => 'async',
	];

	if ( $is_lcp ) {
		$attrs['fetchpriority'] = 'high';
	}

	echo wp_get_attachment_image( $image_id, $size, false, $attrs );
}

/**
 * Render section eyebrow text.
 *
 * @param  string $text Eyebrow text.
 * @return void
 */
function xanh_eyebrow( $text ) {
	if ( empty( $text ) ) {
		return;
	}
	?>
	<p class="text-accent font-semibold text-sm tracking-wider uppercase mb-4">
		<?php echo esc_html( $text ); ?>
	</p>
	<?php
}
