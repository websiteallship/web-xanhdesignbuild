<?php
/**
 * Template Part: Content — Project Card.
 *
 * Reusable card component for portfolio grid and AJAX responses.
 * Data from xanh_project CPT + ACF fields + taxonomies.
 *
 * Expected to be called inside a WP Loop (the_post() already called).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Taxonomy data ──
$project_types  = get_the_terms( get_the_ID(), 'project_type' );
$type_label     = '';
$type_slug      = '';
if ( $project_types && ! is_wp_error( $project_types ) ) {
	$first_type = $project_types[0];
	$type_label = $first_type->name;
	$type_slug  = $first_type->slug;
}

$project_statuses = get_the_terms( get_the_ID(), 'project_status' );
$status_label     = '';
$status_slug      = '';
if ( $project_statuses && ! is_wp_error( $project_statuses ) ) {
	$first_status = $project_statuses[0];
	$status_label = $first_status->name;
	$status_slug  = $first_status->slug;
}

// ── Badge class mapping ──
$badge_class = 'completed';
if ( $status_slug === 'dang-thi-cong' || $status_slug === 'in-progress' ) {
	$badge_class = 'in-progress';
} elseif ( $status_slug === 'concept' || $status_slug === 'concept-3d' ) {
	$badge_class = 'concept';
}

// ── ACF fields with fallbacks ──
$location = '';
$area     = '';
$duration = '';
$style    = '';
$tagline  = '';

if ( function_exists( 'get_field' ) ) {
	$location = get_field( 'project_location' ) ?: '';
	$area     = get_field( 'project_area' ) ?: '';
	$duration = get_field( 'project_duration' ) ?: '';
	$style    = get_field( 'project_style' ) ?: '';
	$tagline  = get_field( 'project_tagline' ) ?: 'Hoàn thiện sát 3D 98% ⎮ 0% Phát sinh';
} else {
	$tagline = 'Hoàn thiện sát 3D 98% ⎮ 0% Phát sinh';
}
?>

<a href="<?php the_permalink(); ?>" class="project-card anim-fade-up" data-category="<?php echo esc_attr( $type_slug ); ?>">
	<div class="project-card__image">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', [
				'class'   => 'w-full h-full object-cover',
				'loading' => 'lazy',
				'alt'     => esc_attr( get_the_title() ),
			] ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( XANH_THEME_URI . '/assets/images/placeholder-project.jpg' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="w-full h-full object-cover" width="600" height="400" loading="lazy" />
		<?php endif; ?>
		<div class="project-card__image-overlay"></div>
		<div class="project-card__light-sweep"></div>
		<?php if ( $status_label ) : ?>
			<span class="project-card__badge project-card__badge--<?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $status_label ); ?></span>
		<?php endif; ?>
	</div>
	<div class="project-card__info">
		<div class="project-card__meta">
			<?php if ( $type_label ) : ?>
				<span class="project-card__type"><?php echo esc_html( $type_label ); ?></span>
			<?php endif; ?>
			<?php if ( $location ) : ?>
				<span class="project-card__location"><i data-lucide="map-pin" class="w-3 h-3"></i> <?php echo esc_html( $location ); ?></span>
			<?php endif; ?>
		</div>
		<h3 class="project-card__title"><?php the_title(); ?></h3>
		<p class="project-card__tagline"><?php echo esc_html( $tagline ); ?></p>
		<div class="project-card__specs">
			<?php if ( $area ) : ?>
				<span class="spec-item"><i data-lucide="ruler" class="w-3.5 h-3.5"></i> <?php echo esc_html( $area ); ?></span>
			<?php endif; ?>
			<?php if ( $duration ) : ?>
				<span class="spec-item"><i data-lucide="calendar-days" class="w-3.5 h-3.5"></i> <?php echo esc_html( $duration ); ?></span>
			<?php endif; ?>
			<?php if ( $style ) : ?>
				<span class="spec-item"><i data-lucide="palette" class="w-3.5 h-3.5"></i> <?php echo esc_html( $style ); ?></span>
			<?php endif; ?>
		</div>
	</div>
</a>
