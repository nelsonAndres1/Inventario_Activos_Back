<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Conta19;

use App\Models\Conta124;
use App\Models\Conta123;
use App\Models\Conta65;
use App\Models\Gener02;
use App\Models\Conta148;

class Conta19Controller extends Controller
{
    protected $cedula;


    public static function convert_from_latin1_to_utf8_recursively($dat)
    {
        if (is_string($dat)) {
            return utf8_encode($dat);
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d)
                $ret[$i] = self::convert_from_latin1_to_utf8_recursively($d);

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d)
                $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

            return $dat;
        } else {
            return $dat;
        }
    }





    public function getCedTra(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, [
            'cedtra' => 'required'
        ]);
        if ($validate->fails()) {
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Noooo cedula no!',
                'errors' => $validate->errors()
            );

        } else {
            $this->cedula = $params->cedtra;

            $signup = array(
                'status' => 'success',
                'code' => 200,
                'message' => $this->cedula
            );
        }
        return response()->json($signup, 200);
    }


    public function getConta19(Request $request)
    {
        $jwtAuth = new \JwtAuth();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, [
            'cedtra' => 'required'
        ]);
        if ($validate->fails()) {
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No!',
                'errors' => $validate->errors()
            );
        } else {
            $signup = $jwtAuth->getConta19($params->cedtra);
            if (!empty($params->gettoken)) {
                $signup = $jwtAuth->getConta19($params->cedtra);
            }
        }
        return response()->json($signup, 200);
    }

    public function preguntarContinuarInventario(Request $request)
    {
        $jwtAuth = new \JwtAuth();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, [
            'cedtra' => 'required'
        ]);
        if ($validate->fails()) {
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No!',
                'errors' => $validate->errors()
            );
        } else {
            $signup = $jwtAuth->preguntarContinuarInventario($params->cedtra);
            if (!empty($params->gettoken)) {
                $signup = $jwtAuth->preguntarContinuarInventario($params->cedtra);
            }
        }
        return response()->json($signup, 200);
    }
    public function getConta19A(Request $request)
    {
        $jwtAuth = new \JwtAuth();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, [
            'codact' => 'required'
        ]);
        if ($validate->fails()) {
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No!',
                'errors' => $validate->errors()
            );
        } else {
            $signup = $jwtAuth->getConta19_($params->codact);
            if (!empty($params->gettoken)) {
                $signup = $jwtAuth->getConta19_($params->codact);
            }
        }
        return response()->json($signup, 200);
    }


    public function traer_nombre(Request $request)
    {

        $jwtAuth = new \JwtAuth();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, [
            'cedtra' => 'required'
        ]);
        if ($validate->fails()) {
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No!',
                'errors' => $validate->errors()
            );
        } else {
            $signup = $jwtAuth->nombre_nomin02($params->cedtra);
            if (!empty($params->gettoken)) {
                $signup = $jwtAuth->nombre_nomin02($params->cedtra);
            }
        }
        return response()->json($signup, 200);
    }




    public function searchConta19(Request $request)
    {
        $res = '';

        $query = Conta19::query();
        $data = $request->input('search');

        if ($data != '') {
            $query->whereRaw("codact LIKE '%" . $data . "%'")
                ->orWhereRaw("detalle LIKE '%" . $data . "%'");
            /* ->Where("cedtra", "!=",$separada[1]) */


            $res = utf8_decode($this->convert_from_latin1_to_utf8_recursively($query->get()));
            if($res){
                for ($i=0; $i < count($res); $i++) { 
                    $res[$i]['detalle'] = utf8_decode($res[$i]['detalle']);
                }
            }
        } else {
            $query = '';
            $res = $this->convert_from_latin1_to_utf8_recursively($query);
        }
        return $res;
    }
    public function saveConta123(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if (!empty($params) && !empty($params_array)) {
            $params_array = array_map('trim', $params_array);

            $validate = Validator::make($params_array, [
                'usuario' => 'required',
                'cedori' => 'required',
                'estado' => 'required'
            ]);
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No se puede guardar conta123',
                    'errors' => $validate->errors()
                );
            } else {

                $conta148 = Conta148::where("estado", "A")->get();
                $fecini = '';
                $fecfin = '';
                $numero = 0;
                $numero2 = 0;
                if (count($conta148) > 0) {
                    foreach ($conta148 as $c148) {
                        $fecini = $c148->fecini;
                        $fecfin = $c148->fecfin;
                    }
                    $conta123 = Conta123::where('cedori', $params_array['cedori'])->whereBetween('fecha', [$fecini, $fecfin])->get();

                    foreach ($conta123 as $c123) {
                        $numero2 = $c123->numero;
                    }
                    Conta123::where('cedori', $params_array['cedori'])->whereBetween('fecha', [$fecini, $fecfin])->delete();
                    Conta124::where('numero', $numero2)->whereBetween('fecha', [$fecini, $fecfin])->delete();

                    $conta123 = new Conta123;
                    $conta123->fecha = date("Y-m-d");
                    $conta123->usuario = $params_array['usuario'];
                    $conta123->cedori = $params_array['cedori'];
                    $conta123->estado = $params_array['estado'];
                    $conta123->save();

                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'si creado',
                    );
                } else {
                    $data = array(
                        'status' => 'error',
                        'code' => 404,
                        'message' => 'No existe periodo activo!'
                    );
                }

            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Datos enviados no correctos conta123'
            );
        }
        return response()->json($data, $data['code']);
    }
    public function saveConta124(Request $request)
    {
        $conta123 = Conta123::select('numero')->orderBy('numero', 'desc')->first();
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if (!empty($params) && !empty($params_array)) {
            $params_array = array_map('trim', $params_array);

            $validate = Validator::make($params_array, [
                'cnt' => 'required',
                'codact' => 'required',
                'subcod' => 'required',
                'estado' => 'required',
                'estinv' => 'required',
                'cedtra' => 'required'

            ]);
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No se puede guardar conta19',
                    'errors' => $validate->errors()
                );
            } else {
                $conta148 = Conta148::where("estado", "A")->get();
                $fecini = '';
                $fecfin = '';
                $numero = 0;
                $numero2 = 0;
                if (count($conta148) > 0) {


                    $conta124 = new Conta124;
                    $conta124->numero = $conta123->numero;
                    $conta124->cnt = $params_array['cnt'];
                    $conta124->fecha = date("Y-m-d");
                    $conta124->codact = $params_array['codact'];
                    $conta124->subcod = $params_array['subcod'];
                    $conta124->coddep = $params_array['coddep'];
                    $conta124->estado = $params_array['estado']; //estado proviene de la conta19
                    $conta124->estact = $params_array['estact']; //estado fisico
                    $conta124->estinv = $params_array['estinv']; //estado del inventario
                    $conta124->observacion = $params_array['observacion'];
                    $conta124->save();


                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'si creado',
                    );

                } else {
                    $data = array(
                        'status' => 'error',
                        'code' => 404,
                        'message' => 'No existe periodo activo!'
                    );

                }
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Datos enviados no correctos'
            );
        }
        return response()->json($data, $data['code']);

    }
    public function getDocumentoConta65(Request $request)
    {
        $jwtAuth = new \JwtAuth();
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, [

        ]);

        if ($validate->fails()) {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'getDocumentoConta65',
                'errors' => $validate->errors()
            );
        } else {
            $signup = $jwtAuth->getConta65();
            $consecutivo = $signup[0]['documento'];
            $num = intval($consecutivo) + 1;
            $length = 7;
            $string = substr(str_repeat(0, $length) . $num, $length);
        }
        return response()->json($string, 200);
    }
    public function SaveConta65(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if (!empty($params) && !empty($params_array)) {
            $params_array = array_map('trim', $params_array);

            $validate = Validator::make($params_array, [
                'cnt' => 'required',
                'documento' => 'required',
                'usuario' => 'required',
                'codact' => 'required',
                'subcod' => 'required',
                'detalle' => 'required',
                'areori' => 'required',
                'depori' => 'required',
                'ubiori' => 'required',
                'cedori' => 'required',
                'aredes' => 'required',
                'depdes' => 'required',
                'ubides' => 'required',
                'cedtra' => 'required',
                'estado' => 'required'
            ]);

            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'no se puede saveconta65',
                    'errors' => $validate->errors()
                );
            } else {
                /*                 $jwtAuth = new \JwtAuth();
                $signup = $jwtAuth->getConta65();
                $consecutivo = $signup[0]['documento'];
                $num = intval($consecutivo)+1; */
                $num = intval($params_array['documento']);
                $length = 7;
                $string = substr(str_repeat(0, $length) . $num, -$length);

                $conta65 = new Conta65();
                $conta65->documento = $string;
                $conta65->cnt = $params_array['cnt'];
                $conta65->usuario = $params_array['usuario'];
                $conta65->fecha = date("Y-m-d");
                $conta65->codact = $params_array['codact'];
                $conta65->subcod = $params_array['subcod'];
                $conta65->detalle = $params_array['detalle'];
                $conta65->areori = $params_array['areori'];
                $conta65->depori = $params_array['depori'];
                $conta65->ubiori = $params_array['ubiori'];
                $conta65->cedori = $params_array['cedori'];
                $conta65->aredes = $params_array['aredes'];
                $conta65->depdes = $params_array['depdes'];
                $conta65->ubides = $params_array['ubides'];
                $conta65->cedtra = $params_array['cedtra'];
                $conta65->estado = $params_array['estado'];
                $conta65->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'si creado saveconta65'
                );
            }
            return response()->json($data, $data['code']);
        }
    }

    public function ConsultaR(Request $request)
    {
        $jwtAuth = new \JwtAuth();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, [

        ]);
        if ($validate->fails()) {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Error en saveConta65',
                'errors' => $validate->errors()
            );
        } else {
            $data = $jwtAuth->consulta();
            if (!empty($params->gettoken)) {
                $data = $jwtAuth->consulta();
            }
        }
        return response()->json($data, 200);
    }


    public function ConsulConta19(Request $request)
    {
        $jwtAuth = new \JwtAuth();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);


        $validate = Validator::make($params_array, [
            'codact' => 'required',
            'cedtra' => 'required'
        ]);
        if ($validate->fails()) {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Error en saveConta65',
                'errors' => $validate->errors()
            );
        } else {
            $data = $jwtAuth->consultConta19($params->codact, $params->cedtra);
            if (!empty($params->gettoken)) {
                $data = $jwtAuth->consultConta19($params->codact, $params->cedtra);
            }
        }
        return response()->json($data, 200);
    }
    public function updateConta19(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);


        if (!empty($params) && !empty($params_array)) {
            $params_array = array_map('trim', $params_array);

            $validate = Validator::make($params_array, [
                'codact' => 'required',
                'codare' => 'required',
                'coddep' => 'required',
                'codubi' => 'required',
                'cedtra' => 'required'
            ]);

            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'no se puede updateConta19',
                    'errors' => $validate->errors()
                );
            } else {

                $codcen = str_split($params_array['coddep']);

                $codcenR = $codcen[0] . $codcen[1] . $codcen[2] . $codcen[3];


                $conta19 = Conta19::where('codact', '=', $params_array['codact'])
                    ->where('cnt', '01')->update(['codare' => $params_array['codare']]);

                $conta19 = Conta19::where('codact', '=', $params_array['codact'])
                    ->where('cnt', '01')->update(['coddep' => $params_array['coddep']]);


                $conta19 = Conta19::where('codact', '=', $params_array['codact'])
                    ->where('cnt', '01')->update(['cedtra' => $params_array['cedtra']]);

                $conta19 = Conta19::where('codact', '=', $params_array['codact'])
                    ->where('cnt', '01')->update(['codcen' => $codcenR]);

                $conta19 = Conta19::where('codact', '=', $params_array['codact'])
                    ->where('cnt', '01')->update(['codubi' => $params_array['codubi']]);

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'si creado',
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'no se puede updateConta19'
            );
        }
        return response()->json($data, $data['code']);
    }
}