<?php
/**
 * Template Name: Serie de iPad
 * Description: Página para registrar el número de ipad
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

user_redirect();
acf_form_head();
$user_ID = get_current_user_id();
get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

    	<section id="completar-registro"  class="container content">

    		<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->

	    	<?php $options = array(
								'post_id'		=> 'user_' . $user_ID,
								'post_title'	=> false,
								'field_groups'	=> array(
													48,
													),
								'submit_value'	=> 'Guardar número de serie',
								);
			?>
			<p>Asegurate de que el número de seríe esté bien escrito antes de continuar, una vez que registres el número de serie de tu iPad no podrás modificarlo</p>
			<div class="group">
				<?php acf_form($options); ?>
			</div>

		</section>
	<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>