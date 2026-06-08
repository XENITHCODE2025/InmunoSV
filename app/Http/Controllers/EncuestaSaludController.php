<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EncuestaSalud;
use App\Models\EncuestaVacuna;
use App\Models\EncuestaCondicion;

class EncuestaSaludController extends Controller
{

    public function index()
    {
        $departamento = auth()->user()->departamento;

        $municipios = $this->obtenerMunicipios($departamento);

        return view('Bienvenida', compact('municipios'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'municipio' => 'required|string|max:255',
        ]);

        $encuesta = EncuestaSalud::create([
            'user_id' => auth()->id(),
            'municipio' => $request->municipio,
            'otra_condicion' => $request->otra_condicion
        ]);

        // Guardar vacunas
        if ($request->has('vacunas')) {

            foreach ($request->vacunas as $vacunaId) {

                EncuestaVacuna::create([
                    'encuesta_id' => $encuesta->id_encuesta,
                    'vacuna_id' => $vacunaId
                ]);
            }
        }

        // Guardar condiciones
        if ($request->has('condiciones')) {

            foreach ($request->condiciones as $condicionId) {

                EncuestaCondicion::create([
                    'encuesta_id' => $encuesta->id_encuesta,
                    'condicion_id' => $condicionId
                ]);
            }
        }

        return redirect('/notificaciones')
            ->with('success', 'Encuesta guardada correctamente');
    }

    private function obtenerMunicipios($departamento)
    {
        $municipios = [

            'ahuachapan' => [
                'Ahuachapán',
                'Apaneca',
                'Atiquizaya',
                'Concepción de Ataco',
                'El Refugio',
                'Guaymango',
                'Jujutla',
                'San Francisco Menéndez',
                'San Lorenzo',
                'San Pedro Puxtla',
                'Tacuba',
                'Turín'
            ],

            'cabanas' => [
                'Cinquera',
                'Dolores',
                'Guacotecti',
                'Ilobasco',
                'Jutiapa',
                'San Isidro',
                'Sensuntepeque',
                'Tejutepeque',
                'Victoria'
            ],

            'chalatenango' => [
                'Agua Caliente',
                'Arcatao',
                'Azacualpa',
                'Chalatenango',
                'Citalá',
                'Comalapa',
                'Concepción Quezaltepeque',
                'Dulce Nombre de María',
                'El Carrizal',
                'El Paraíso',
                'La Laguna',
                'La Palma',
                'La Reina',
                'Las Vueltas',
                'Nueva Concepción',
                'Nueva Trinidad',
                'Nombre de Jesús',
                'Ojos de Agua',
                'Potonico',
                'San Antonio de la Cruz',
                'San Antonio Los Ranchos',
                'San Fernando',
                'San Francisco Lempa',
                'San Francisco Morazán',
                'San Ignacio',
                'San Isidro Labrador',
                'San José Cancasque',
                'San José Las Flores',
                'San Luis del Carmen',
                'San Miguel de Mercedes',
                'San Rafael',
                'Santa Rita',
                'Tejutla'
            ],

            'cuscatlan' => [
                'Candelaria',
                'Cojutepeque',
                'El Carmen',
                'El Rosario',
                'Monte San Juan',
                'Oratorio de Concepción',
                'San Bartolomé Perulapía',
                'San Cristóbal',
                'San José Guayabal',
                'San Pedro Perulapán',
                'San Rafael Cedros',
                'San Ramón',
                'Santa Cruz Analquito',
                'Santa Cruz Michapa',
                'Suchitoto',
                'Tenancingo'
            ],

            'la_libertad' => [
                'Antiguo Cuscatlán',
                'Chiltiupán',
                'Ciudad Arce',
                'Colón',
                'Comasagua',
                'Huizúcar',
                'Jayaque',
                'Jicalapa',
                'La Libertad',
                'Nuevo Cuscatlán',
                'Opico',
                'Quezaltepeque',
                'Sacacoyo',
                'San Juan Opico',
                'San Matías',
                'San Pablo Tacachico',
                'Santa Tecla',
                'Talnique',
                'Tamanique',
                'Teotepeque',
                'Tepecoyo',
                'Zaragoza'
            ],

            'la_paz' => [
                'Cuyultitán',
                'El Rosario',
                'Jerusalén',
                'Mercedes La Ceiba',
                'Olocuilta',
                'Paraíso de Osorio',
                'San Antonio Masahuat',
                'San Emigdio',
                'San Francisco Chinameca',
                'San Juan Nonualco',
                'San Juan Talpa',
                'San Juan Tepezontes',
                'San Luis La Herradura',
                'San Luis Talpa',
                'San Miguel Tepezontes',
                'San Pedro Masahuat',
                'San Pedro Nonualco',
                'San Rafael Obrajuelo',
                'Santa María Ostuma',
                'Santiago Nonualco',
                'Tapalhuaca',
                'Zacatecoluca'
            ],

            'la_union' => [
                'Anamorós',
                'Bolívar',
                'Concepción de Oriente',
                'Conchagua',
                'El Carmen',
                'El Sauce',
                'Intipucá',
                'La Unión',
                'Lislique',
                'Meanguera del Golfo',
                'Nueva Esparta',
                'Pasaquina',
                'Polorós',
                'San Alejo',
                'San José',
                'Santa Rosa de Lima',
                'Yayantique',
                'Yucuaiquín'
            ],

            'morazan' => [
                'Arambala',
                'Cacaopera',
                'Chilanga',
                'Corinto',
                'Delicias de Concepción',
                'El Divisadero',
                'El Rosario',
                'Gualococti',
                'Guatajiagua',
                'Joateca',
                'Jocoaitique',
                'Jocoro',
                'Lolotiquillo',
                'Meanguera',
                'Osicala',
                'Perquín',
                'San Carlos',
                'San Fernando',
                'San Francisco Gotera',
                'San Isidro',
                'San Simón',
                'Sensembra',
                'Sociedad',
                'Torola',
                'Yamabal',
                'Yoloaiquín'
            ],

            'san_miguel' => [
                'Carolina',
                'Chapeltique',
                'Chinameca',
                'Chirilagua',
                'Ciudad Barrios',
                'Comacarán',
                'El Tránsito',
                'Lolotique',
                'Moncagua',
                'Nueva Guadalupe',
                'Nuevo Edén de San Juan',
                'Quelepa',
                'San Antonio del Mosco',
                'San Gerardo',
                'San Jorge',
                'San Luis de la Reina',
                'San Miguel',
                'San Rafael Oriente',
                'Sesori',
                'Uluazapa'
            ],

            'san_salvador' => [
                'Aguilares',
                'Apopa',
                'Ayutuxtepeque',
                'Cuscatancingo',
                'Delgado',
                'El Paisnal',
                'Guazapa',
                'Ilopango',
                'Mejicanos',
                'Nejapa',
                'Panchimalco',
                'Rosario de Mora',
                'San Marcos',
                'San Martín',
                'San Salvador',
                'Santiago Texacuangos',
                'Santo Tomás',
                'Soyapango',
                'Tonacatepeque'
            ],

            'san_vicente' => [
                'Apastepeque',
                'Guadalupe',
                'San Cayetano Istepeque',
                'San Esteban Catarina',
                'San Ildefonso',
                'San Lorenzo',
                'San Sebastián',
                'San Vicente',
                'Santa Clara',
                'Santo Domingo',
                'Tecoluca',
                'Tepetitán',
                'Verapaz'
            ],

            'santa_ana' => [
                'Candelaria de la Frontera',
                'Chalchuapa',
                'Coatepeque',
                'El Congo',
                'El Porvenir',
                'Masahuat',
                'Metapán',
                'San Antonio Pajonal',
                'San Sebastián Salitrillo',
                'Santa Ana',
                'Santa Rosa Guachipilín',
                'Santiago de la Frontera',
                'Texistepeque'
            ],

            'sonsonate' => [
                'Acajutla',
                'Armenia',
                'Caluco',
                'Cuisnahuat',
                'Izalco',
                'Juayúa',
                'Nahuizalco',
                'Nahulingo',
                'Salcoatitán',
                'San Antonio del Monte',
                'San Julián',
                'Santa Catarina Masahuat',
                'Santa Isabel Ishuatán',
                'Santo Domingo de Guzmán',
                'Sonsonate',
                'Sonzacate'
            ],

            'usulutan' => [
                'Usulután',
                'Alegría',
                'Berlín',
                'California',
                'Concepción Batres',
                'El Triunfo',
                'Ereguayquín',
                'Estanzuelas',
                'Jiquilisco',
                'Jucuapa',
                'Jucuarán',
                'Mercedes Umaña',
                'Nueva Granada',
                'Ozatlán',
                'Puerto El Triunfo',
                'San Agustín',
                'San Buenaventura',
                'San Dionisio',
                'San Francisco Javier',
                'Santa Elena',
                'Santa María',
                'Santiago de María',
                'Tecapán'
            ]

        ];

        return $municipios[strtolower($departamento)] ?? [];
    }
}
