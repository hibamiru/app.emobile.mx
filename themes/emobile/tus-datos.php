<?php
/**
 * Template Name: Tus datos
 * Description: Página que muestra los datos del participante
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

user_redirect();
get_header(); ?>
    
<?php while ( have_posts() ) : the_post(); ?>

    <section id="tus-datos"  class="container content">

        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header><!-- .entry-header -->

        <?php $tus_datos = participant_c_data(); ?>

            <div id="datos-personales" class="bloque-info row">
                <h3>Datos personales</h3>
                <p class="col-md-6"><img class="img-circle" src="<?php echo $tus_datos[p_foto]; ?>"></p>
                <p class="col-md-6"><strong>Nombre:</strong> <?php echo $tus_datos[p_nombre] . ' ' . $tus_datos[p_apellido_p] . ' ' . $tus_datos[p_apellido_m]; ?></p>
                <p class="col-md-6"><strong>CURP:</strong> <?php echo $tus_datos[p_curp]; ?></p>
                <p class="col-md-6"><strong>RFC:</strong> <?php echo $tus_datos[p_rfc]; ?></p>
                <p class="col-md-6"><strong>Sexo:</strong> <?php echo $tus_datos[p_sexo]; ?></p>
                <p class="col-md-6"><strong>Edad:</strong> <?php echo $tus_datos[p_edad]; ?></p>
                <p class="col-md-6"><strong>Teléfono particular:</strong> <?php echo $tus_datos[p_telefono_p]; ?></p>
                <p class="col-md-6"><strong>Teléfono celular:</strong> <?php echo $tus_datos[p_telefono_c]; ?></p>
                <p class="col-md-6"><strong>Correo electrónico:</strong> <?php echo $tus_datos[p_email]; ?></p>
                <p class="col-md-6"><strong>Sede de capacitación:</strong> <?php echo $tus_datos[p_sede_c]; ?></p>
                <p class="col-md-6"><strong>Líder creativo:</strong> <?php echo $tus_datos[p_lider_c]; ?></p>
                <p class="col-md-6"><strong>ID de iCloud:</strong> <?php echo $tus_datos[p_cuenta_icloud]; ?></p>
            </div>

            <div id="datos-laborales" class="bloque-info row">
                <h3>Datos laborales</h3>
                <p class="col-md-6"><strong>Función o grado que atiende:</strong> 
                    <?php // Recorro e imprimo array
                        foreach ($tus_datos[p_funcion_grado] as &$grado) {                            
                            echo $grado; 
                            if (end($tus_datos[p_funcion_grado]) != $grado) { echo ', '; } 
                            if ($grado == 'Dirección') : $es_director = true; endif;
                        } ?>
                </p>
                <p class="col-md-6"><strong>Nivel o servicio educativo:</strong> <?php echo $tus_datos[p_nivel_se]; ?></p>
                <p class="col-md-6"><strong>Modalidad:</strong> <?php echo $tus_datos[p_modalidad]; ?></p>
                <?php if ($tus_datos[p_modalidad_eesp]) { ?>
                	<p class="col-md-6"><strong>Modalidad educación especial:</strong> <?php echo $tus_datos[p_modalidad_eesp]; ?></p>
                <?php } ?>
                <p class="col-md-6"><strong>Sostenimiento:</strong> <?php echo $tus_datos[p_sostenimiento]; ?></p>
                <p class="col-md-6"><strong>Nombre del CT:</strong> <?php echo $tus_datos[p_nombre_ct]; ?></p>
                <p class="col-md-6"><strong>Clave del CT:</strong> <?php echo $tus_datos[p_clave_cct]; ?></p>
                <p class="col-md-6"><strong>Localidad:</strong> <?php echo $tus_datos[p_localidad]; ?></p>
                <p class="col-md-6"><strong>Municipio:</strong> <?php echo $tus_datos[p_municipio]; ?></p>
                <p class="col-md-6"><strong>Zona escolar:</strong> <?php echo $tus_datos[p_zona_escolar]; ?></p>
                <p class="col-md-6"><strong>Sector escolar:</strong> <?php echo $tus_datos[p_sector_escolar]; ?></p>
                <p class="col-md-6"><strong>Antiguedad en servicio educativo:</strong> <?php echo $tus_datos[p_antiguedad_se]; ?> años</p>
                <p class="col-md-6"><strong>Alumnos en su grupo:</strong> <?php echo $tus_datos[p_cantidad_alumnos]; ?></p>
                <p class="col-md-6"><strong>Teléfono del trabajo:</strong> <?php echo $tus_datos[p_telefono_t]; ?></p>
            </div>

            <div id="info-profesional" class="bloque-info row">
                <h3>Información profesional</h3>
                <p class="col-md-6"><strong>Participa en carrera magisterial:</strong> <?php echo $tus_datos[p_participa_cm]; ?></p>
                <?php if  ($tus_datos[p_participa_cm] == 'Sí') : ?>
                    <p class="col-md-6"><strong>Nivel:</strong> <?php echo $tus_datos[p_nivel_cm]; ?> vertiente <?php echo $tus_datos[p_vertiente_cm]; ?></p>
                <?php endif; ?>
                <p class="col-md-6"><strong>Carrera:</strong> <?php echo $tus_datos[p_licenciatura]; ?></p>
                <p class="col-md-6"><strong>Institución donde cursó:</strong> <?php echo $tus_datos[p_institucion]; ?></p>
                <p class="col-md-6"><strong>Grado máximo de estudios:</strong> <?php echo $tus_datos[p_grado_estudios]; ?></p>
                <p class="col-md-6"><strong>Institución último grado de estudios:</strong> <?php echo $tus_datos[p_institucion_uge]; ?></p>
                <p class="col-md-6"><strong>Estudios que cursa actualmente:</strong> <?php echo $tus_datos[p_estudios_actuales]; ?></p>
            </div>
            
            <div id="ipad-num" class="bloque-info row ">
                <h3>Numero de serie de tu ipad: </h3>
                <?php if ($tus_datos[p_nserie_ipad] == '' ) { ?>
                    <p>Aún no tienes un número de serie asignado; 
                        <a href="<?php bloginfo( 'url' ); ?>/registra-tu-ipad/" class="btn btn-info">Asignar un iPad</a>
                    </p>
                <?php } else { ?>                     
                    <p><strong><?php echo $tus_datos[p_nserie_ipad]; ?></strong></p>
                <?php } ?>
            </div>

            <?php if ($es_director) : ?>
                <div id="datos-ct" class="bloque-info row ">
                    <h3>Datos adicionales del centro de trabajo</h3>
                    <p class="col-md-6"><strong>Organización:</strong> <?php echo $tus_datos[p_organizacion]; ?></p>
                    <p class="col-md-6"><strong>Grupos que participan:</strong> <?php echo $tus_datos[p_ngrupos_participantes]; ?></p>
                        <?php for ($i = 1; $i <= $tus_datos[p_ngrupos_participantes]; $i++) { 
                             $gradop = 'p_ct_gra_' . $i;
                             $grupop = 'p_ct_gru_' . $i;
                            ?>
                            <p class="col-md-6"><strong>Grupo <?php echo $i; ?>:</strong> <?php echo $tus_datos[$gradop] . ' ' . $tus_datos[$grupop]; ?></p> 
                        <?php } ?>                        
                    <p class="col-md-6"><strong>Números de serie de ipads:</strong> <br>
                        <?php $num = 1;
                            foreach ($tus_datos[p_ct_nserie_ipads] as &$serie) {                            
                                echo $num . ') ' . $serie . '<br>'; 
                                $num++;
                            } ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="bloque-info row">
                <p align="center"><a href="<?php bloginfo( 'url' ); ?>/imprimir-pdf/" target="_blank" class="btn btn-success">Ver ficha de registro en PDF</a></p>
            </div>
            
    </section>

    <?php //echo '<pre style="display:block;">'; print_r($tus_datos); echo '</pre>'; // PRINT_R ?>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>