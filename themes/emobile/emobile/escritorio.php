<?php
/**
 * Template Name: Escritorio
 * Description: Página para el escritorio de docentes
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

user_redirect();
get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<section id="escritorio"  class="container content">

        <?php get_template_part( 'content', 'page' ); ?>


        <?php $tus_datos = participant_c_data(); // Obtengo datos de participante ?>

        <div id="botones-escritorio" class="row group">
          <!-- nueva planeación -->
          <div class="col-md-4">
            <a class="boton-escritorio" href="<?php inicio_url(); ?>/agregar-planeacion/">
                <span class="ico-escritorio ico-nuevoplan"></span>
                <h3>Nueva planeación</h3>
            </a>
          </div>
          <!-- tus datos -->
          <div class="col-md-4">
            <a class="boton-escritorio" href="<?php inicio_url(); ?>/tus-datos/">
                <span class="ico-escritorio ico-tusdatos"></span>
                <h3>Tus datos</h3>
            </a>
          </div>
          <!-- ficha de registro -->
          <div class="col-md-4">
            <a class="boton-escritorio" href="<?php inicio_url(); ?>/imprimir-pdf/" target="_blank">
                <span class="ico-escritorio ico-pdf"></span>
                <h3>Ficha de registro</h3>
            </a>
          </div>
        </div>

        <h2 class="titulo-seccion">Mis planes de clase</h2>
        
        <?php $planeaciones = query_user_plans(); // Obtengo datos de planeaciones ?>

        <div id="planeaciones" class="row group">

            <?php if ($planeaciones) { ?>
                <?php foreach ($planeaciones as $planeacion) { ?>
                <?php //echo '<pre style="display:block;">'; print_r($planeacion); echo '</pre>'; // PRINT_R ?>

                    <div class="col-md-6">
                        <div class="planeacion">
                            <div class="row group">
                                <!-- icono de planeación -->
                                <div class="col-sm-2">
                                    <?php if ($planeacion['asignatura'] == 'Español') { ?>
                                        <span class="ico-planeacion ico-esp"></span> 
                                    <?php } else { ?>
                                        <span class="ico-planeacion ico-mat"></span> 
                                    <?php } ?>
                                </div>
                                <!-- Asignatura -->
                                <div class="col-sm-3">
                                    <strong><?php echo $planeacion['asignatura']; ?></strong>
                                </div>
                                <!-- Grado -->
                                <div class="col-sm-3">
                                    <strong><?php echo $planeacion['grado']; ?> grado</strong>
                                </div>
                                <!-- Bloque -->
                                <div class="col-sm-3">
                                    <strong><?php echo $planeacion['bloque']; ?> bloque</strong>
                                </div>
                            </div>
                            
                            <p class="aprendizaje group"><?php echo $planeacion['aprendizaje_esp']; ?></p>

                            <div class="row group">
                                <div class="col-xs-3">
                                    <a class="btn btn-default btn-sm editar" href="<?php echo $planeacion['url_editar']; ?>">
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar
                                    </a>
                                </div>
                                <div class="col-xs-3">
                                    <a class="btn btn-default btn-sm publicar" href="<?php echo $planeacion['url_publicar']; ?>">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Publicar
                                    </a>
                                </div>
                                <div class="col-xs-3">
                                    <a class="btn btn-default btn-sm borrar" href="<?php echo $planeacion['url_imp_pdf']; ?>">
                                        <span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span> PDF
                                    </a>
                                </div>
                                <div class="col-xs-2">
                                    <a class="btn btn-default btn-sm borrar" href="<?php echo wp_nonce_url( get_bloginfo('url') . "/wp-admin/post.php?action=delete&post=" . $planeacion['ID'], 'delete-post_' . $planeacion['ID']); ?>" onclick="return confirm('¿Seguro que deseas borrarlo? No puedes deshacer esta acción.')">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Borrar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            <?php }?>

        </div> <!-- #planeaciones -->


        <?php // echo '<pre style="display:block;">'; print_r($planeaciones); echo '</pre>'; // IMPRIME ARRAY ?>
       

        <?php if ($tus_datos[p_nserie_ipad] == '' ) { ?>   
        	<div class="alert alert-warning alert-dismissible fade in" role="alert">
            	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>    	
            	<p><strong>Aún no asignas el número de serie de tu iPad.<br> 
            	</strong> Una vez que te lo hayan proporcionado puedes asignarlo en la sección de "Tus datos" o haciendo click en el botón de abajo</p>
            	<p><a href="<?php bloginfo( 'url' ); ?>/registra-tu-ipad/" class="btn btn-danger">Asignar un iPad</a></p>
            </div>
        <?php } ?>
    

	</section>    

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>