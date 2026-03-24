<?php
/**
 * Custom Nav Walker — Outputs menu links with theme-specific classes.
 *
 * Supports two contexts via constructor:
 *  - 'desktop' → nav-link classes for desktop horizontal menu + dropdown sub-menus.
 *  - 'mobile'  → mobile-nav-link classes + accordion sub-menus.
 *
 * Supports up to 3 levels of depth.
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
	 * Starts the list before the elements are added — <ul class="sub-menu">.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		if ( 'mobile' === $this->context ) {
			$output .= "\n{$indent}<ul class=\"mobile-sub-menu\">\n";
		} else {
			$sub_class = ( $depth === 0 ) ? 'sub-menu' : 'sub-menu sub-menu--nested';
			$output .= "\n{$indent}<ul class=\"{$sub_class}\">\n";
		}
	}

	/**
	 * Ends the list after the elements are added — </ul>.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "{$indent}</ul>\n";
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
		$has_children = ! empty( $args ) && ! empty( $args->walker ) && $this->has_children;

		// Build <li> classes.
		$li_classes = [];
		if ( $has_children ) {
			$li_classes[] = 'menu-item-has-children';
		}
		if ( 'mobile' === $this->context && $has_children ) {
			$li_classes[] = 'mobile-has-children';
		}
		$li_class_str = ! empty( $li_classes ) ? ' class="' . esc_attr( implode( ' ', $li_classes ) ) . '"' : '';

		$output .= '<li' . $li_class_str . '>';

		$url   = esc_url( $item->url );
		$title = esc_html( $item->title );

		// Detect active state from WP's auto-assigned classes.
		$is_active = false;
		if ( ! empty( $item->classes ) && is_array( $item->classes ) ) {
			$active_classes = [ 'current-menu-item', 'current-menu-ancestor', 'current-menu-parent', 'current_page_item', 'current_page_parent' ];
			$is_active = (bool) array_intersect( $active_classes, $item->classes );

			// Fix: WP falsely marks Blog page as current_page_parent on CPT archives.
			if ( $is_active && ! is_singular( 'post' ) && ! is_home() && ! is_category() && ! is_tag() ) {
				$blog_page_id = (int) get_option( 'page_for_posts' );
				if ( $blog_page_id && (int) $item->object_id === $blog_page_id ) {
					$is_active = false;
				}
			}
		}

		// Fallback: URL prefix matching for detail/child pages.
		if ( ! $is_active && ! empty( $item->url ) ) {
			$menu_path    = wp_parse_url( $item->url, PHP_URL_PATH );
			$current_path = wp_parse_url( home_url( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH );

			if ( $menu_path && $current_path ) {
				$menu_path    = trailingslashit( $menu_path );
				$current_path = trailingslashit( $current_path );

				if ( '/' !== $menu_path && str_starts_with( $current_path, $menu_path ) ) {
					$is_active = true;
				}
			}
		}

		if ( 'mobile' === $this->context ) {
			$this->render_mobile_link( $output, $url, $title, $is_active, $has_children, $depth );
		} else {
			$this->render_desktop_link( $output, $url, $title, $is_active, $has_children, $depth );
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

	/* ══════════════════════════════════════════════════════
	   Private Renderers
	   ══════════════════════════════════════════════════════ */

	/**
	 * Render a desktop nav link.
	 */
	private function render_desktop_link( &$output, $url, $title, $is_active, $has_children, $depth ) {
		if ( $depth === 0 ) {
			// Top-level link.
			$classes = 'nav-link text-white/90 text-sm font-medium uppercase tracking-[0.1em] hover:text-white transition-colors duration-300 relative';
			if ( $is_active ) {
				$classes .= ' active';
			}

			$output .= '<a href="' . $url . '" class="' . esc_attr( $classes ) . '">';
			$output .= $title;
			if ( $has_children ) {
				$output .= '<svg class="submenu-arrow" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
			}
			$output .= '</a>';
		} else {
			// Sub-menu link (depth 1, 2, ...).
			$classes = 'sub-menu__link';
			if ( $is_active ) {
				$classes .= ' active';
			}

			$output .= '<a href="' . $url . '" class="' . esc_attr( $classes ) . '">';
			$output .= $title;
			if ( $has_children ) {
				$output .= '<svg class="submenu-arrow--nested" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 6 15 12 9 18"></polyline></svg>';
			}
			$output .= '</a>';
		}
	}

	/**
	 * Render a mobile nav link.
	 */
	private function render_mobile_link( &$output, $url, $title, $is_active, $has_children, $depth ) {
		$indent_class = ( $depth > 0 ) ? ' mobile-nav-link--sub' : '';
		$depth_class  = ( $depth > 0 ) ? ' mobile-nav-link--depth-' . $depth : '';

		$classes = 'mobile-nav-link flex items-center justify-between px-6 py-4 text-white/90 text-[11px] md:text-xs font-semibold uppercase tracking-[0.15em] border-b-[0.5px] border-white/20 hover:bg-white/10 hover:text-white transition-colors duration-300' . $indent_class . $depth_class;
		if ( $is_active ) {
			$classes .= ' active';
		}

		if ( $has_children ) {
			// Wrap link + toggle button in a flex container.
			$output .= '<div class="mobile-nav-parent">';
			$output .= '<a href="' . $url . '" class="' . esc_attr( $classes ) . '">';
			$output .= $title;
			$output .= '</a>';
			// Toggle button — separate from the link so tapping the link navigates.
			$output .= '<button class="submenu-toggle" aria-label="' . esc_attr__( 'Mở menu con', 'xanh' ) . '" aria-expanded="false">';
			$output .= '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
			$output .= '</button>';
			$output .= '</div>';
		} else {
			$output .= '<a href="' . $url . '" class="' . esc_attr( $classes ) . '">';
			$output .= $title;
			$output .= '<i data-lucide="chevron-right" class="w-3.5 h-3.5 text-white/40"></i>';
			$output .= '</a>';
		}
	}
}
