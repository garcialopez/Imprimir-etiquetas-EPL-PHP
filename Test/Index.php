<?php

/*
 * El script ImprimirEpl.php es una inspiración del publicado por Simon Corless, usuario de Github.
 * Su perfil esta en https://github.com/SiCoUK el cual, consiste en código EPL sobre php para 
 * imprimir etiquetas en imoresoras termicas.
 *
 * Copyright y licencia
 *
 * La impresora PHP EPL fue escrita por Simon Corless (actualmente sin licencia), 
 * está sin terminar y no se puede asumir ninguna responsabilidad por el uso de este código.
 * 
 * Las impresoras de etiquetas incorporan el lenguaje de control 
 * de impresora TSPL\EPL\ZPL\DPL, que es totalmente compatible con otros lenguajes de impresora.
 * 
 * La prueba se realizo con la impresora Xprinter XP-450B ofrece una interfaz USB 2.0 estándar.
 * La impresora debe de estar compartida con sus drivers originales o genéricos. 
 * 
 * Documentación de EPL (Eltron Programming Language): 
 * http://web.archive.org/web/20140819172234/https://support.zebra.com/cpws/docs/eltron/epl2/EPL2_Prog.pdf
 * 
 * Implementación para imprimir etiquetas en la impresora antes mencionadas.
 * El desarrollo parte del script mencionado y la implementación hecha por:
 * @Autor: José Adrian García López
 * @Contacto: joseadrian_g97@hotmail.com
 * @Canal: https://www.youtube.com/channel/UCFW7xIcnFBqVUMQClfyuBXA
 * @GitHub: https://github.com/garcialopez
 *
 */

	/*
	 * Nota: Puedes utilizar los métodos de la clase ImprimirEpl 
	 * pero debes de revisar la documentación del Lenguaje EPL
	 *
	 * 
	 */

	
	require_once('../src/Codadry/JY/Epl/ImprimirEpl.php');
	require_once('../src/Codadry/JY/Epl/ExisteImpresoraWindows.php');
		

	//Instanciamos de la clase ExisteImpresoraWindows.php para comprobar si existe la impresora
	$exiteImpresora = new ExisteImpresoraWindows();	
	//Instanciamos de la clase ImprimirEpl.php para imprimir
	$epl = new ImprimirEpl();	
    //Escribimos el nombre de la impresora tal cual este compartida.
	$impresora = 'Xprinter XP-450B';
	//$impresora = 'PDFCreator';
	

	if ($exiteImpresora->verificarImpresora($impresora,true)) {		
		
		//Escribimos el contenido de la etiqueta a imprimir
		$fecha = date("d") . "-" . date("m") . "-" . date("Y");
		$hora = date("H") .":". date("i");

		
		$texto1 = "TABASCO ES MAS";
		$texto2 = "410";
		$texto3 = "43 - 10";
		$texto4 = "#800";
		$texto5 = "BS2002142400";
		$texto6 = $fecha." Cunduacan Tabasco ". $hora;


	    $etiqueta = $epl->escribirTexto($texto1, 170, 10, 1, false, 0, 3, 3);
	    $etiqueta .= $epl->pintarLinea(5, 75, 765, 10, 1, 0);    
	    $etiqueta .= $epl->escribirTexto($texto2, 350, 95, 5, false, 0, 2, 2);
	    $etiqueta .= $epl->escribirTexto($texto3, 200, 210, 4, false, 0, 3, 3);
	    $etiqueta .= $epl->escribirTexto($texto4, 310, 310, 4, false, 0, 3, 3);
	    $etiqueta .= $epl->escribirTexto($texto5, 240, 405, 2, false, 0, 2, 2);
	    $etiqueta .= $epl->escribirTexto($texto6, 140, 450, 3, false, 0, 1, 1);    
	    
	    //Ejemplo de código de barras
	    //$etiqueta = $epl->pintarCodigoBarra("47591476", 190, 210, 120, 1, true, 0, 2, 6);   

	    	              
	    $epl->imprimir($epl->construirEtiqueta($etiqueta, 1), $impresora, false, true);
	}else{
		echo "<h1>No existe Impresora</h1>";
	}


?>