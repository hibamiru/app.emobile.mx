<?php
/**
 * The template used for displaying Enlaces a Redes Sociales via ACF
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */
?>

<!-- ENLACES A REDES SOCIALES -->
<?php if ( get_field('enlace_a_facebook') ) { ?>
    <a href="<?php the_field('enlace_a_facebook'); ?>" title="Facebook" target="_blank"><i class="fa fa-facebook-square fa-3x"></i></a>
<?php } ?>
<?php if ( get_field('enlace_a_twitter') ) { ?> 
    <a href="<?php the_field('enlace_a_twitter'); ?>" title="Twitter" target="_blank"><i class="fa fa-twitter-square fa-3x"></i></a>
<?php } ?>
<?php if ( get_field('enlace_a_google_plus') ) { ?>
    <a href="<?php the_field('enlace_a_google_plus'); ?>" title="Google Plus" target="_blank"><i class="fa fa-google-plus-square fa-3x"></i></a>
<?php } ?>
<?php if ( get_field('enlace_a_youtube') ) { ?>
    <a href="<?php the_field('enlace_a_youtube'); ?>"title="Youtube" target="_blank"><i class="fa fa-youtube-square fa-3x"></i></a>
<?php } ?>