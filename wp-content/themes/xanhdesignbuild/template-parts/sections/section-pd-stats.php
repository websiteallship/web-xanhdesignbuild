<?php
/**
 * Template Part: Section PD Stats Bar (D3).
 *
 * Stats bar with counter animation, highlight badge.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stats           = function_exists( 'get_field' ) ? get_field( 'pd_stats' ) : [];
$highlight_value = function_exists( 'get_field' ) ? get_field( 'pd_stats_highlight_value' ) : '';
$highlight_text  = function_exists( 'get_field' ) ? get_field( 'pd_stats_highlight_text' ) : '';

if ( empty( $stats ) ) {
	return;
}
?>

<!-- ═════════════════════════════════════════════════
     D3: STATS BAR — Thông Số Minh Bạch
     ═════════════════════════════════════════════════ -->
<section id="stats-bar" class="stats-bar">
  <div class="site-container">
    <div class="stats-bar__grid">

      <?php foreach ( $stats as $i => $stat ) :
        $icon       = ! empty( $stat['icon'] ) ? $stat['icon'] : 'info';
        $value      = ! empty( $stat['value'] ) ? $stat['value'] : '';
        $unit       = ! empty( $stat['unit'] ) ? $stat['unit'] : '';
        $label      = ! empty( $stat['label'] ) ? $stat['label'] : '';
        $is_counter = ! empty( $stat['is_counter'] );
        $decimals   = ! empty( $stat['decimals'] ) ? intval( $stat['decimals'] ) : 0;
      ?>
        <?php if ( $i > 0 ) : ?>
          <div class="stats-bar__divider" aria-hidden="true"></div>
        <?php endif; ?>

        <div class="stats-bar__item anim-fade-up">
          <div class="stats-bar__icon">
            <i data-lucide="<?php echo esc_attr( $icon ); ?>" class="w-5 h-5"></i>
          </div>
          <div class="stats-bar__info">
            <span class="stats-bar__value">
              <?php if ( $is_counter && is_numeric( $value ) ) : ?>
                <span class="stats-bar__counter" data-target="<?php echo esc_attr( $value ); ?>"<?php if ( $decimals ) : ?> data-decimals="<?php echo esc_attr( $decimals ); ?>"<?php endif; ?>>0</span><?php if ( $unit ) : echo esc_html( $unit ); endif; ?>
              <?php else : ?>
                <?php echo esc_html( $value ); ?><?php if ( $unit ) : ?> <?php echo esc_html( $unit ); ?><?php endif; ?>
              <?php endif; ?>
            </span>
            <?php if ( $label ) : ?>
              <span class="stats-bar__label"><?php echo esc_html( $label ); ?></span>
            <?php endif; ?>
          </div>
        </div>

      <?php endforeach; ?>

    </div>

    <?php if ( $highlight_value ) : ?>
      <!-- 0% Phát sinh highlight -->
      <div class="stats-bar__highlight anim-fade-up">
        <i data-lucide="shield-check" class="w-4 h-4"></i>
        <span><strong class="stats-bar__highlight-value"><?php echo esc_html( $highlight_value ); ?></strong> <?php echo esc_html( $highlight_text ); ?></span>
      </div>
    <?php endif; ?>

  </div>
</section>
