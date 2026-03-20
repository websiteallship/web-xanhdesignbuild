<?php
/**
 * Template Name: Liên Hệ
 * Contact Page Template.
 *
 * Displays the "Liên Hệ" page with all sections.
 * Requires ACF field group: group_contact_page.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main" role="main">

	<?php
	/**
	 * Hook: xanh_before_hero
	 *
	 * @param int $post_id Current page ID.
	 */
	do_action( 'xanh_before_hero', get_the_ID() );
	?>

	<?php // Section 1: Hero Banner ?>
	<?php get_template_part( 'template-parts/hero/hero', 'contact' ); ?>

	<?php // Section 2: Contact Block — Form + Info + Map ?>
	<?php get_template_part( 'template-parts/sections/section', 'contact-block' ); ?>

	<?php // Section 3: FAQ Accordion ?>
	<?php get_template_part( 'template-parts/sections/section', 'contact-faq' ); ?>

	<?php
	/**
	 * Hook: xanh_after_content
	 */
	do_action( 'xanh_after_content' );
	?>

</main>

<?php
get_footer();
