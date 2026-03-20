<?php
/**
 * Template Part: Section PD Related Projects (D9).
 *
 * Related projects grid — 3 cards from same category.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current project's project_type terms
$current_id    = get_the_ID();
$project_types = get_the_terms( $current_id, 'project_type' );
$type_ids      = [];
if ( $project_types && ! is_wp_error( $project_types ) ) {
	$type_ids = wp_list_pluck( $project_types, 'term_id' );
}

// Query related projects
$args = [
	'post_type'      => 'xanh_project',
	'posts_per_page' => 3,
	'post__not_in'   => [ $current_id ],
	'orderby'        => 'rand',
	'post_status'    => 'publish',
];

// Filter by same category if available
if ( ! empty( $type_ids ) ) {
	$args['tax_query'] = [
		[
			'taxonomy' => 'project_type',
			'field'    => 'term_id',
			'terms'    => $type_ids,
		],
	];
}

$related_query = new WP_Query( $args );

if ( ! $related_query->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>

<!-- ═════════════════════════════════════════════════
     D9: Related Projects — Dự Án Liên Quan
     ═════════════════════════════════════════════════ -->
<section id="d9-related" class="d9-related">
  <div class="site-container">

    <!-- Section Header -->
    <div class="d9-related__header anim-fade-up">
      <span class="section-eyebrow d9-related__eyebrow"><?php esc_html_e( 'Cùng Danh Mục', 'xanh' ); ?></span>
      <h2 class="section-title d9-related__title"><?php esc_html_e( 'Dự Án Liên Quan', 'xanh' ); ?></h2>
    </div>

    <!-- Project Cards Grid -->
    <div class="d9-related__grid">

      <?php while ( $related_query->have_posts() ) :
        $related_query->the_post();
        get_template_part( 'template-parts/content/card', 'project' );
      endwhile; ?>

    </div><!-- /.d9-related__grid -->

  </div>
</section><!-- /#d9-related -->

<?php wp_reset_postdata(); ?>
