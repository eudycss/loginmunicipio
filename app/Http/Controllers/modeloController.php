<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\adm_modulos;
use App\Models\adm_usuario;
use Illuminate\Console\View\Components\Task;
use Illuminate\Support\Facades\DB;

class modeloController extends Controller
{

    public function index()
    {
        $modulos = adm_modulos::select('mo_id', 'mo_padre', 'mo_nombre', 'mo_descripcion', 'mo_estado')
            ->where('mo_estado', '=', 'A')
            ->orderBy('mo_nombre', 'ASC')
            ->get();

        return response()->json(['modulos' => $modulos, 'status' => 200]);
    }

    /*  public function update($id)
    {
        $modulo = adm_modulos::find($id);
        $modulo->mod_visitas = $modulo->mod_visitas + 1;
        $modulo->save();

        return response()->json(['mensaje' => 'Visitas actualizadas', 'modulo' => $id, 'status' => 200]);
    } */

    public function clasif(Request $request)
    {
        $modulos = DB::select(' SELECT u.us_login , ru.url_login, r.rol_codigo, urm.urm_codigo_rol, mo.mo_id, mo.mo_nombre
        from adm_usuario u
        JOIN adm_rol_usuario ru ON ru.url_login = u.us_login
        JOIN adm_rol r ON r.rol_codigo  = ru.url_rol
        JOIN ccc_usuario_rol_modulo urm ON urm.urm_id_modulo =r.rol_codigo
        JOIN ccc_modulo mo ON mo.mo_id= urm.urm_id_modulo
        ');
        foreach ($modulos as $modulo) {
          /*   if ( $modulo->mo_id == 2) {
                return response()->json(['message' => 'Cerramiento']);
            }
            if ( $modulo->mo_id == 1) {
                return response()->json(['message' => 'CONSTRUCION']);
            }
            if ( $modulo->mo_id == 3) {
                return response()->json(['message' => 'PUBLICIDAD']);
            } */


        }

       return response()->json(['modulos' => $modulos, 'status' => 200]);
    }
}
