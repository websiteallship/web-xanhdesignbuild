<?php
/**
 * Template Part: Section SV Portfolio (S5).
 *
 * Related projects grid — ACF Relationship → xanh_project CPT.
 * Uses .anim-fade-up from components.css.
 *
 * ACF fields: sv_portfolio_eyebrow, sv_portfolio_title,
 *             sv_portfolio_subtitle, sv_portfolio_mode (manual|taxonomy),
 *             sv_related_projects (Relationship), sv_portfolio_category (Taxonomy),
 *             sv_portfolio_count.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow  = get_field( 'sv_portfolio_eyebrow' ) ?: 'Minh Chứng Chất Lượng';
$title    = get_field( 'sv_portfolio_title' ) ?: 'Bộ Sưu Tập Di Sản';
$subtitle = get_field( 'sv_portfolio_subtitle' ) ?: 'Mỗi công trình <strong>thiết kế kiến trúc và nội thất</strong> là một câu chuyện độc bản — minh chứng sống động cho lời cam kết về thẩm mỹ, chất lượng và sự tận tâm của đội ngũ XANH.';

$mode     = get_field( 'sv_portfolio_mode' ) ?: 'auto';
$projects = [];

if ( 'auto' === $mode ) {
	// Mode: Tự động — query projects linked to this service
	$current_service_id = get_the_ID();
	$count = get_field( 'sv_portfolio_count' ) ?: 6;

	$auto_args = [
		'post_type'      => 'xanh_project',
		'posts_per_page' => intval( $count ),
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_query'     => [
			[
				'key'     => 'project_service',
				'value'   => $current_service_id,
				'compare' => '=',
			],
		],
	];
	$auto_projects = new WP_Query( $auto_args );
	if ( $auto_projects->have_posts() ) {
		$projects = $auto_projects->posts;
	}
	wp_reset_postdata();

} elseif ( 'taxonomy' === $mode ) {
	// Mode: Theo danh mục — query by project_type taxonomy
	$cat_ids = get_field( 'sv_portfolio_category' );
	$count   = get_field( 'sv_portfolio_count' ) ?: 6;

	if ( ! empty( $cat_ids ) ) {
		$tax_query_args = [
			'post_type'      => 'xanh_project',
			'posts_per_page' => intval( $count ),
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'tax_query'      => [
				[
					'taxonomy' => 'project_type',
					'field'    => 'term_id',
					'terms'    => array_map( 'intval', (array) $cat_ids ),
				],
			],
		];
		$tax_projects = new WP_Query( $tax_query_args );
		if ( $tax_projects->have_posts() ) {
			$projects = $tax_projects->posts;
		}
		wp_reset_postdata();
	}
} else {
	// Mode: Chọn tay — relationship field
	$projects = get_field( 'sv_related_projects' ) ?: [];
}
?>

<section id="service-portfolio" class="s5-portfolio">
	<div class="site-container">

		<!-- Section Header -->
		<div class="s5-portfolio__header anim-fade-up">
			<span class="section-eyebrow text-primary/50 block mb-4 anim-fade-up"><?php echo esc_html( $eyebrow ); ?></span>
			<h2 class="section-title s5-portfolio__title"><?php echo esc_html( $title ); ?></h2>
			<p class="s5-portfolio__subtitle">
				<?php echo wp_kses_post( $subtitle ); ?>
			</p>
		</div>

		<?php if ( $projects ) : ?>
			<!-- 3×2 Project Cards Grid -->
			<div class="s5-portfolio__grid">
				<?php foreach ( $projects as $i => $project ) :
					setup_postdata( $project );
					$pid    = $project->ID;
					$plink  = get_permalink( $pid );
					$ptitle = get_the_title( $pid );
					$pthumb = get_post_thumbnail_id( $pid );
					$delay  = ( $i % 3 ) * 80;

					// Get ACF fields from the project.
					$p_type     = function_exists( 'get_field' ) ? get_field( 'project_type_label', $pid ) : '';
					$p_location = function_exists( 'get_field' ) ? get_field( 'project_location', $pid ) : '';
					$p_area     = function_exists( 'get_field' ) ? get_field( 'project_area', $pid ) : '';
					$p_duration = function_exists( 'get_field' ) ? get_field( 'project_duration', $pid ) : '';
					$p_status   = function_exists( 'get_field' ) ? get_field( 'project_status_label', $pid ) : '';
					$p_tagline  = function_exists( 'get_field' ) ? get_field( 'project_tagline', $pid ) : '';

					// Service name + icon from linked service
					$p_service_obj  = function_exists( 'get_field' ) ? get_field( 'project_service', $pid ) : null;
					$p_service_name = '';
					$p_service_icon = '';
					if ( $p_service_obj && isset( $p_service_obj->ID ) ) {
						$p_service_name = get_the_title( $p_service_obj->ID );
						$p_service_icon = function_exists( 'get_field' ) ? ( get_field( 'sv_card_icon', $p_service_obj->ID ) ?: 'building' ) : 'building';
					}

					// Fallback type from taxonomy.
					if ( ! $p_type ) {
						$terms = get_the_terms( $pid, 'project_type' );
						$p_type = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
					}

					// Badge class.
					$badge_class = 'project-card__badge--completed';
					$badge_text  = $p_status ?: 'Hoàn Thành';
					if ( stripos( $badge_text, 'Đang' ) !== false ) {
						$badge_class = 'project-card__badge--in-progress';
					} elseif ( stripos( $badge_text, 'Concept' ) !== false ) {
						$badge_class = 'project-card__badge--concept';
					}

					// Fallback tagline
					if ( ! $p_tagline ) {
						$p_tagline = 'Hoàn thiện sát 3D 98% ⎮ 0% Phát sinh';
					}
				?>
					<a href="<?php echo esc_url( $plink ); ?>" class="project-card anim-fade-up"<?php echo $delay ? ' style="transition-delay: ' . esc_attr( $delay ) . 'ms;"' : ''; ?>>
						<div class="project-card__image">
							<?php if ( $pthumb ) : ?>
								<?php echo wp_get_attachment_image( $pthumb, 'medium_large', false, [
									'class'   => 'w-full h-full object-cover',
									'loading' => 'lazy',
									'width'   => 600,
									'height'  => 400,
								] ); ?>
							<?php endif; ?>
							<div class="project-card__image-overlay"></div>
							<div class="project-card__light-sweep"></div>
							<span class="project-card__badge <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $badge_text ); ?></span>
						</div>
						<div class="project-card__info">
							<div class="project-card__meta">
								<?php if ( $p_type ) : ?>
									<span class="project-card__type"><?php echo esc_html( $p_type ); ?></span>
								<?php endif; ?>
								<?php if ( $p_location ) : ?>
									<span class="project-card__location"><i data-lucide="map-pin" class="w-3 h-3"></i> <?php echo esc_html( $p_location ); ?></span>
								<?php endif; ?>
							</div>
							<h3 class="project-card__title"><?php echo esc_html( $ptitle ); ?></h3>
							<p class="project-card__tagline"><?php echo esc_html( $p_tagline ); ?></p>
							<div class="project-card__specs">
								<?php if ( $p_area ) : ?>
									<span class="spec-item"><i data-lucide="ruler" class="w-3.5 h-3.5"></i> <?php echo esc_html( $p_area ); ?></span>
								<?php endif; ?>
								<?php if ( $p_duration ) : ?>
									<span class="spec-item"><i data-lucide="calendar-days" class="w-3.5 h-3.5"></i> <?php echo esc_html( $p_duration ); ?></span>
								<?php endif; ?>
								<?php if ( $p_service_name ) : ?>
									<span class="spec-item"><i data-lucide="<?php echo esc_attr( $p_service_icon ); ?>" class="w-3.5 h-3.5"></i> <?php echo esc_html( $p_service_name ); ?></span>
								<?php endif; ?>
							</div>
						</div>
					</a>
				<?php endforeach; ?>
				<?php wp_reset_postdata(); ?>
			</div><!-- /.s5-portfolio__grid -->
		<?php endif; ?>

		<!-- View All CTA -->
		<div class="s5-portfolio__cta anim-fade-up">
			<a href="<?php echo esc_url( home_url( '/du-an/' ) ); ?>" class="btn btn--outline group">
				<span>Xem Tất Cả Dự Án</span>
				<i data-lucide="arrow-right" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5"></i>
			</a>
		</div>

	</div>
</section>
