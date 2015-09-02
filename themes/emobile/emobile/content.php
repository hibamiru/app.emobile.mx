<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */
?>

<article class="articulo-resumen">
	<div class="fecha-resumen-articulos">
			<?php if ( 'post' == get_post_type() ) : ?>
                <h4><?php the_time('j'); ?></h4>
                <h5><?php the_time('F'); ?></h5> 
                <h5><?php the_time('Y'); ?></h5>
            <?php endif; ?>
    </div>
    <div class="content-resto-articulo">
        <header class="entry-header">
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
            <h4 class="entry-autor">Publicado por <?php the_author(); ?></h4>
        </header><!-- .entry-header -->
    
        <div class="entry-summary">
            <?php the_excerpt(); ?>
			<?php if ( has_post_thumbnail() ) {	?>
                <div class="entry-thumbnail"><?php the_post_thumbnail('medium'); ?></div>
            <?php } ?>
        </div><!-- .entry-summary -->
    </div>


</article>