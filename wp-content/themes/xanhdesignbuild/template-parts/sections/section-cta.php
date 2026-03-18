<?php
/**
 * Template Part: Section — CTA (Liên Hệ Tư Vấn).
 *
 * Split card: text panel (left) + image panel (right).
 * ACF fields: cta_eyebrow, cta_headline, cta_body,
 *             cta_primary_text, cta_primary_url, cta_secondary_text, cta_secondary_url,
 *             cta_badges (Repeater), cta_image, cta_quote.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$eyebrow        = get_field( 'cta_eyebrow' ) ?: 'Bắt Đầu Hành Trình Của Bạn';
$headline        = get_field( 'cta_headline' ) ?: 'Không Gian Lý Tưởng<br />Bắt Đầu Từ<br />Một Cuộc Trò Chuyện.';
$body            = get_field( 'cta_body' ) ?: 'Đội ngũ XANH Design & Build sẵn sàng lắng nghe — đồng hành cùng bạn từ bản vẽ đầu tiên đến ngày trao chìa khóa.';
$primary_text    = get_field( 'cta_primary_text' ) ?: 'Đặt Lịch Trao Đổi Riêng';
$primary_url     = get_field( 'cta_primary_url' ) ?: '#contact';
$secondary_text  = get_field( 'cta_secondary_text' ) ?: 'Khám Phá Các Tác Phẩm';
$secondary_url   = get_field( 'cta_secondary_url' ) ?: '#projects';
$quote           = get_field( 'cta_quote' ) ?: '"Thiết kế không chỉ là vẻ đẹp bên ngoài — đó là cách không gian khiến bạn cảm thấy."';
$image           = get_field( 'cta_image' );
$img_url         = $image['url'] ?? esc_url( XANH_THEME_URI . '/assets/images/interior-living.png' );
$img_alt         = $image['alt'] ?? 'Không gian nội thất XANH Design & Build';
$img_id          = $image['ID'] ?? null;

$default_badges = [
	[ 'number' => '10', 'suffix' => '+', 'label' => 'Năm kinh nghiệm' ],
	[ 'number' => '200', 'suffix' => '+', 'label' => 'Dự án hoàn thành' ],
	[ 'number' => '98', 'suffix' => '%', 'label' => 'Khách hàng hài lòng' ],
];
$badges = get_field( 'cta_badges' ) ?: $default_badges;
?>

<section id="cta" class="cta-section section" aria-label="Liên hệ tư vấn">
	<div class="site-container">
		<div class="cta-card">
			<!-- LEFT: Text panel -->
			<div class="cta-panel cta-panel--text">
				<div class="cta-content">
					<p class="cta-el section-eyebrow cta-eyebrow">
						<?php echo esc_html( $eyebrow ); ?>
					</p>
					<h2 class="cta-el section-title text-primary cta-heading">
						<?php echo wp_kses_post( $headline ); ?>
					</h2>
					<p class="cta-el cta-body">
						<?php echo esc_html( $body ); ?>
					</p>
					<div class="cta-el cta-actions">
						<a href="<?php echo esc_url( $primary_url ); ?>" class="btn btn--primary group">
							<span><?php echo esc_html( $primary_text ); ?></span>
							<i data-lucide="phone" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5"></i>
						</a>
						<a href="<?php echo esc_url( $secondary_url ); ?>" class="cta-btn cta-btn--ghost group">
							<span><?php echo esc_html( $secondary_text ); ?></span>
							<i data-lucide="message-circle" class="cta-btn--ghost__icon"></i>
						</a>
					</div>
					<!-- Trust badges row -->
					<div class="cta-el cta-badges">
						<?php foreach ( $badges as $i => $badge ) : ?>
							<?php if ( $i > 0 ) : ?>
								<div class="cta-badge-sep"></div>
							<?php endif; ?>
							<div class="cta-badge">
								<span class="cta-badge__num" data-count="<?php echo esc_attr( $badge['number'] ); ?>" data-suffix="<?php echo esc_attr( $badge['suffix'] ?? '' ); ?>">0</span>
								<span class="cta-badge__label"><?php echo esc_html( $badge['label'] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<!-- RIGHT: Image panel -->
			<div class="cta-panel cta-panel--image" aria-hidden="true">
				<?php if ( $img_id ) :
					echo wp_get_attachment_image( $img_id, 'large', false, [
						'class'   => 'cta-img',
						'loading' => 'lazy',
					] );
				else : ?>
					<img src="<?php echo esc_url( $img_url ); ?>"
						alt="<?php echo esc_attr( $img_alt ); ?>"
						class="cta-img" width="640" height="800" loading="lazy" />
				<?php endif; ?>
				<!-- Decorative overlay quote -->
				<div class="cta-img-quote">
					<svg class="cta-img-quote__icon" viewBox="0 0 24 24" fill="currentColor">
						<path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z" />
						<path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z" />
					</svg>
					<p><?php echo wp_kses_post( $quote ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>
