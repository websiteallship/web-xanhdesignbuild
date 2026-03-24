<?php
/**
 * Template Part: Content — Blog Article Card.
 *
 * Renders a single article card in the blog grid.
 * Used in both the main grid and AJAX Load More responses.
 * Expects to be called inside the WP Loop.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get first category.
$categories = get_the_category();
$cat_name   = '';
$cat_slug   = '';
if ( ! empty( $categories ) ) {
	$cat_name = $categories[0]->name;
	$cat_slug = $categories[0]->slug;
}

// Estimated reading time.
$content    = get_the_content();
$word_count = str_word_count( wp_strip_all_tags( $content ) );
$read_time  = max( 1, ceil( $word_count / 200 ) );

// Check for author display vs read time.
$show_author = ( $word_count > 500 );


?>

<a href="<?php the_permalink(); ?>"
   class="article-card xanh-card anim-fade-up"
   data-category="<?php echo esc_attr( $cat_slug ); ?>">

	<div class="article-card__image xanh-card__img-wrap" style="aspect-ratio: 16/10;">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium_large', [
				'class'    => 'w-full h-full object-cover',
				'loading'  => 'lazy',
				'decoding' => 'async',
				'width'    => '600',
				'height'   => '400',
			] ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( XANH_THEME_URI . '/assets/images/placeholder-project.png' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="w-full h-full object-cover" width="600" height="400" loading="lazy" />
		<?php endif; ?>
		<div class="xanh-card__overlay"></div>
		<div class="xanh-card__sweep"></div>
		<?php if ( $cat_name ) : ?>
			<span class="tag article-card__tag"><?php echo esc_html( $cat_name ); ?></span>
		<?php endif; ?>
	</div>

	<div class="article-card__body">
		<div class="article-card__meta">
			<i data-lucide="calendar" class="w-3 h-3 featured-card__meta-icon"></i>
			<span><?php echo esc_html( get_the_date( 'j \T\h\á\n\g n, Y' ) ); ?></span>
			<span class="featured-card__meta-sep">·</span>
			<?php if ( $show_author ) : ?>
				<i data-lucide="user" class="w-3 h-3 featured-card__meta-icon"></i>
				<span><?php echo esc_html( get_the_author() ); ?></span>
			<?php else : ?>
				<i data-lucide="clock" class="w-3 h-3 featured-card__meta-icon"></i>
				<span><?php echo esc_html( $read_time ); ?> phút đọc</span>
			<?php endif; ?>
		</div>

		<h3 class="article-card__title"><?php the_title(); ?></h3>

		<p class="article-card__excerpt">
			<?php echo esc_html( wp_trim_words( get_the_excerpt(), 20, '...' ) ); ?>
		</p>

		<span class="featured-card__cta">
			Đọc tiếp <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
		</span>
	</div>
</a>
