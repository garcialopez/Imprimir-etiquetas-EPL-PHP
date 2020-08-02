<?php
	class AccionesPhp{

		/**
         * El símbolo 'Fin De Línea' correcto de la plataforma en uso.
         * 
         * @return string
         */
        public static function finLinea() {
            return PHP_EOL;
        }

        /**
         * Crea una linea de comados EPL
         *
         * Concatena el array separados por comas en una sola linea 
         *
         * @param string ($comando) El comando de linea
         * @param array ($parametros) Los parametros del comando EPL
         * @return (string) Devuelve una linea de código EPL
         */
        public static function escribirComando($comando, $parametros) {
            return $comando . implode(',', $parametros) . AccionesPhp::finLinea();
        }


	}
?>