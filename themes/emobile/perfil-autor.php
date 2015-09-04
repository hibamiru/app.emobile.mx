<?php
/**
 * Template Name: Perfil autor
 * Description: Página personalizada de ejemplo
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

user_redirect();
get_header(); ?>

		<div id="primary-full">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>


				    <section id="tus-datos"  class="container content">

				        <header class="entry-header">
				            <h1 class="entry-title"><?php the_title(); ?></h1>
				        </header><!-- .entry-header -->

						<?php $perfil_autor = perfil_autor( get_query_var('id_autor') );?>

			            <div id="datos-personales" class="bloque-info row">
			                <h3>Datos personales</h3>
			                <p class="col-md-6"><img class="img-circle" src="<?php echo $perfil_autor[p_foto]; ?>"></p>
			                <p class="col-md-6"><strong>Nombre:</strong> <?php echo $perfil_autor[p_nombre] . ' ' . $perfil_autor[p_apellido_p] . ' ' . $perfil_autor[p_apellido_m]; ?></p>
			                <p class="col-md-6"><strong>CURP:</strong> <?php echo $perfil_autor[p_curp]; ?></p>
			                <p class="col-md-6"><strong>RFC:</strong> <?php echo $perfil_autor[p_rfc]; ?></p>
			                <p class="col-md-6"><strong>Sexo:</strong> <?php echo $perfil_autor[p_sexo]; ?></p>
			                <p class="col-md-6"><strong>Edad:</strong> <?php echo $perfil_autor[p_edad]; ?></p>
			                <p class="col-md-6"><strong>Teléfono particular:</strong> <?php echo $perfil_autor[p_telefono_p]; ?></p>
			                <p class="col-md-6"><strong>Teléfono celular:</strong> <?php echo $perfil_autor[p_telefono_c]; ?></p>
			                <p class="col-md-6"><strong>Correo electrónico:</strong> <?php echo $perfil_autor[p_email]; ?></p>
			                <p class="col-md-6"><strong>Sede de capacitación:</strong> <?php echo $perfil_autor[p_sede_c]; ?></p>
			                <p class="col-md-6"><strong>Líder creativo:</strong> <?php echo $perfil_autor[p_lider_c]; ?></p>
			                <p class="col-md-6"><strong>ID de iCloud:</strong> <?php echo $perfil_autor[p_cuenta_icloud]; ?></p>
			            </div>

			            <div id="datos-laborales" class="bloque-info row">
			                <h3>Datos laborales</h3>
			                <p class="col-md-6"><strong>Función o grado que atiende:</strong> 
			                    <?php // Recorro e imprimo array
			                    	if ($perfil_autor[p_funcion_grado]) {
				                        foreach ($perfil_autor[p_funcion_grado] as &$grado) {                            
				                            echo $grado; 
				                            if (end($perfil_autor[p_funcion_grado]) != $grado) { echo ', '; } 
				                            if ($grado == 'Dirección') : $es_director = true; endif;
				                        } 
			                        }?>
			                </p>
			                <p class="col-md-6"><strong>Nivel o servicio educativo:</strong> <?php echo $perfil_autor[p_nivel_se]; ?></p>
			                <p class="col-md-6"><strong>Modalidad:</strong> <?php echo $perfil_autor[p_modalidad]; ?></p>
			                <?php if ($perfil_autor[p_modalidad_eesp]) { ?>
			                	<p class="col-md-6"><strong>Modalidad educación especial:</strong> <?php echo $perfil_autor[p_modalidad_eesp]; ?></p>
			                <?php } ?>
			                <p class="col-md-6"><strong>Sostenimiento:</strong> <?php echo $perfil_autor[p_sostenimiento]; ?></p>
			                <p class="col-md-6"><strong>Nombre del CT:</strong> <?php echo $perfil_autor[p_nombre_ct]; ?></p>
			                <p class="col-md-6"><strong>Clave del CT:</strong> <?php echo $perfil_autor[p_clave_cct]; ?></p>
			                <p class="col-md-6"><strong>Localidad:</strong> <?php echo $perfil_autor[p_localidad]; ?></p>
			                <p class="col-md-6"><strong>Municipio:</strong> <?php echo $perfil_autor[p_municipio]; ?></p>
			                <p class="col-md-6"><strong>Zona escolar:</strong> <?php echo $perfil_autor[p_zona_escolar]; ?></p>
			                <p class="col-md-6"><strong>Sector escolar:</strong> <?php echo $perfil_autor[p_sector_escolar]; ?></p>
			                <p class="col-md-6"><strong>Antiguedad en servicio educativo:</strong> <?php echo $perfil_autor[p_antiguedad_se]; ?> años</p>
			                <p class="col-md-6"><strong>Alumnos en su grupo:</strong> <?php echo $perfil_autor[p_cantidad_alumnos]; ?></p>
			                <p class="col-md-6"><strong>Teléfono del trabajo:</strong> <?php echo $perfil_autor[p_telefono_t]; ?></p>
			            </div>

			            <div id="info-profesional" class="bloque-info row">
			                <h3>Información profesional</h3>
			                <p class="col-md-6"><strong>Participa en carrera magisterial:</strong> <?php echo $perfil_autor[p_participa_cm]; ?></p>
			                <?php if  ($perfil_autor[p_participa_cm] == 'Sí') : ?>
			                    <p class="col-md-6"><strong>Nivel:</strong> <?php echo $perfil_autor[p_nivel_cm]; ?> vertiente <?php echo $perfil_autor[p_vertiente_cm]; ?></p>
			                <?php endif; ?>
			                <p class="col-md-6"><strong>Carrera:</strong> <?php echo $perfil_autor[p_licenciatura]; ?></p>
			                <p class="col-md-6"><strong>Institución donde cursó:</strong> <?php echo $perfil_autor[p_institucion]; ?></p>
			                <p class="col-md-6"><strong>Grado máximo de estudios:</strong> <?php echo $perfil_autor[p_grado_estudios]; ?></p>
			                <p class="col-md-6"><strong>Institución último grado de estudios:</strong> <?php echo $perfil_autor[p_institucion_uge]; ?></p>
			                <p class="col-md-6"><strong>Estudios que cursa actualmente:</strong> <?php echo $perfil_autor[p_estudios_actuales]; ?></p>
			            </div>
			            
			            <?php if ($es_director) : ?>
			                <div id="datos-ct" class="bloque-info row ">
			                    <h3>Datos adicionales del centro de trabajo</h3>
			                    <p class="col-md-6"><strong>Organización:</strong> <?php echo $perfil_autor[p_organizacion]; ?></p>
			                    <p class="col-md-6"><strong>Grupos que participan:</strong> <?php echo $perfil_autor[p_ngrupos_participantes]; ?></p>
			                        <?php for ($i = 1; $i <= $perfil_autor[p_ngrupos_participantes]; $i++) { 
			                             $gradop = 'p_ct_gra_' . $i;
			                             $grupop = 'p_ct_gru_' . $i;
			                            ?>
			                            <p class="col-md-6"><strong>Grupo <?php echo $i; ?>:</strong> <?php echo $perfil_autor[$gradop] . ' ' . $perfil_autor[$grupop]; ?></p> 
			                        <?php } ?>                        
			                    <p class="col-md-6"><strong>Números de serie de ipads:</strong> <br>
			                        <?php $num = 1;
			                            foreach ($perfil_autor[p_ct_nserie_ipads] as &$serie) {                            
			                                echo $num . ') ' . $serie . '<br>'; 
			                                $num++;
			                            } ?>
			                    </p>
			                </div>
			            <?php endif; ?>



				    </section>
					<section id="escritorio"  class="container content">

				        <h2 class="titulo-seccion">Planeaciones del autor</h2>
				        
				        <?php $planeaciones = query_autor_plans( get_query_var('id_autor') ); // Obtengo datos de planeaciones ?>

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
				                                    <a class="btn btn-default btn-sm borrar" href="<?php echo $planeacion['url_imp_pdf']; ?>">
				                                        <span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span> PDF
				                                    </a>
				                                </div>
				                                
				                            </div>
				                        </div>
				                    </div>

				                <?php } ?>
				            <?php }?>

				        </div> <!-- #planeaciones -->
					</section>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>