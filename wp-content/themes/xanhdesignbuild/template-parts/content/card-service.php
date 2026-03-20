<?php
/**
 * Template Part: Content — Service Card.
 *
 * Reusable card component for services grid and AJAX responses.
 * Data from xanh_service CPT + ACF fields.
 *
 * Expected to be called inside a WP Loop (the_post() already called).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── ACF fields ──
$sv_desc  = '';
$sv_icon  = '';
if ( function_exists( 'get_field' ) ) {
	$sv_desc = get_field( 'sv_hero_desc' ) ?: '';
	$sv_icon = get_field( 'sv_card_icon' ) ?: 'drafting-compass';
}

// Fallback description: use excerpt or auto-generate from title.
if ( empty( $sv_desc ) ) {
	$sv_desc = has_excerpt() ? get_the_excerpt() : '';
}
// Truncate to ~120 chars for card display.
if ( ! empty( $sv_desc ) ) {
	$sv_desc = wp_strip_all_tags( $sv_desc );
	if ( mb_strlen( $sv_desc ) > 120 ) {
		$sv_desc = mb_substr( $sv_desc, 0, 117 ) . '...';
	}
}

// Fallback icon name.
if ( empty( $sv_icon ) ) {
	$sv_icon = 'drafting-compass';
}
?>

<a href="<?php the_permalink(); ?>" class="service-card group anim-fade-up">
	<div class="service-card__image-wrap">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', [
				'class'   => 'service-card__image',
				'loading' => 'lazy',
				'alt'     => esc_attr( get_the_title() ),
			] ); ?>
		<?php else : ?>
			<?php
			// Fallback: use sv_hero_image from ACF.
			$hero_img = function_exists( 'get_field' ) ? get_field( 'sv_hero_image' ) : null;
			if ( $hero_img && isset( $hero_img['ID'] ) ) :
			?>
				<?php echo wp_get_attachment_image( $hero_img['ID'], 'large', false, [
					'class'   => 'service-card__image',
					'loading' => 'lazy',
					'alt'     => esc_attr( get_the_title() ),
				] ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( XANH_THEME_URI . '/assets/images/placeholder-project.png' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="service-card__image" width="600" height="800" loading="lazy" />
			<?php endif; ?>
		<?php endif; ?>
		
		<!-- Gradient Overlay -->
		<div class="service-card__overlay"></div>
		
		<!-- Icon Badge (Top Right) -->
		<span class="service-card__icon-badge">
			<i data-lucide="<?php echo esc_attr( $sv_icon ); ?>" class="w-5 h-5"></i>
		</span>

		<!-- Content (Bottom Reveal) -->
		<div class="service-card__content">
			<h3 class="service-card__title"><?php the_title(); ?></h3>
			
			<div class="service-card__hidden-wrap">
				<div class="service-card__hidden-inner">
					<?php if ( $sv_desc ) : ?>
						<p class="service-card__desc"><?php echo esc_html( $sv_desc ); ?></p>
					<?php endif; ?>
					<span class="service-card__link">
						<span>Khám Phá Dịch Vụ</span>
						<i data-lucide="arrow-right" class="w-4 h-4"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
</a>
