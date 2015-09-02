<?php
/**
 * Template Name: Validar formulario
 * Description: Página para el escritorio de docentes
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

user_redirect();
acf_form_head();
$user_ID = get_current_user_id();
get_header();
$allow_form_validation = allow_form_validation(); ?>
<?php //echo '<pre style="display:block;">'; print_r($allow_form_validation); echo '</pre>'; // PRINT_R?>
	<?php while ( have_posts() ) : the_post(); ?>

    	<section id="completar-registro"  class="container content">

    		<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->

			<?php if ($allow_form_validation) : ?>
				<?php $options = array(
                                    'post_id'		=> 'user_' . $user_ID,
                                    'post_title'	=> false,
                                    'field_groups'	=> array(
                                                        21,
                                                        ),
                                    'submit_value'	=> 'Validar formulario',
                                    );
                ?>	
            <div class="group">    
                <?php acf_form($options); ?>
			</div>
            <?php else : ?>
				<div class="alert alert-danger alert-dismissible fade in" role="alert">
			    	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			    	<h4>¡Aún no terminas tu registro!</h4>
			    	<p>Te falta llenar campos obligatorios. Llena todo el formulario y verás el botón para validarlo e imprimir tu ficha. Puedes guardar tu progreso si necesitas interrumpir la actividad.</p>
			    </div>
			<?php endif; //Form validation ?>

		</section>
	<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>