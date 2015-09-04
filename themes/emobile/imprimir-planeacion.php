<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 'On');
/**
 * Template Name: Imprimir planeación
 * Description: Esta página genera un pdf con los datos de una planeación.
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */
//user_redirect();
?>
<?php $planeacion_info = planeacion_pdf_info( get_query_var('id_planeacion') );?>
<?php //echo '<pre style="display:block;">'; print_r($planeacion_info); echo '</pre>'; // PRINT_R ?>

<?php while ( have_posts() ) : the_post(); ?>

<?php $participante = participant_c_data(); ?>
<?php //echo '<pre style="display:block;">'; print_r($participante); echo '</pre>'; // PRINT_R ?>
<?php require(TEMPLATEPATH . '/inc/fpdf/fpdf.php'); ?>
<?php define('FPDF_FONTPATH', TEMPLATEPATH . '/inc/fpdf/font'); //Absolute font path. Si lo defines en el config, podria ser asi: BASEPATH."application/libraries/font/"



//------------------------PREPARO DATOS A USAR--------------------------//
$title = 'Plan de clase Educación Mobile';
$nombre_participante = $participante['p_nombre'] . ' ' . $participante['p_apellido_p'] . ' ' . $participante['p_apellido_m'];
$colores = array ( '33, 150, 243', '139, 195, 74', '255, 193, 7', '255, 87, 34', '224, 64, 251', '244, 67, 54');
if ($planeacion_info[p1][pl_nivel] == Preescolar)  {
	$grado = $planeacion_info[p1][pl_grado_pre];	
} else { 
	if ($planeacion_info[p1][pl_nivel] == Primaria) {
		$grado = $planeacion_info[p1][pl_grado_pri];
	} else {
		$grado = $planeacion_info[p1][pl_grado_sec];
	}
}

//----------------CREO FUNCIONES PARA EL DOCUMENTO----------------//
class PDF extends FPDF {
	// Margins
   var $left = 10;
   var $right = 10;
   var $top = 10;
   var $bottom = 10;
         
   // Create Table
   function WriteTable($tcolums)
   {
      // go through all colums
      for ($i = 0; $i < sizeof($tcolums); $i++)
      {
         $current_col = $tcolums[$i];
         $height = 0;
         
         // get max height of current col
         $nb=0;
         for($b = 0; $b < sizeof($current_col); $b++)
         {
            // set style
            $this->SetFont($current_col[$b]['font_name'], $current_col[$b]['font_style'], $current_col[$b]['font_size']);
            $color = explode(",", $current_col[$b]['fillcolor']);
            $this->SetFillColor($color[0], $color[1], $color[2]);
            $color = explode(",", $current_col[$b]['textcolor']);
            $this->SetTextColor($color[0], $color[1], $color[2]);            
            $color = explode(",", $current_col[$b]['drawcolor']);            
            $this->SetDrawColor($color[0], $color[1], $color[2]);
            $this->SetLineWidth($current_col[$b]['linewidth']);
                        
            $nb = max($nb, $this->NbLines($current_col[$b]['width'], $current_col[$b]['text']));            
            $height = $current_col[$b]['height'];
         }  
         $h=$height*$nb;
         
         
         // Issue a page break first if needed
         $this->CheckPageBreak($h);
         
         // Draw the cells of the row
         for($b = 0; $b < sizeof($current_col); $b++)
         {
            $w = $current_col[$b]['width'];
            $a = $current_col[$b]['align'];
            
            // Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            
            // set style
            $this->SetFont($current_col[$b]['font_name'], $current_col[$b]['font_style'], $current_col[$b]['font_size']);
            $color = explode(",", $current_col[$b]['fillcolor']);
            $this->SetFillColor($color[0], $color[1], $color[2]);
            $color = explode(",", $current_col[$b]['textcolor']);
            $this->SetTextColor($color[0], $color[1], $color[2]);            
            $color = explode(",", $current_col[$b]['drawcolor']);            
            $this->SetDrawColor($color[0], $color[1], $color[2]);
            $this->SetLineWidth($current_col[$b]['linewidth']);
            
            $color = explode(",", $current_col[$b]['fillcolor']);            
            $this->SetDrawColor($color[0], $color[1], $color[2]);
            
            
            // Draw Cell Background
            $this->Rect($x, $y, $w, $h, 'FD');
            
            $color = explode(",", $current_col[$b]['drawcolor']);            
            $this->SetDrawColor($color[0], $color[1], $color[2]);
            
            // Draw Cell Border
            if (substr_count($current_col[$b]['linearea'], "T") > 0)
            {
               $this->Line($x, $y, $x+$w, $y);
            }            
            
            if (substr_count($current_col[$b]['linearea'], "B") > 0)
            {
               $this->Line($x, $y+$h, $x+$w, $y+$h);
            }            
            
            if (substr_count($current_col[$b]['linearea'], "L") > 0)
            {
               $this->Line($x, $y, $x, $y+$h);
            }
                        
            if (substr_count($current_col[$b]['linearea'], "R") > 0)
            {
               $this->Line($x+$w, $y, $x+$w, $y+$h);
            }
            
            
            // Print the text
            $this->MultiCell($w, $current_col[$b]['height'], $current_col[$b]['text'], 0, $a, 0);
            
            // Put the position to the right of the cell
            $this->SetXY($x+$w, $y);         
         }
         
         // Go to the next line
         $this->Ln($h);          
      }                  
   }

   
   // If the height h would cause an overflow, add a new page immediately
   function CheckPageBreak($h)
   {
      if($this->GetY()+$h>$this->PageBreakTrigger)
         $this->AddPage($this->CurOrientation);
   }


   // Computes the number of lines a MultiCell of width w will take
   function NbLines($w, $txt)
   {
      $cw=&$this->CurrentFont['cw'];
      if($w==0)
         $w=$this->w-$this->rMargin-$this->x;
      $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
      $s=str_replace("\r", '', $txt);
      $nb=strlen($s);
      if($nb>0 and $s[$nb-1]=="\n")
         $nb--;
      $sep=-1;
      $i=0;
      $j=0;
      $l=0;
      $nl=1;
      while($i<$nb)
      {
         $c=$s[$i];
         if($c=="\n")
         {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
         }
         if($c==' ')
            $sep=$i;
         $l+=$cw[$c];
         if($l>$wmax)
         {
            if($sep==-1)
            {
               if($i==$j)
                  $i++;
            }
            else
               $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
         }
         else
            $i++;
      }
      return $nl;
   }


	// Cabecera de página
	function Header()	{
	    // Logo
	    $this->Image(get_plantilla_url() . '/images/logo.png',10,8,30);
		// Logo 2
	    $this->Image(get_plantilla_url() . '/images/logo-sev.png',305,8,40);
	    // Arial bold 15
	    $this->SetFont('Helvetica','B',16);
	    // Arial bold 15
		$this->SetTextColor(49,40,41);
		
		// Títulos
	    $this->Cell(80);
	    $this->Cell(180,7,utf8_decode('Educación Mobile Veracruz'),0,1,'C');
		//subtitulo
		$this->SetFont('Helvetica','',14);
		$this->SetTextColor(68,68,68);
		$this->SetDrawColor(197,197,197);
		$this->Ln(7);
		$this->Cell(0,7,utf8_decode('PLAN DE CLASE'),'B',1,'C');
		$this->Ln(3);
	}

	// Pie de página
	function Footer()	{
	    // Posición: a 1,5 cm del final
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo(),0,0,'C');
	}

	function TituloSeccion($num, $label)	{
	    // Fuente
	    $this->SetFont('Helvetica','',17);
	    // Color de fondo
	    $this->SetFillColor(255,255,255);
		// Color de texto
		$this->SetTextColor(186,28,35);
		// Color de linea
		$this->SetDrawColor(197,197,197);
	    // Título
	    $this->Ln(8);
	    $this->Cell(0,6,utf8_decode("Parte $num : $label"),'',1,'L',true);
	    // Salto de línea
	    $this->Ln(6);
	}

	function SubtituloSeccion($label)	{
	    // Fuente
	    $this->SetFont('Helvetica','',14);
	    // Color de fondo
	    $this->SetFillColor(0,127,62);
		// Color de texto
		$this->SetTextColor(252,252,252);
	    // Título
	    $this->Cell(0,8,$label,0,1,'C',true);
	}
	function Etiqueta($etiqueta) {
		// Arial 11
	    $this->SetFont('Helvetica','',12);
		$this->SetFillColor(224,224,224);
		$this->SetTextColor(34,34,34);
		$this->Cell(50,7,' ' . utf8_decode($etiqueta). ':',0,0,'L',1);
	}
	function EtiquetaLarga($etiqueta) {
		// Arial 12
	   $this->SetFont('Helvetica','',12);
		$this->SetFillColor(224,224,224);
		$this->SetTextColor(34,34,34);
		$this->Cell(0,7,' ' . utf8_decode($etiqueta). ':',0,1,'L',1);
	}
	function Valor($valor) {
		// Arial 12
	    $this->SetFont('Helvetica','',12);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(34,34,34);
		$this->SetDrawColor(239,239,239);
		$this->Cell(0,7,utf8_decode(' ' . $valor),'B',1,'L',1);
		$this->Ln(2);
	}
	function ValorTexto($valor) {
	    $this->SetFont('Helvetica','',12);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(34,34,34);
		$this->SetDrawColor(239,239,239);
		$this->Ln(2);
		$this->MultiCell(0,7,utf8_decode(' ' . $valor),'',1,'L',1);
		$this->Ln(2);
	}
	function ValorElementoLista($valor) {
	    $this->SetFont('Helvetica','',12);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(34,34,34);
		$this->SetDrawColor(239,239,239);
		$this->Ln(2);
		$this->Cell(0,7,utf8_decode('        » ' . $valor),'B',1,'L',1);
		$this->Ln(1);
	}
	function ValorTextoLista($valor) {
	    $this->SetFont('Helvetica','',12);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(34,34,34);
		$this->SetDrawColor(245,245,245);
		$this->Ln(2);
		$this->MultiCell(0,7,utf8_decode('        » ' . $valor),'B',1,'L',1);
		$this->Ln(1);
	}

}

//----------------CREO EL DOCUMENTO----------------//
$pdf = new PDF('L','mm','Legal');

$pdf->SetTitle(utf8_decode($title));
$pdf->SetAuthor(utf8_decode($nombre_participante));
$pdf->AddPage(L, Legal);

// PARTE 1
$pdf->TituloSeccion(1,'Descriptor curricular');


// Si el nivel es secundaria o primaria
if ($planeacion_info[p1][pl_nivel] != Preescolar) {
	// Encabezado 
	$col = array();
	// columna 1
	$col[] = array('text' => 'Nivel', 'width' => '84', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	// columna 2
	$col[] = array('text' => 'Asignatura', 'width' => '84', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	// columna 3
	$col[] = array('text' => 'Grado', 'width' => '84', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	// columna 4
	$col[] = array('text' => 'Bloque', 'width' => '84', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	$generalesParte1[] = $col;
	// Datos
	$col = array();
	// columna 1
	$col[] = array('text' => utf8_decode($planeacion_info[p1][pl_nivel]), 'width' => '84', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	// columna 2
	$col[] = array('text' => utf8_decode($planeacion_info[p1][pl_asignatura]), 'width' => '84', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	// columna 3
	$col[] = array('text' => utf8_decode($grado), 'width' => '84', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	// columna 4
	$col[] = array('text' => utf8_decode($planeacion_info[p1][pl_bloque]), 'width' => '84', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	$generalesParte1[] = $col;
	// Draw Table   
	$pdf->WriteTable($generalesParte1);
	$pdf->Ln(1);

	// Si la asignatura es matemáticas
	if ($planeacion_info[p1][pl_asignatura] == Matemáticas) {
		$pdf->EtiquetaLarga('Aprendizaje esperado');
		$pdf->ValorTexto($planeacion_info[p1][pl_aprendizaje_esp]);
		$pdf->EtiquetaLarga('Eje');
		$pdf->ValorTexto($planeacion_info[p1][pl_eje]);
		$pdf->EtiquetaLarga('Tema');
		$pdf->ValorTexto($planeacion_info[p1][pl_tema]);
		$pdf->EtiquetaLarga('Contenido');
		$pdf->ValorTexto($planeacion_info[p1][pl_contenido]);
	}
	// Si la asignatura es español
	if ($planeacion_info[p1][pl_asignatura] == Español) {

		// Encabezado 
		$col = array();
		// columna 1
		$col[] = array('text' => 'Componente', 'width' => '112', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
		// columna 2
		$col[] = array('text' => 'Ambito', 'width' => '112', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
		// columna 3
		$col[] = array('text' => 'Tipo de texto', 'width' => '112', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');		
		$datosEsp[] = $col;
		// Datos
		$col = array();
		// columna 1
		$col[] = array('text' => utf8_decode($planeacion_info[p1][pl_componente]), 'width' => '112', 'height' => '7', 'align' => 'L', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
		// columna 2
		$col[] = array('text' => utf8_decode($planeacion_info[p1][pl_ambito]), 'width' => '112', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
		// columna 3
		$col[] = array('text' => utf8_decode($planeacion_info[p1][pl_tipo_texto]), 'width' => '112', 'height' => '7', 'align' => 'L', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
		$datosEsp[] = $col;
		// Draw Table   
		$pdf->WriteTable($datosEsp);
		$pdf->Ln(2);

		$pdf->EtiquetaLarga('Práctica social del lenguaje');
		$pdf->ValorTexto($planeacion_info[p1][pl_psl]);
		$pdf->EtiquetaLarga('Aprendizaje esperado');
		$pdf->ValorTexto($planeacion_info[p1][pl_aprendizaje_esp]);
		$pdf->EtiquetaLarga('Tema de reflexión');
		$pdf->ValorTexto($planeacion_info[p1][pl_tema_ref]);
		$pdf->EtiquetaLarga('Producción para el desarrollo del proyecto');
		$pdf->ValorTexto($planeacion_info[p1][pl_ppdp]);
	}

} else {
	// Encabezado 
	$col = array();
	// columna 1
	$col[] = array('text' => 'Nivel', 'width' => '84', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	// columna 2
	$col[] = array('text' => 'Campo formativo', 'width' => '252', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	$datosPreescolar[] = $col;
	// Datos
	$col = array();
	// columna 1
	$col[] = array('text' => utf8_decode($planeacion_info[p1][pl_nivel]), 'width' => '84', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	// columna 2
	$col[] = array('text' => utf8_decode($planeacion_info[p1][pl_campo_f]), 'width' => '252', 'height' => '7', 'align' => 'L', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	$datosPreescolar[] = $col;
	// Draw Table   
	$pdf->WriteTable($datosPreescolar);
	$pdf->Ln(2);

	$pdf->EtiquetaLarga('Aprendizaje esperado');
	$pdf->ValorTexto($planeacion_info[p1][pl_aprendizaje_esp]);
	$pdf->EtiquetaLarga('Tipo de situación de aprendizaje');
	$pdf->ValorTexto($planeacion_info[p1][pl_tipo_sa]);
	$pdf->EtiquetaLarga('Título de la situación de aprendiaje');
	$pdf->ValorTexto($planeacion_info[p1][pl_titulo_sa]);
}

// PARTE 2
$pdf->AddPage(L, Legal);
$pdf->TituloSeccion(2,'Reto educativo');

$pdf->EtiquetaLarga('Tópico generativo (Reto o problema a resolver)');
$pdf->ValorTexto($planeacion_info[p2][pl_topico_g]);
$pdf->EtiquetaLarga('Conocimientos previos requeridos');
foreach ($planeacion_info[p2][pl_conocimientos_pr] as $elemento) {  
      $pdf->ValorTextoLista($elemento);  
} 
$pdf->EtiquetaLarga('Otras asignaturas con las que se relaciona');
foreach ($planeacion_info[p2][pl_otras_ar] as $elemento) {  
      $pdf->ValorTextoLista($elemento);  
} 
$pdf->EtiquetaLarga('Por qué es interesante para mis alumnos');
$pdf->ValorTexto($planeacion_info[p2][pl_porque_ia]);


// PARTE 3
$pdf->AddPage(L, Legal);
$pdf->TituloSeccion(3,'Desempeño de compresión');
$pdf->SubtituloSeccion('Propuestas del maestro para analizar el problema');


// Encabezado de la tabla
$col = array();
// columna 1
$col[] = array('text' => 'No.', 'width' => '20', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
// columna 2
$col[] = array('text' => 'Propuesta para analizar el reto', 'width' => '316', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
$columnas[] = $col;

// Propuestas
$np = 1; 
foreach ( $planeacion_info[p3] as $propuesta ) {
	if ( $propuesta != '') {

		$col = array();
		// columna 1
		$col[] = array('text' => $np, 'width' => '20', 'height' => '10', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '13', 'font_style' => 'B', 'fillcolor' => $colores[$np-1], 'textcolor' => '255,255,255', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
		// columna 2
		$col[] = array('text' => utf8_decode($planeacion_info[p3]['pl_propuesta_' . $np]), 'width' => '312', 'height' => '8', 'align' => 'L', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
		$columnas[] = $col;
		$np++;
		// Espacio entre filas
		$col = array();
		$col[] = array('text' => ' ', 'width' => '306', 'height' => '2', 'fillcolor' => '255,255,255');
		$columnas[] = $col;
	}
}
// Draw Table   
$pdf->WriteTable($columnas);

// PARTE 4
$pdf->AddPage(L, Legal);
$pdf->TituloSeccion(4,'Mapa de desempeños');
// Encabezado de la tabla
$encabezado = array(" ", "Lingüistica", "Lógico matemática", "Musical", "Corporal cinéstesica", "Espacial", "Interpersonal", "Intrapersonal","Naturalista", "Existencial");

$col = array();
for ($i = 0; $i <= 9; $i++) {
	$col[] = array('text' => utf8_decode($encabezado[$i]), 'width' => '33', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
}
$mapa[] = $col;

// contenido de la tabla
$encabezado2 = array("Legislativo", "Ejecutivo", "Judicial", "Monárquico", "Jerárquico", "Oligárquico", "Anárquico", "Global","Local", "Interno", "Externo", "Liberal", "Conservador");

// recorro el array de la fila
for ($f = 1; $f <= 13; $f++) {
	$col = array();
	$col[] = array('text' => utf8_decode($encabezado2[$f-1]), 'width' => '33', 'height' => '9', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
	// recorro el array de la columna
	for ($c = 1; $c <= 9; $c++) {
		// obtengo la celda correcta
		$celda = 'pl_' . $f . '_' . $c;
		$valorCelda = $planeacion_info[p4][$celda];
		// asigno el color de relleno dela celda
		if ($valorCelda) {
			$color_celda = $colores[($valorCelda - 1)];
		} else {
			$color_celda = '255,255,255';
		}
		$col[] = array('text' => $valorCelda, 'width' => '33', 'height' => '9', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => $color_celda, 'textcolor' => '255,255,255', 'drawcolor' => '224,224,224', 'linewidth' => '0', 'linearea' => 'LTBR');
	}
	$mapa[] = $col;
}

// Draw Table   
$pdf->WriteTable($mapa);

// PARTE 5
$pdf->AddPage(L, Legal);
$pdf->TituloSeccion(5,'Consideraciones para evaluar los productos delos alumnos');

// Encabezado de la tabla
$col = array();
// columna 1
$col[] = array('text' => 'Propuesta', 'width' => '30', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
// columna 2
$col[] = array('text' => 'Aspectos a considerar', 'width' => '306', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
$col_cons[] = $col;

// Propuestas
$np = 1; 
foreach ( $planeacion_info[p5] as $propuesta ) {
	if ( $propuesta != '') {

		$col = array();
		// columna 1
		$col[] = array('text' => $np, 'width' => '30', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => 'B', 'fillcolor' => $colores[$np-1], 'textcolor' => '255,255,255', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
		// columna 2
		$col[] = array('text' => utf8_decode($planeacion_info[p5]['pl_consideracion_' . $np]), 'width' => '306', 'height' => '7', 'align' => 'L', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0', 'linearea' => 'LTBR');
		$col_cons[] = $col;
		$np++;
		// Espacio entre filas
		$col = array();
		$col[] = array('text' => ' ', 'width' => '306', 'height' => '2', 'fillcolor' => '255,255,255');
		$col_cons[] = $col;
	}
}
// Draw Table   
$pdf->WriteTable($col_cons);

// PARTE 6
$pdf->AddPage(L, Legal);
$pdf->TituloSeccion(6,'Recursos TIC a utilizar');

// Encabezado de la tabla
$col = array();
// columna 1
$col[] = array('text' => 'Recurso TIC (iPad) a utilizar', 'width' => '92', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0.2', 'linearea' => 'LTBR');
// columna 2
$col[] = array('text' => 'Propuesta(s)', 'width' => '72', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0.2', 'linearea' => 'LTBR');
// columna 3
$col[] = array('text' => 'Uso', 'width' => '172', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0.2', 'linearea' => 'LTBR');
$col_tics[] = $col;

// Recursos TIC
$np = 1; 
foreach ( $planeacion_info[p6][pl_recursos_tic] as $tic ) {
	$col = array();
	// columna 2
	$col[] = array('text' =>  utf8_decode($tic['pl_recurso_tic']), 'width' => '92', 'height' => '7', 'align' => 'LTBR', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '242,242,242', 'linewidth' => '0.2', 'linearea' => 'LTBR');
	// columna 2
	$tic_ps= '';
	for ($i = 1; $i <= 6; $i++) {
	    if ($tic['pl_propuesta'][$i]) {	$tic_ps .= '  ' . $i . '  '; }
	}
	$col[] = array('text' =>  $tic_ps, 'width' => '72', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '242,242,242', 'linewidth' => '0.2', 'linearea' => 'LTBR');
	// columna 2
	$col[] = array('text' => utf8_decode($tic['pl_uso']), 'width' => '172', 'height' => '8', 'align' => 'L', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '242,242,242', 'linewidth' => '0.2', 'linearea' => 'LTBR');
	$col_tics[] = $col;
	$np++;
}
// Draw Table   
$pdf->WriteTable($col_tics);

// PARTE 7
$pdf->AddPage(L, Legal);
$pdf->TituloSeccion(7,'Secuencia de aplicación');


// Encabezado de la tabla
$col = array();
// columna 1
$col[] = array('text' => utf8_decode('Ordinal'), 'width' => '52', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0.2', 'linearea' => 'LTBR');
// columna 2
$col[] = array('text' => 'Actividad', 'width' => '222', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0.2', 'linearea' => 'LTBR');
// columna 3
$col[] = array('text' => 'Tiempo', 'width' => '52', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '224,224,224', 'textcolor' => '34,34,34', 'drawcolor' => '255,255,255', 'linewidth' => '0.2', 'linearea' => 'LTBR');
$col_actividad[] = $col;

// Recursos TIC
$np = 1; 
foreach ( $planeacion_info[p7][pl_secuencia_aplicacion] as $actividad ) {
	$col = array();
	// columna 2
	$col[] = array('text' =>  $np, 'width' => '52', 'height' => '7', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '242,242,242', 'linewidth' => '0.2', 'linearea' => 'LTBR');
	// columna 2
	$col[] = array('text' =>  utf8_decode($actividad['pl_actividad_secuencia']), 'width' => '222', 'height' => '7', 'align' => 'L', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '242,242,242', 'linewidth' => '0.2', 'linearea' => 'LTBR');
	// columna 2
	$col[] = array('text' => $actividad['pl_minutos_secuencia'] . ' min.', 'width' => '52', 'height' => '8', 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '12', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '34,34,34', 'drawcolor' => '242,242,242', 'linewidth' => '0.2', 'linearea' => 'LTBR');
	$col_actividad[] = $col;
	$np++;
}
// Draw Table   
$pdf->WriteTable($col_actividad);

$pdf->Output();
?>
<?php endwhile; // end of the loop. ?>