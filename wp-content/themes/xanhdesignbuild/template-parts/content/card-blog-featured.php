<?php
/**
 * Template Part: Content — Blog Featured Card.
 *
 * Renders a single featured blog card in two variants:
 * - 'large': Left column card with full details.
 * - 'small': Right column stacked card.
 *
 * Expects to be called inside the WP Loop.
 * Pass variant via $args['size'] = 'large' | 'small'.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$size = $args['size'] ?? 'large';
$is_large = ( 'large' === $size );

// Get first category.
$categories = get_the_category();
$cat_name   = '';
$cat_slug   = '';
if ( ! empty( $categories ) ) {
	$cat_name = $categories[0]->name;
	$cat_slug = $categories[0]->slug;
}

// Estimated reading time (1 minute per 200 words).
$content    = get_the_content();
$word_count = str_word_count( wp_strip_all_tags( $content ) );
$read_time  = max( 1, ceil( $word_count / 200 ) );


?>

<a href="<?php the_permalink(); ?>"
   class="featured-card featured-card--<?php echo esc_attr( $size ); ?> xanh-card anim-fade-up"
   data-category="<?php echo esc_attr( $cat_slug ); ?>">

	<?php if ( $is_large ) : ?>
		<div class="featured-card__image xanh-card__img-wrap">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large', [
					'class'   => 'w-full h-full object-cover',
					'loading' => 'eager',
					'width'   => '800',
					'height'  => '500',
				] ); ?>
			<?php else : ?>
				<div class="w-full h-full bg-gray-200 flex items-center justify-center" style="min-height:300px">
					<span class="text-gray-400">Chưa có ảnh</span>
				</div>
			<?php endif; ?>
			<div class="xanh-card__overlay"></div>
			<div class="xanh-card__sweep"></div>
			<?php if ( $cat_name ) : ?>
				<span class="tag featured-card__tag"><?php echo esc_html( $cat_name ); ?></span>
			<?php endif; ?>
			<div class="featured-card__read-time">
				<i data-lucide="clock" class="w-3.5 h-3.5"></i>
				<?php echo esc_html( $read_time ); ?> phút đọc
			</div>
		</div>
	<?php else : ?>
		<div class="featured-card--small__image xanh-card__img-wrap">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'medium_large', [
					'class'   => 'w-full h-full object-cover',
					'loading' => 'lazy',
					'width'   => '400',
					'height'  => '250',
				] ); ?>
			<?php else : ?>
				<div class="w-full h-full bg-gray-200 flex items-center justify-center" style="min-height:200px">
					<span class="text-gray-400 text-sm">Chưa có ảnh</span>
				</div>
			<?php endif; ?>
			<div class="xanh-card__overlay"></div>
			<div class="xanh-card__sweep"></div>
			<?php if ( $cat_name ) : ?>
				<span class="tag featured-card__tag"><?php echo esc_html( $cat_name ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="featured-card__body">
		<div class="featured-card__meta">
			<i data-lucide="calendar" class="w-3 h-3 featured-card__meta-icon"></i>
			<span class="text-dark/40 text-xs">
				<?php echo esc_html( get_the_date( 'j \T\h\á\n\g n, Y' ) ); ?>
			</span>
			<span class="text-dark/40 text-xs featured-card__meta-sep">·</span>
			<?php if ( $is_large ) : ?>
				<i data-lucide="user" class="w-3 h-3 featured-card__meta-icon"></i>
				<span class="text-dark/40 text-xs"><?php echo esc_html( get_the_author() ); ?></span>
			<?php else : ?>
				<i data-lucide="clock" class="w-3 h-3 featured-card__meta-icon"></i>
				<span class="text-dark/40 text-xs"><?php echo esc_html( $read_time ); ?> phút đọc</span>
			<?php endif; ?>
		</div>

		<h3 class="featured-card__title<?php echo ! $is_large ? ' featured-card__title--sm' : ''; ?>">
			<?php the_title(); ?>
		</h3>

		<p class="featured-card__excerpt text-body">
			<?php echo esc_html( wp_trim_words( get_the_excerpt(), $is_large ? 30 : 25, '...' ) ); ?>
		</p>

		<span class="featured-card__cta">
			<?php echo $is_large ? 'Khám Phá' : 'Đọc tiếp'; ?>
			<i data-lucide="arrow-right" class="w-<?php echo $is_large ? '4' : '3.5'; ?> h-<?php echo $is_large ? '4' : '3.5'; ?>"></i>
		</span>
	</div>
</a>
