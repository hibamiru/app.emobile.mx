<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */
?>

<article>
	
    <header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->	
    
    <footer class="entry-footer">
		<?php the_social_share(); ?>
		
	</footer><!-- .entry-footer -->
    
</article>
