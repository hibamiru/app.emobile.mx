<?php
/**
 * Template Name: Agregar planeación
 * Description: Página para agregar nuevas planeaciones
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

//user_redirect();
acf_form_head();
$user_ID = get_current_user_id();
$user_meta	= get_user_meta( $user_ID );
if ($user_meta['p_nivel_se'][0] != 'Educación especial') {
	$load_form = TRUE;
} else {
	$load_form = FALSE;
}
get_header();
//the_post();
$allow_form_validation = allow_form_validation(); ?>
<?php //echo '<pre style="display:block;">'; print_r($user_meta); echo '</pre>'; // PRINT_R?>
	<?php while ( have_posts() ) : the_post(); ?>

    	<section id="formulario-mobile"  class="container content">

    		<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->

	    	<?php if ($load_form) { ?>
				<?php $options = array(
                                    'post_id'		=> 'new',
                                    'post_title'	=> false,
                                    'field_groups'	=> array(
                                                        585,
                                                        ),
                                    'updated_message' => 'Información guardada, continua agregando una nueva planeación.',
                                    'submit_value'	=> 'Guardar borrador',
                                    );
                ?>
                <div class="group">
                    <?php acf_form($options); ?>
                </div>
            
            <?php } else { ?>
            	<p>Aún no puedes crear planeaciones debido a que estamos actualizando datos en tu perfil de forma manual. Te notificaremos cuando ya puedas agregarlas.<p>
                <p>Si ves este mensaje y enseñas en Educación Especial, NO necesitas reportar este incidente. De lo contrario solicita asistencia solo si NO enseñas en educación especial.</p>
            <?php } ?>

		</section>
	<?php endwhile; // end of the loop. ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script"<?php plantilla_url(); ?>/js/editar_planeacion.js"></script>
<?php get_footer(); ?>