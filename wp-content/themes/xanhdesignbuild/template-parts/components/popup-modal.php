<?php
/**
 * Popup Modal — Frontend Renderer.
 *
 * Queries all active xanh_popup posts and renders them
 * into the page footer with data-* attributes for the JS manager.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'get_field' ) ) {
	return;
}

/**
 * Check if a popup should display on the current page.
 *
 * @param  int $popup_id  The popup post ID.
 * @return bool
 */
function xanh_popup_should_display( $popup_id ) {
	$target = get_field( 'popup_pages', $popup_id );

	switch ( $target ) {
		case 'all':
			return true;

		case 'home':
			return is_front_page();

		case 'blog':
			return is_home() || is_singular( 'post' ) || is_category();

		case 'services':
			return is_post_type_archive( 'xanh_service' ) || is_singular( 'xanh_service' );

		case 'custom':
			$custom_pages = get_field( 'popup_custom_pages', $popup_id );
			if ( ! $custom_pages || ! is_array( $custom_pages ) ) {
				return false;
			}
			$current_id = get_queried_object_id();
			return in_array( $current_id, $custom_pages, true );

		default:
			return true;
	}
}

/**
 * Convert YouTube URL to embeddable format.
 *
 * @param  string $url YouTube URL.
 * @return string Embed URL or empty string.
 */
function xanh_youtube_embed_url( $url ) {
	if ( empty( $url ) ) {
		return '';
	}

	// Match youtube.com/watch?v=ID or youtu.be/ID
	if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches ) ) {
		return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&rel=0';
	}

	return esc_url( $url );
}

// ── Preview Mode: ?preview_popup=ID (admin only) ──
$preview_popup_id = isset( $_GET['preview_popup'] ) ? absint( $_GET['preview_popup'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

// Query active popups.
$query_args = [
	'post_type'      => 'xanh_popup',
	'post_status'    => 'publish',
	'posts_per_page' => 10,
	'meta_query'     => [
		[
			'key'     => 'popup_active',
			'value'   => '1',
			'compare' => '=',
		],
	],
	'no_found_rows'  => true,
];

// In preview mode, override query to fetch just that one popup (even if inactive).
if ( $preview_popup_id && current_user_can( 'manage_options' ) ) {
	$query_args = [
		'post_type'      => 'xanh_popup',
		'post_status'    => 'any',
		'p'              => $preview_popup_id,
		'posts_per_page' => 1,
		'no_found_rows'  => true,
	];
}

$popup_query = new WP_Query( $query_args );

if ( ! $popup_query->have_posts() ) {
	return;
}

while ( $popup_query->have_posts() ) :
	$popup_query->the_post();
	$popup_id = get_the_ID();

	// Check page targeting (skip in preview mode).
	$is_preview = ( $preview_popup_id === $popup_id );
	if ( ! $is_preview && ! xanh_popup_should_display( $popup_id ) ) {
		continue;
	}

	// Gather field values.
	$content_type    = get_field( 'popup_content_type', $popup_id ) ?: 'wysiwyg';
	$size            = get_field( 'popup_size', $popup_id ) ?: 'medium';
	$overlay         = get_field( 'popup_overlay', $popup_id ) ?: 'dark';
	$animation       = get_field( 'popup_animation', $popup_id ) ?: 'fade-scale';
	$bg_color        = get_field( 'popup_bg_color', $popup_id ) ?: 'light';
	$trigger         = get_field( 'popup_trigger', $popup_id ) ?: 'click';
	$frequency       = get_field( 'popup_frequency', $popup_id ) ?: 'once_session';
	$hide_mobile     = $is_preview ? false : get_field( 'popup_hide_mobile', $popup_id );
	$click_selector  = get_field( 'popup_click_selector', $popup_id );

	// Trigger-specific values.
	$delay_seconds   = ( 'delay' === $trigger )  ? intval( get_field( 'popup_delay_seconds', $popup_id ) )  : 0;
	$scroll_percent  = ( 'scroll' === $trigger )  ? intval( get_field( 'popup_scroll_percent', $popup_id ) ) : 0;

	// Build data attributes.
	$data_attrs = sprintf(
		'data-popup-id="%d" data-trigger="%s" data-frequency="%s" data-animation="%s" data-size="%s" data-overlay="%s"',
		$popup_id,
		esc_attr( $trigger ),
		esc_attr( $frequency ),
		esc_attr( $animation ),
		esc_attr( $size ),
		esc_attr( $overlay )
	);

	if ( $delay_seconds ) {
		$data_attrs .= sprintf( ' data-delay="%d"', $delay_seconds );
	}
	if ( $scroll_percent ) {
		$data_attrs .= sprintf( ' data-scroll="%d"', $scroll_percent );
	}
	if ( $click_selector ) {
		$data_attrs .= sprintf( ' data-click-selector="%s"', esc_attr( $click_selector ) );
	}
	if ( $hide_mobile ) {
		$data_attrs .= ' data-hide-mobile="1"';
	}
?>

<!-- Popup Modal #<?php echo intval( $popup_id ); ?> -->
<div class="x-modal x-modal--<?php echo esc_attr( $size ); ?>"
     id="xanh-popup-<?php echo intval( $popup_id ); ?>"
     role="dialog"
     aria-modal="true"
     aria-hidden="true"
     aria-labelledby="x-modal-title-<?php echo intval( $popup_id ); ?>"
     <?php echo $data_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

	<!-- Overlay -->
	<div class="x-modal__overlay x-modal__overlay--<?php echo esc_attr( $overlay ); ?>" data-modal-close></div>

	<!-- Modal Box -->
	<div class="x-modal__box x-modal__anim--<?php echo esc_attr( $animation ); ?><?php echo 'primary' === $bg_color ? ' x-modal__box--primary' : ''; ?>">

		<!-- Close Button -->
		<button class="x-modal__close" aria-label="<?php esc_attr_e( 'Đóng popup', 'xanh' ); ?>" data-modal-close>
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<line x1="18" y1="6" x2="6" y2="18"></line>
				<line x1="6" y1="6" x2="18" y2="18"></line>
			</svg>
		</button>

		<div class="x-modal__body x-modal__body--<?php echo esc_attr( $content_type ); ?>">

			<?php
			// ── Content Switcher ──
			switch ( $content_type ) :

				// ── WYSIWYG ──
				case 'wysiwyg':
					$wysiwyg = get_field( 'popup_wysiwyg', $popup_id );
					if ( $wysiwyg ) {
						echo '<div class="x-modal__wysiwyg">' . wp_kses_post( $wysiwyg ) . '</div>';
					}
					break;

				// ── Image Only ──
				case 'image':
					$image      = get_field( 'popup_image', $popup_id );
					$image_link = get_field( 'popup_image_link', $popup_id );
					if ( $image && isset( $image['url'] ) ) {
						$img_html = '<img src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( $image['alt'] ?? '' ) . '" class="x-modal__image" loading="lazy">';
						if ( $image_link ) {
							echo '<a href="' . esc_url( $image_link ) . '" class="x-modal__image-link" target="_blank" rel="noopener noreferrer">' . $img_html . '</a>';
						} else {
							echo $img_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					}
					break;

				// ── Raw HTML ──
				case 'html':
					$html = get_field( 'popup_html', $popup_id );
					if ( $html ) {
						// Support shortcodes within raw HTML.
						echo do_shortcode( $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					break;

				// ── Template: Quote (FluentForm) ──
				case 'quote':
					$title   = get_field( 'popup_title', $popup_id );
					$desc    = get_field( 'popup_desc', $popup_id );
					$form_id = intval( get_field( 'popup_form_id', $popup_id ) );
					?>
					<div class="x-modal__template x-modal__template--quote">
						<?php if ( 'primary' === $bg_color ) : ?>
							<!-- Decorative background (same as blog CTA) -->
							<div class="x-modal__deco" aria-hidden="true">
								<svg viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="350" cy="50" r="120" stroke="white" stroke-width="1"/><circle cx="50" cy="350" r="80" stroke="white" stroke-width="1"/></svg>
							</div>
						<?php endif; ?>
						<div class="x-modal__quote-inner">
							<?php if ( $title ) : ?>
								<h3 class="x-modal__title" id="x-modal-title-<?php echo intval( $popup_id ); ?>"><?php echo esc_html( $title ); ?></h3>
							<?php endif; ?>
							<?php if ( $desc ) : ?>
								<p class="x-modal__desc"><?php echo esc_html( $desc ); ?></p>
							<?php endif; ?>
							<?php if ( $form_id ) : ?>
								<div class="x-modal__form">
									<?php echo do_shortcode( '[fluentform id="' . $form_id . '"]' ); ?>
								</div>
							<?php endif; ?>
							<p class="x-modal__trust">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
								Bảo mật thông tin. XANH cam kết không spam.
							</p>
						</div>
					</div>
					<?php
					break;

				// ── Template: Ebook (Image + FluentForm) ──
				case 'ebook':
					$title   = get_field( 'popup_title', $popup_id );
					$desc    = get_field( 'popup_desc', $popup_id );
					$image   = get_field( 'popup_image', $popup_id );
					$form_id = intval( get_field( 'popup_form_id', $popup_id ) );
					?>
					<div class="x-modal__template x-modal__template--ebook">
						<?php if ( $image && isset( $image['url'] ) ) : ?>
							<div class="x-modal__ebook-cover">
								<img src="<?php echo esc_url( $image['url'] ); ?>"
								     alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>"
								     class="x-modal__ebook-img" loading="lazy">
							</div>
						<?php endif; ?>
						<div class="x-modal__ebook-content">
							<?php if ( $title ) : ?>
								<h3 class="x-modal__title" id="x-modal-title-<?php echo intval( $popup_id ); ?>"><?php echo esc_html( $title ); ?></h3>
							<?php endif; ?>
							<?php if ( $desc ) : ?>
								<p class="x-modal__desc"><?php echo esc_html( $desc ); ?></p>
							<?php endif; ?>
							<?php if ( $form_id ) : ?>
								<div class="x-modal__form">
									<?php echo do_shortcode( '[fluentform id="' . $form_id . '"]' ); ?>
								</div>
							<?php endif; ?>
							<p class="x-modal__trust">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
								Bảo mật thông tin. XANH cam kết không spam.
							</p>
						</div>
					</div>
					<?php
					break;

				// ── Template: Video (YouTube) ──
				case 'video':
					$video_url = get_field( 'popup_video_url', $popup_id );
					$embed_url = xanh_youtube_embed_url( $video_url );
					?>
					<div class="x-modal__template x-modal__template--video">
						<div class="x-modal__video-wrap" data-embed-url="<?php echo esc_url( $embed_url ); ?>">
							<!-- iframe injected by JS on open to avoid autoplay drain -->
						</div>
					</div>
					<?php
					break;

			endswitch;
			?>

		</div><!-- .x-modal__body -->
	</div><!-- .x-modal__box -->
</div><!-- .x-modal -->

<?php
endwhile;
wp_reset_postdata();

// ── Preview auto-open: inject inline JS to immediately open the previewed popup ──
if ( $preview_popup_id && current_user_can( 'manage_options' ) ) : ?>
<script>
(function() {
	function autoOpenPreview() {
		var modal = document.getElementById('xanh-popup-<?php echo intval( $preview_popup_id ); ?>');
		if (!modal) return;
		// Use the global manager if available, else fallback.
		if (window.XanhPopupManager && window.XanhPopupManager.open) {
			window.XanhPopupManager.open(modal);
		} else {
			modal.classList.add('is-open');
			modal.setAttribute('aria-hidden', 'false');
			document.body.classList.add('is-popup-open');
		}
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function() { setTimeout(autoOpenPreview, 300); });
	} else {
		setTimeout(autoOpenPreview, 300);
	}
})();
</script>
<?php endif;

