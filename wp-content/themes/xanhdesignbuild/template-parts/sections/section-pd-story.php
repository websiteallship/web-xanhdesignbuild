<?php
/**
 * Template Part: Section PD Story (D4).
 *
 * Editorial split-screen: Challenge vs Solution panels.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow         = function_exists( 'get_field' ) ? get_field( 'pd_story_eyebrow' ) : '';
$title            = function_exists( 'get_field' ) ? get_field( 'pd_story_title' ) : '';
$challenge_title  = function_exists( 'get_field' ) ? get_field( 'pd_challenge_title' ) : '';
$challenge_body   = function_exists( 'get_field' ) ? get_field( 'pd_challenge_body' ) : '';
$context_heading  = function_exists( 'get_field' ) ? get_field( 'pd_context_heading' ) : '';
$context_body     = function_exists( 'get_field' ) ? get_field( 'pd_context_body' ) : '';
$client_quote     = function_exists( 'get_field' ) ? get_field( 'pd_client_quote' ) : '';
$client_name      = function_exists( 'get_field' ) ? get_field( 'pd_client_name' ) : '';
$solution_title   = function_exists( 'get_field' ) ? get_field( 'pd_solution_title' ) : '';
$solutions        = function_exists( 'get_field' ) ? get_field( 'pd_solutions' ) : [];
?>

<!-- ═════════════════════════════════════════════════
     D4: CÂU CHUYỆN DỰ ÁN
     ═════════════════════════════════════════════════ -->
<section id="project-story" class="project-story">

  <!-- Section Header -->
  <div class="site-container">
    <div class="section-header section-header--center anim-fade-up">
      <?php if ( $eyebrow ) : ?>
        <span class="section-eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
      <?php endif; ?>
      <?php if ( $title ) : ?>
        <h2 class="section-title text-primary"><?php echo wp_kses_post( $title ); ?></h2>
      <?php endif; ?>
    </div>
  </div>

  <!-- Story Block -->
  <div class="project-story__block anim-fade-up">

    <!-- ▌ LEFT — BÀI TOÁN (Beige Panel) -->
    <div class="project-story__challenge">

      <span class="project-story__col-label"><?php esc_html_e( 'Bài Toán', 'xanh' ); ?></span>

      <?php if ( $challenge_title ) : ?>
        <h3 class="project-story__col-title"><?php echo wp_kses_post( $challenge_title ); ?></h3>
      <?php endif; ?>

      <?php if ( $challenge_body ) : ?>
        <p class="text-lead text-dark/80 mb-6"><?php echo esc_html( $challenge_body ); ?></p>
      <?php endif; ?>

      <?php if ( $context_heading || $context_body ) : ?>
        <div class="project-story__context">
          <?php if ( $context_heading ) : ?>
            <h4 class="project-story__context-heading"><?php echo esc_html( $context_heading ); ?></h4>
          <?php endif; ?>
          <?php if ( $context_body ) : ?>
            <p class="text-body text-dark/70"><?php echo esc_html( $context_body ); ?></p>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if ( $client_quote ) : ?>
        <blockquote class="project-story__quote mt-auto">
          <p class="text-quote"><?php echo esc_html( $client_quote ); ?></p>
          <?php if ( $client_name ) : ?>
            <cite class="block mt-4 text-xs font-semibold text-primary tracking-wide uppercase"><?php echo esc_html( $client_name ); ?></cite>
          <?php endif; ?>
        </blockquote>
      <?php endif; ?>

    </div>

    <!-- ▌ CONNECTOR — Dashed Arrow -->
    <div class="project-story__connector" aria-hidden="true">
      <div class="project-story__connector-line"></div>
      <div class="project-story__connector-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m9 18 6-6-6-6"/>
        </svg>
      </div>
      <div class="project-story__connector-line"></div>
    </div>

    <!-- ▌ RIGHT — LỜI GIẢI (White Panel) -->
    <div class="project-story__solution">

      <span class="project-story__col-label"><?php esc_html_e( 'Lời Giải', 'xanh' ); ?></span>

      <?php if ( $solution_title ) : ?>
        <h3 class="project-story__col-title"><?php echo esc_html( $solution_title ); ?></h3>
      <?php endif; ?>

      <?php if ( $solutions ) : ?>
        <?php foreach ( $solutions as $sol ) :
          $sol_icon  = ! empty( $sol['icon'] ) ? $sol['icon'] : 'check';
          $sol_title = ! empty( $sol['title'] ) ? $sol['title'] : '';
          $sol_desc  = ! empty( $sol['desc'] ) ? $sol['desc'] : '';
        ?>
          <div class="project-story__solution-item group cursor-default">
            <div class="icon-circle icon-circle--sm shrink-0">
              <i data-lucide="<?php echo esc_attr( $sol_icon ); ?>" class="w-5 h-5"></i>
            </div>
            <div class="project-story__sol-content">
              <?php if ( $sol_title ) : ?>
                <h4 class="project-story__sol-title"><?php echo esc_html( $sol_title ); ?></h4>
              <?php endif; ?>
              <?php if ( $sol_desc ) : ?>
                <p class="project-story__sol-desc"><?php echo esc_html( $sol_desc ); ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>

  </div>

</section>
