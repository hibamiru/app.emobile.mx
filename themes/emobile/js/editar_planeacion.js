$(document).ready(function() {

	//PARTE 1; Modificación de de elementos de formulario
	$('#acf-pl_autor').addClass('nothere'); 
	$('#acf-pl_aprendizaje_esp').addClass('cols4'); 
	//Matematicas prim y sec
	$('#acf-pl_eje').addClass('cols2');
	$('#acf-pl_tema').addClass('cols2');
	$('#acf-pl_contenido').addClass('cols4');
	//Español prim y sec
	$('#acf-pl_componente').addClass('cols2');
	$('#acf-pl_ambito').addClass('cols2');
	$('#acf-pl_tipo_texto').addClass('cols2');
	$('#acf-pl_psl').addClass('cols2');
	$('#acf-pl_tema_ref').addClass('cols2');
	$('#acf-pl_ppdp').addClass('cols2');
	//Preescolar
	$('#acf-pl_campo_f').addClass('cols3');
	$('#acf-pl_tipo_sa').addClass('cols2');
	$('#acf-pl_titulo_sa').addClass('cols2');


	//PARTE 2; Modificación de de elementos de formulario
	$('#acf-pl_topico_g').addClass('cols4'); 
	$('#acf-pl_conocimientos_pr').addClass('cols4');
	$('#acf-pl_otras_ar').addClass('cols2');
	$('#acf-pl_porque_ia').addClass('cols2');

	//PARTE 3; Modificación de de elementos de formulario
	$('#acf-pl_propuesta_1').addClass('prop prop1 cols4'); 
	$('#acf-pl_propuesta_2').addClass('prop prop2 cols4');
	$('#acf-pl_propuesta_3').addClass('prop prop3 cols4');
	$('#acf-pl_propuesta_4').addClass('prop prop4 cols4');
	$('#acf-pl_propuesta_5').addClass('prop prop5 cols4');
	$('#acf-pl_propuesta_6').addClass('prop prop6 cols4');

	//PARTE 4; Modificación de de elementos de formulario

	//Encabezados del grid
	$( '<div id="acf-pl_0_1" class="p4cell"><p>&nbsp</p></div>' ).insertBefore( "#acf-pl_1_1" );
	$( '<div id="acf-pl_0_2" class="p4cell"><p>Lingüistica</p></div>' ).insertBefore( "#acf-pl_1_1" );
	$( '<div id="acf-pl_0_3" class="p4cell"><p>Lógico matemática</p></div>' ).insertBefore( "#acf-pl_1_1" );
	$( '<div id="acf-pl_0_4" class="p4cell"><p>Musical</p></div>' ).insertBefore( "#acf-pl_1_1" );
	$( '<div id="acf-pl_0_5" class="p4cell"><p>Corporal cinéstesica</p></div>' ).insertBefore( "#acf-pl_1_1" );
	$( '<div id="acf-pl_0_6" class="p4cell"><p>Espacial</p></div>' ).insertBefore( "#acf-pl_1_1" );
	$( '<div id="acf-pl_0_7" class="p4cell"><p>Interpersonal</p></div>' ).insertBefore( "#acf-pl_1_1" );
	$( '<div id="acf-pl_0_8" class="p4cell"><p>Intrapersonal</p></div>' ).insertBefore( "#acf-pl_1_1" );
	$( '<div id="acf-pl_0_9" class="p4cell"><p>Naturalista</p></div>' ).insertBefore( "#acf-pl_1_1" );
	$( '<div id="acf-pl_0_10" class="p4cell"><p>Existencial</p></div>' ).insertBefore( "#acf-pl_1_1" );	
	
	//Primera columna del grid
	$( '<div id="acf-pl_1_0" class="p4cell"><p>Legislativo</p></div>' ).insertBefore( "#acf-pl_1_1" );
	$( '<div id="acf-pl_2_0" class="p4cell"><p>Ejecutivo</p></div>' ).insertBefore( "#acf-pl_2_1" );
	$( '<div id="acf-pl_3_0" class="p4cell"><p>Judicial</p></div>' ).insertBefore( "#acf-pl_3_1" );
	$( '<div id="acf-pl_4_0" class="p4cell"><p>Monárquico</p></div>' ).insertBefore( "#acf-pl_4_1" );
	$( '<div id="acf-pl_5_0" class="p4cell"><p>Jerárquico</p></div>' ).insertBefore( "#acf-pl_5_1" );
	$( '<div id="acf-pl_6_0" class="p4cell"><p>Oligárquico</p></div>' ).insertBefore( "#acf-pl_6_1" );
	$( '<div id="acf-pl_7_0" class="p4cell"><p>Anárquico</p></div>' ).insertBefore( "#acf-pl_7_1" );
	$( '<div id="acf-pl_8_0" class="p4cell"><p>Global</p></div>' ).insertBefore( "#acf-pl_8_1" );
	$( '<div id="acf-pl_9_0" class="p4cell"><p>Local</p></div>' ).insertBefore( "#acf-pl_9_1" );
	$( '<div id="acf-pl_10_0" class="p4cell"><p>Interno</p></div>' ).insertBefore( "#acf-pl_10_1" );
	$( '<div id="acf-pl_11_0" class="p4cell"><p>Externo</p></div>' ).insertBefore( "#acf-pl_11_1" );
	$( '<div id="acf-pl_12_0" class="p4cell"><p>Liberal</p></div>' ).insertBefore( "#acf-pl_12_1" );
	$( '<div id="acf-pl_13_0" class="p4cell"><p>Conservador</p></div>' ).insertBefore( "#acf-pl_13_1" );

	//Agrego la clase p4cell a cada una de las celdas del grid
	for (f = 1; f < 14; f++) {
		for (c = 1; c < 10; c++) {
			$('#acf-pl_'+ f +'_'+ c).addClass('p4cell');
			$('#acf-pl_'+ f +'_'+ c+ ' select option:first').text("")
		}
	}



	//PARTE 5; Modificación de de elementos de formulario
	$('#acf-pl_consideracion_1').addClass('cols2'); 
	$('#acf-pl_consideracion_2').addClass('cols2');
	$('#acf-pl_consideracion_3').addClass('cols2');
	$('#acf-pl_consideracion_4').addClass('cols2');
	$('#acf-pl_consideracion_5').addClass('cols2');
	$('#acf-pl_consideracion_6').addClass('cols2');

	//PARTE 6; Modificación de de elementos de formulario
	$('#acf-pl_recursos_tic').addClass('cols4'); 

	//PARTE 6; Modificación de de elementos de formulario
	$('#acf-pl_secuencia_aplicacion').addClass('cols4'); 


});