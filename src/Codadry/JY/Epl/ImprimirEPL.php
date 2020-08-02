<?php
/*
 * El script ImprimirEpl.php es una inspiración del publicado por Simon Corless, usuario de Github.
 * Su perfil esta en https://github.com/SiCoUK el cual, consiste en código EPL sobre php para 
 * imprimir etiquetas en imoresoras termicas.
 * 
 * Las impresoras de etiquetas incorporan el lenguaje de control 
 * de impresora TSPL\EPL\ZPL\DPL, que es totalmente compatible con otros lenguajes de impresora.
 * 
 * 
 * Documentación de EPL (Eltron Programming Language): 
 * http://web.archive.org/web/20140819172234/https://support.zebra.com/cpws/docs/eltron/epl2/EPL2_Prog.pdf
 * 
 * Implementación para imprimir etiquetas en la impresora antes mencionadas.
 * El desarrollo parte del script mencionado y la implementación hecha por:
 * @Autor: José Adrian García López
 * @Contacto: joseadrian_g97@hotmail.com
 * @Canal: 
 * @GitHub: 
 *
 */
    require('AccionesPhp.php');
    require('ComandosEpl.php');

	class ImprimirEpl{		

    	/**
         * Método constructor sin parámetros
         */
        public function __construct() {
            //...
        }

        /**
         * Envia comandos EPL al dispositivo indicado
         * 
         * @param string ($etiqueta) comandos a imprimir
         * @param string ($impresora) el nombre de la impresora
         * @param boolean ($impresion) si activa la impresión en el dispositivo
         * @param boolean ($depuracion) si desea imprimir información de impresión
         */
    	public function imprimir($etiqueta, $impresora, $impresion = true, $informacion = false) {
        	                
            /*
             * Se crea el archivo temporal.
             * sys_get_temp_dir() Devuelve ruta del directorio donde PHP 
             * almacena los archivos temporales por defecto.
             * 'Eti' es un prefijo para el nombre del archivo temporal.
             */

            $archivoTemporal = tempnam(sys_get_temp_dir(), 'Eti');
            $temporalAuxiliar = $archivoTemporal;
            
            // Se abré el archivo temporal para escribir en el
            $archivoAbierto = fopen($archivoTemporal, "w");
            //Se escribe las etiquetas en el archivo temporal
            fwrite($archivoAbierto, $etiqueta);
            // Se cierra el archivo        
            fclose($archivoAbierto); 

            // Se pregunta si se desea imprimir en la impresora
            if ($impresion) {
            	// Se imprime la archivo temporal (etiqueta ya escrita) en la impresora indicada.
            	$print =  exec('print /d:"\\\%COMPUTERNAME%\\' 
                                . $impresora 
                                . '" "' 
                                . $archivoTemporal 
                                . '"');

            }        
            
            // Se elimina el archivo temporal
            $archivoEliminado =  unlink($archivoTemporal);

            // // Se pregunta si se desea imprimir la información
    		if ($informacion) {
                $informe = "<b>**************************************************************************</b>";
                $informe .= 
                "*<h1>Información de impresión</h1>"
                ."<b>**************************************************************************</b><br>"
                ."<b>Impresora: </b>". $impresora ."<br>"
                ."<b>Sistema operativo: </b>". php_uname() ."<br>"
                ."<b>Host: </b>". gethostname() ."<br>"            
                ."<b>Fecha: </b>". date("d") . "-" . date("m") . "-" . date("Y") ."<br>"  
                ."<b>Hora: </b>". date("H") .":". date("i") ."<br>"  
                ."<b>Comandos EPL: </b>". $etiqueta ."<br>"
                ."<b>Archivo temporal creado: </b>". $temporalAuxiliar ."<br>";

                //Se verifica si se elimino el archivo temporal
                if ($archivoEliminado) {
                    $informe .= "<b>Archivo elimidado: </b>se ha eliminado el archivo temporal.<br>"; 
                }else{
                    $informe .= "<b>Archivo elimidado: </b>no se ha podido eliminar el archivo temporal.<br>"; 
                }							

                //Se verifica si se activo la impresión al dispositivo
    			if ($impresion) {
                    $informe .= "<b>Impresión: </b>activada.<br>";
    				$informe .= "<h4>Impresión: ".$print."</h4>";
    			}else{
                    $informe .= "<b>Impresión: </b>desactivada.<br>";
    				$informe .= "<b>Nota: </b>La impresión esta desactivada, para habilitarla, cambiar a <i>true</i> en la función: -> <i>imprimir()</i>.<br>";
    			}
                $informe .= "<b>Nota: </b>Para desactivar la información, cambiar a <i>false</i> en la llamada de la función: -> <i>imprimir().</i><br>*<br>"
                ."<b>**************************************************************************</b>";

                // Se imprime la información
                echo($informe);
    						
    		}
        }
        
        /**
         * Se agrega un encabezado y un pie a la etiqueta.
         * 
         * @param string ($etiqueta) el contenido de la etiqueta
         * @param int ($cantidad) la cantidad de impresión
         * @return string devuelve la etiqueta lista para imprimir
         */
        public function construirEtiqueta($etiqueta, $cantidad = 1) {
            // Se crea la nueva etiqueta
            $nuevaEtiqueta = ComandosEpl::$INICIAR_ETIQUETA . AccionesPhp::finLinea(); 
            // Se limpia el buffer de imagen antes de la elaboración de una nueva etiqueta. 
            $nuevaEtiqueta .= ComandosEpl::$LIMPIAR_BUFFER . AccionesPhp::finLinea();
                    
            // Se agrega el contenido de la etiqueta a la nueva etiqueta
            $nuevaEtiqueta .= $etiqueta;

            // Se agrega el número de impresiones de una etiqueta.
            $nuevaEtiqueta .= ComandosEpl::$NUMERO_IMPRESION
                              .ComandosEpl::$COMA
                              .(int) $cantidad 
                              . AccionesPhp::finLinea();
            
            // Se devuelve la nueva etiqueta lista para imprimir
            return $nuevaEtiqueta;
        }
        
        /**
         * Escribe texto de caracteres ascii
         * 
         * @param string ($texto) The string of text
         * @param int ($ejeX) Posición en el eje x (horizontal)
         * @param int ($ejeY) Posición en el eje y (vertical)
         * @param int ($fuente) Tamaño de la fuente [1-5]
         * @param boolean ($constraste) invierte el contraste del texto. 
         * @param int ($rotacion) Rotación del texto:
         *                      0 = Normal 
         *                      1 = 90 grados
         *                      2 = 180 grados
         *                      3 = 270 grados
         * @param int ($expandirX) multiplicador horizontal, expande el texto horizontalmente 
         *                                                          (acepta valores de 1-6, 8). 
         * @param int ($expandirY) multiplicador vertical, expande el texto verticalmente 
         *                                                          (acepta valores del 1 - 9). 
         * @return (string) devuelve comando completo
         */
        public function escribirTexto($texto
                                    , $ejeX
                                    , $ejeY
                                    , $fuente
                                    , $constraste = false
                                    , $rotacion = 0
                                    , $expandirX = 1
                                    , $expandirY = 1){
                    

            // Se verifica la configuración del constraste
            // constraste = N (Normal, negro letras) o R (constraste activado)
            $style = ($constraste) ? ComandosEpl::$TEXTO_R : ComandosEpl::$TEXTO_N;

            //Comando A: imprime una cadena de texto ASCII. 
            return AccionesPhp::escribirComando(ComandosEpl::$CADENA_A, 
                                        array(
                                            (int) $ejeX,
                                            (int) $ejeY,
                                            (int) $rotacion,
                                            $fuente,
                                            (int) $expandirX,
                                            (int) $expandirY,
                                            $style,
                                            ComandosEpl::$COMILLA 
                                                        . $texto 
                                                        . ComandosEpl::$COMILLA
                                        )
                    );

        }

        /**
         * Pintamos una linea especifica.
         * 
         * @see $this->line()
         * @param int ($ejeX) The horizontal start position
         * @param int ($ejeY) The vertical start position
         * @param int ($tamanho) Tamaño de la linea en puntos
         * @param int ($grosor) El grosor de la línea en puntos
         * @param int ($orientacion) La orientación de la line 
         *                          0: [vertical]
         *                          1: [horizontal] 
         * @param int ($colorLinea) Color de línea
         *                          0: Negro
         *                          1: Color invertido
         *                          2: Blanco
         *
         * @return string
         */
        public function pintarLinea($ejeX
                                  , $ejeY
                                  , $tamanho
                                  , $grosor = 10
                                  , $orientacion = 1
                                  , $colorLinea = 0) {

            // Se verifica la orientación especificada
            if ($orientacion == ComandosEpl::$HORIZONTAL) {
                $xTamanho = $tamanho;
                $yTamanho = $grosor;
            } else {
                $yTamanho = $tamanho;
                $xTamanho = $grosor;
            }

            // Se determina el comando de linea y el color que debe de imprimir      
            $comando = ComandosEpl::$LINEA_NEGRA_LO;
            switch ($colorLinea) {
                case 1:
                    $comando = ComandosEpl::$LINEA_INVERTIA_LE;
                    break;
                case 2:
                    $comando = ComandosEpl::$LINEA_BLANCA_LW;
                    break;                
            }
            
            // Se crea la linea
            return AccionesPhp::escribirComando($comando, 
                                        array(
                                            (int) $ejeX,
                                            (int) $ejeY,
                                            (int) $xTamanho,
                                            (int) $yTamanho
                                        )
                    );

        }

       /**
         * Se escribe un código de barra
         * 
         * @param string ($dato) Datos del código de barra
         * @param int ($ejeX) Posición en el eje x
         * @param int $ejeY Posición en el eje y
         * @param int ($altura) altura de puntos de código de barras
         * @param string ($tipo) Selección de código de barras
         * @param boolean $legibilidad código legible para humanos true: si; false: no
         * @param int ($rotacion) rotación del código de barra
         *                      0 = Normal 
         *                      1 = 90 grados
         *                      2 = 180 grados
         *                      3 = 270 grados
         * @param int ($estrecho) estrecho de la barra
         * @param int ($anchuraBarras) anchura
         * @return string
         */
        public function pintarCodigoBarra($dato
                                  , $ejeX
                                  , $ejeY
                                  , $altura
                                  , $tipo = 3
                                  , $legibilidad = true
                                  , $rotacion = 0
                                  , $estrecho = 2
                                  , $anchuraBarras = 3) {

            $comando = ComandosEpl::$BARRAS_B;

            // Se verifica si es legible para humanos            
            $legibilidadH = ($legibilidad)? ComandosEpl::$BARRA_LEGIBLE_B : ComandosEpl::$BARRA_ILEGIBLE_N;           
            return AccionesPhp::escribirComando($comando
                                              , array(
                                                    (int) $ejeX,
                                                    (int) $ejeY,
                                                    (int) $rotacion,
                                                    $tipo,
                                                    (int) $estrecho,
                                                    (int) $anchuraBarras,
                                                    (int) $altura,
                                                    $legibilidadH,
                                                    ComandosEpl::$COMILLA . $dato . ComandosEpl::$COMILLA
                                                )
                    );
        }


    }


?>