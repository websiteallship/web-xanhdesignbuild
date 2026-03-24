<?php
/**
 * Template Part: Section — Blog Category Tabs (Sticky).
 *
 * Renders category filter tabs from WordPress categories.
 * Each tab links to its category archive page.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get all categories with posts.
$categories = get_categories( [
	'orderby'    => 'count',
	'order'      => 'DESC',
	'hide_empty' => true,
] );

// Total published posts.
$total_posts = (int) wp_count_posts()->publish;

// Current category (if viewing a category archive).
$current_cat = is_category() ? get_queried_object_id() : 0;
$is_all      = is_home() && ! is_category();
?>

<div id="category-tabs-bar" class="filter-bar" role="navigation" aria-label="<?php esc_attr_e( 'Lọc chuyên mục', 'xanh' ); ?>">
	<div class="site-container">
		<div class="filter-bar__inner" role="tablist">
			<?php if ( ! is_home() ) : ?>
				<?php // Link mode: for category archives ?>
				<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"
				   class="filter-tab<?php echo $is_all ? ' is-active' : ''; ?>"
				   role="tab"
				   aria-selected="<?php echo $is_all ? 'true' : 'false'; ?>">
					<?php esc_html_e( 'Tất Cả', 'xanh' ); ?>
					<span class="filter-tab__count"><?php echo esc_html( $total_posts ); ?></span>
				</a>

				<?php foreach ( $categories as $cat ) : ?>
					<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
					   class="filter-tab<?php echo ( $current_cat === $cat->term_id ) ? ' is-active' : ''; ?>"
					   data-category="<?php echo esc_attr( $cat->term_id ); ?>"
					   role="tab"
					   aria-selected="<?php echo ( $current_cat === $cat->term_id ) ? 'true' : 'false'; ?>">
						<?php echo esc_html( $cat->name ); ?>
						<span class="filter-tab__count"><?php echo esc_html( $cat->count ); ?></span>
					</a>
				<?php endforeach; ?>
			<?php else : ?>
				<?php // Button mode: for main blog index (AJAX filtering) ?>
				<button class="filter-tab is-active" data-category="" role="tab" aria-selected="true">
					<?php esc_html_e( 'Tất Cả', 'xanh' ); ?>
					<span class="filter-tab__count"><?php echo esc_html( $total_posts ); ?></span>
				</button>

				<?php foreach ( $categories as $cat ) : ?>
					<button class="filter-tab" data-category="<?php echo esc_attr( $cat->term_id ); ?>" role="tab" aria-selected="false">
						<?php echo esc_html( $cat->name ); ?>
						<span class="filter-tab__count"><?php echo esc_html( $cat->count ); ?></span>
					</button>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</div>
