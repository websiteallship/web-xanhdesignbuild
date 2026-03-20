<?php
/**
 * Template Part: Marquee Divider.
 *
 * Continuous text ribbon loop between Hero and Empathy sections.
 * ACF field: marquee_items (Repeater: text).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$marquee_items = get_field( 'marquee_items' );

$default_items = [
	[ 'text' => 'kiến tạo tổ ấm bình yên' ],
	[ 'text' => 'minh bạch từ viên gạch đầu tiên' ],
	[ 'text' => 'thiết kế xanh cho thế hệ mai sau' ],
	[ 'text' => 'thi công trọn gói — cam kết không phát sinh' ],
];

$items = $marquee_items ?: $default_items;
?>

<div class="marquee relative z-30 shadow-2xl shadow-black/50">
	<div class="marquee__track">
		<?php
		// Render items twice for seamless CSS animation loop.
		for ( $loop = 0; $loop < 2; $loop++ ) :
			foreach ( $items as $item ) :
				$text = $marquee_items
					? esc_html( $item['text'] ?? '' )
					: esc_html( $item['text'] );
				?>
				<span class="marquee__text"><?php echo $text; // phpcs:ignore -- pre-escaped via esc_html on L35-37. ?> <span>✦</span></span>
			<?php endforeach;
		endfor;
		?>
	</div>
</div>
