<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Fourteen
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Shopme
 * @since Shopme 1.0
 */


get_header(); ?>

<?php if ( have_posts() ) : ?>

	<?php $shopme_results = shopme_which_archive(); ?>

	<?php if (!empty($shopme_results)): ?>
		<?php echo shopme_title(
			array(
				'title' => $shopme_results,
				'heading' => 'h1'
			)
		); ?>
	<?php endif; ?>

	<?php $shopme_blog_style = shopme_custom_get_option('blog_style'); ?>

	<div class="post-area">

		<?php echo shopme_pagination('', array('tag' => 'header', 'class' => 'top_box', 'blog_style' => $shopme_blog_style)); ?>

		<ul class="list_of_entries type-1 <?php echo esc_attr($shopme_blog_style) ?>">

			<?php
				// Start the loop.
				while ( have_posts() ) : the_post();
					get_template_part( 'loop/loop', 'index' );
				endwhile;
			?>

		</ul><!--/ .list_of_entries-->

		<?php echo shopme_pagination(); ?>

	</div><!--/ .post-area-->

<?php else:

	// If no content, include the "No posts found" template.
	get_template_part( 'content', 'none' );

endif; ?>

<?php get_footer(); ?>