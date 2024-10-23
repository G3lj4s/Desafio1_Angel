<?php
class TerritorioFactory {
    public static function crearTerritorio($propietario) {
        $nombre = self::generarNombreAleatorio();
        return new Territorio(null,null, $nombre,$propietario, 0);
    }

    private static function generarNombreAleatorio() {
        $nombresPosibles = [
            'Alaska', 'Kamchatka', 'Brasil', 'Egipto', 'Siberia', 'Mongolia', 'India', 'Argentina', 
            'Islandia', 'Japón', 'Australia', 'Groenlandia', 'Perú', 'Chile', 'México', 'Canadá', 
            'China', 'Sudáfrica', 'Nigeria', 'Suecia', 'Noruega', 'España', 'Francia', 'Italia', 
            'Alemania', 'Reino Unido', 'Turquía', 'Irán', 'Rusia', 'Corea', 'Filipinas', 'Vietnam', 
            'Indonesia', 'Colombia', 'Venezuela', 'Arabia Saudita', 'Siria', 'Grecia', 'Polonia', 'Finlandia'
        ];
        return $nombresPosibles[array_rand($nombresPosibles)];
    }
    public static function generarTerritorios($numCasillas){
        $territorios = [];
        for ($i=0; $i < $numCasillas; $i++) {
            ($i % 2 == 0) ? $propietario="M" :  $propietario="U";
            $territorios[] = self::crearTerritorio($propietario);
        }
        shuffle($territorios);
        return $territorios;
    }
}
