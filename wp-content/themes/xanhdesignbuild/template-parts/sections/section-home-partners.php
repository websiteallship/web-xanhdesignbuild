<?php
/**
 * Template Part: Section — Partners (Đối Tác Chiến Lược).
 *
 * Auto-scrolling Swiper ribbon of partner logos.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$default_partners = [
	[ 'name' => 'Dulux',             'logo' => null, 'url' => '' ],
	[ 'name' => 'An Cường',          'logo' => null, 'url' => '' ],
	[ 'name' => 'Schneider Electric', 'logo' => null, 'url' => '' ],
	[ 'name' => 'Häfele',            'logo' => null, 'url' => '' ],
	[ 'name' => 'Panasonic',         'logo' => null, 'url' => '' ],
	[ 'name' => 'Vicostone',         'logo' => null, 'url' => '' ],
	[ 'name' => 'Eurowindow',        'logo' => null, 'url' => '' ],
	[ 'name' => 'Inax',              'logo' => null, 'url' => '' ],
];

$acf_partners = get_field( 'partners_items' );
$partners     = ( is_array( $acf_partners ) && ! empty( $acf_partners[0]['name'] ?? '' ) )
	? $acf_partners
	: $default_partners;
?>

<section id="partners" class="partners-section" aria-label="Đối tác chiến lược">
	<div class="site-container">
		<p class="partners-overline anim-fade-up">Đối Tác Chiến Lược</p>

		<div class="swiper partners-swiper anim-fade-up" id="partners-swiper">
			<div class="swiper-wrapper">
				<?php foreach ( $partners as $partner ) :
					$logo = $partner['logo'] ?? null;
					$url  = $partner['url'] ?? '';
				?>
					<div class="swiper-slide">
						<div class="partner-logo">
							<?php if ( is_array( $logo ) && ! empty( $logo['ID'] ) ) : ?>
								<?php echo wp_get_attachment_image( $logo['ID'], 'medium', false, [
									'class'   => 'partner-logo__img',
									'alt'     => esc_attr( $partner['name'] ),
									'loading' => 'lazy',
								] ); ?>
							<?php else : ?>
								<span class="partner-logo__text"><?php echo esc_html( $partner['name'] ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
