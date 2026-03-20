<?php
/**
 * Custom Nav Walker — Outputs menu links with theme-specific classes.
 *
 * Supports two contexts via constructor:
 *  - 'desktop' → nav-link classes for desktop horizontal menu.
 *  - 'mobile'  → mobile-nav-link classes + chevron icon.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Xanh_Nav_Walker
 */
class Xanh_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Context: 'desktop' or 'mobile'.
	 *
	 * @var string
	 */
	private $context;

	/**
	 * Constructor.
	 *
	 * @param string $context 'desktop' or 'mobile'.
	 */
	public function __construct( $context = 'desktop' ) {
		$this->context = $context;
	}

	/**
	 * Starts the element output (the <li> and <a> tag).
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu arguments.
	 * @param int      $id     Current item ID.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$output .= '<li>';

		$url   = esc_url( $item->url );
		$title = esc_html( $item->title );

		if ( 'mobile' === $this->context ) {
			$classes = 'mobile-nav-link flex items-center justify-between px-6 py-4 text-white/90 text-[11px] md:text-xs font-semibold uppercase tracking-[0.15em] border-b-[0.5px] border-white/20 hover:bg-white/10 hover:text-white transition-colors duration-300';

			$output .= '<a href="' . $url . '" class="' . esc_attr( $classes ) . '">';
			$output .= $title;
			$output .= '<i data-lucide="chevron-right" class="w-3.5 h-3.5 text-white/40"></i>';
			$output .= '</a>';
		} else {
			$classes = 'nav-link text-white/90 text-sm font-medium uppercase tracking-[0.1em] hover:text-white transition-colors duration-300 relative';

			$output .= '<a href="' . $url . '" class="' . esc_attr( $classes ) . '">';
			$output .= $title;
			$output .= '</a>';
		}
	}

	/**
	 * Ends the element output (closing </li>).
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}
