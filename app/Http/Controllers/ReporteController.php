<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Conta19;
use App\Models\Conta123;
use App\Models\Gener02;
use App\Models\nomin02;
use App\Models\nomin02Emp;

use Firebase\JWT\JWT;

class ReporteController extends Controller
{
    public function reporte_general(Request $request){
        $jwtAuth = new \JwtAuth();
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);


            $params_array = array_map('trim', $params_array);

            $validate = Validator::make($params_array, [
                'fecini'=>'required',
                'fecfin'=>'required'
            ]);
            if($validate->fails()){
                $signup = array(
                    'status' => 'error',
                    'code'   => 404,
                    'message' => 'No creado',
                    'errors' => $validate->errors()
                );
            }else{
                $signup = $jwtAuth->reporte_general($params->fecini, $params->fecfin);
                /* if(!empty($params->getToken)){
                    $signup = $jwtAuth->reporte_general($params->fecini, $params->fecfin);
                } */
            }
        return response()->json($signup, 200);
    }

    public function reporte(Request $request){
        $jwtAuth = new \JwtAuth();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params) && !empty($params_array)){
            $params_array = array_map('trim', $params_array);

            $validate = Validator::make($params_array, [
                'cedori'=>'required'
            ]);
            if($validate->fails()){
                $signup = array(
                    'status' => 'error',
                    'code'   => 404,
                    'message' => 'No creado',
                    'errors' => $validate->errors()
                );
            }else{
                $signup = $jwtAuth->reporte($params->cedori);
                if(!empty($params->getToken)){
                    $signup = $jwtAuth->reporte($params->cedori);
                }
            }
            return response()->json($signup, 200);
        }
    }



    public function reporte2(Request $request){
        $jwtAuth = new \JwtAuth();
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, [
            'cedori'=>'required'
        ]);
        if($validate->fails()){
            $signup = array(
                'status' => 'error',
                'code'   => 404,
                'message' => 'No!',
                'errors' => $validate->errors()
            );
        }else{
            $signup = $jwtAuth->reporteHistorico($params->cedori);
            if(!empty($params->getToken)){
                $signup = $jwtAuth->reporteHistorico($params->cedori);
            }
        }
        return response()->json($signup, 200);
    }

    public static function convert_from_latin1_to_utf8_recursively($dat)
    {
       if (is_string($dat)) {
          return utf8_encode($dat);
       } elseif (is_array($dat)) {
          $ret = [];
          foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);
 
          return $ret;
       } elseif (is_object($dat)) {
          foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);
 
          return $dat;
       } else {
          return $dat;
       }
    }


    function eliminar_acentos($cadena){
		
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}


    public function searchGener02_sub(Request $request){
        $jwtAuth = new \JwtAuth();
        $json = $request->input('search', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);


        $validate = Validator::make($params_array, [
            'cedtra'=>'required'
        ]);
        if($validate->fails()){
            $signup = array(
                'status' => 'error',
                'code'   => 404,
                'message' => 'No!',
                'errors' => $validate->errors()
            );
        }else{
            $signup = $jwtAuth->traerCedula($params->cedtra);
            if(!empty($params->getToken)){
                $signup = $jwtAuth->traerCedula($params->cedtra);
            }
        }
        return response()->json($signup, 200);

    }


    public function searchGener02(Request $request){
        $res = '';
        $query = nomin02Emp::query();
        $data = $request->input('search');


        if($data != ''){
            $query->WhereRaw("docemp LIKE '%".$data."%'");
            
            $res = $query->get();
        }else{
            $query='';
            $res = $this->convert_from_latin1_to_utf8_recursively($query);
        }

        return $this->convert_from_latin1_to_utf8_recursively($res);
    }

    public function changeChar($cadena){
        $flag = false;
        for($i=0;$i<strlen($cadena);$i++){
          $char = $cadena[$i];
          switch($char){
            case '?': $cadena = utf8_decode(str_replace($char,'Ñ',$cadena));break; }
        }
        return htmlentities($cadena);
      }


    public function reporteH2(Request $request){
        $jwtAuth = new \JwtAuth();
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);


        $validate = Validator::make($params_array, [
            'numero'=>'required'
        ]);
        if($validate->fails()){
            $signup = array(
                'status' => 'error',
                'code'   => 404,
                'message' => 'No!',
                'errors' => $validate->errors()
            );
        }else{
            $signup = $jwtAuth->reporteH2($params->numero);
            if(!empty($params->getToken)){
                $signup = $jwtAuth->reporteH2($params->numero);
            }
        }
        return response()->json($signup, 200);
    }
}
