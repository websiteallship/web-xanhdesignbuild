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

$partners = [
	[ 'name' => 'Dulux',             'alt' => 'Dulux — Đối tác sơn cao cấp' ],
	[ 'name' => 'An Cường',          'alt' => 'An Cường — Đối tác gỗ công nghiệp' ],
	[ 'name' => 'Schneider Electric', 'alt' => 'Schneider Electric — Đối tác thiết bị điện' ],
	[ 'name' => 'Häfele',            'alt' => 'Häfele — Đối tác phụ kiện nội thất' ],
	[ 'name' => 'Panasonic',         'alt' => 'Panasonic — Đối tác thiết bị điện tử' ],
	[ 'name' => 'Vicostone',         'alt' => 'Vicostone — Đối tác đá thạch anh' ],
	[ 'name' => 'Eurowindow',        'alt' => 'Eurowindow — Đối tác cửa sổ' ],
	[ 'name' => 'Inax',              'alt' => 'Inax — Đối tác thiết bị vệ sinh' ],
];
?>

<section id="partners" class="partners-section" aria-label="Đối tác chiến lược">
	<div class="site-container">
		<p class="partners-overline anim-fade-up">Đối Tác Chiến Lược</p>

		<div class="swiper partners-swiper anim-fade-up" id="partners-swiper">
			<div class="swiper-wrapper">
				<?php foreach ( $partners as $partner ) : ?>
					<div class="swiper-slide">
						<div class="partner-logo">
							<span class="partner-logo__text"><?php echo esc_html( $partner['name'] ); ?></span>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
