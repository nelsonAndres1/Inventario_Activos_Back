<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Conta148;

class Conta148Controller extends Controller
{


    public function getConta148_C(Request $request){

        $jwtAuth = new \JwtAuth();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, [
           
        ]);

        if($validate->fails()){
            $signup = array(
                'status' => 'error',
                'code'   => 404,
                'message' => 'No!',
                'errors' => $validate->errors()
            );
        }else{
            $signup = $jwtAuth->getConta148_C();
            if(!empty($params->gettoken)){
                $signup = $jwtAuth->getConta148_C();
            }
        }
        return response()->json($signup, 200);
    }

    public function getConta148(Request $request){

        $jwtAuth = new \JwtAuth();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, [
           
        ]);

        if($validate->fails()){
            $signup = array(
                'status' => 'error',
                'code'   => 404,
                'message' => 'No!',
                'errors' => $validate->errors()
            );
        }else{
            $signup = $jwtAuth->getConta148();
            if(!empty($params->gettoken)){
                $signup = $jwtAuth->getConta148();
            }
        }
        return response()->json($signup, 200);
    }


    public function saveConta148(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params) && !empty($params_array)){
            $params_array = array_map('trim', $params_array);

            $validate = Validator::make($params_array,[
                'periodo'=>'required',
                'fecini'=>'required',
                'fecfin'=>'required',
                'estado'=>'required'
            ]);

            $jwtAuth = new \JwtAuth();

            $bandera = $jwtAuth->consultarConta148($params_array['periodo']);

            if($bandera==true){
                $data = array(
                    'status'=>'error',
                    'code'=>404,
                    'message'=>'Periodo ya existe',
                    'bandera'=>'error' 
                );
            }else{
                if($validate->fails()){
                    $data = array(
                        'status'=>'error',
                        'code'=>404,
                        'message'=>'No se puede guardar conta148',
                        'bandera'=>'error',
                        'errors'=>$validate->errors() 
                    );
                }else{


                    $conta148 = Conta148::where('estado','A')->get();

                    if(count($conta148)>0){

                        $data = array(
                            'status' => 'error',
                            'code'   => 400,
                            'message' => 'Periodo NO Creado, existe un periodo activo!',
                            'bandera'=>'success'    
                        );

                    }else{
                        $conta148 = new Conta148;
                        $conta148 -> periodo = $params_array['periodo'];
                        $conta148 -> fecini = $params_array['fecini'];
                        $conta148 -> fecfin = $params_array['fecfin'];
                        $conta148 -> estado = $params_array['estado']; 
                        $conta148->save();
        
                        $data = array(
                            'status' => 'success',
                            'code'   => 200,
                            'message' => 'Periodo Creado!',
                            'bandera'=>'success'    
                        );
                    }



                }
            }

          
        }else{
            $data = array(
                'status' => 'error',
                'code'   => 404,
                'message' => 'Datos enviados no correctos conta148',
                'bandera'=>'error'      
            );
        }
        return response()->json($data, $data['code']);
    }

    public function Cerrar_Periodo(Request $request){
        $jwtAuth = new \JwtAuth();
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, [
            'periodo'=>'required',
            'fecini'=>'required',
            'fecfin'=>'required'
        ]);
        if($validate->fails()){
            $signup = array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Fallas en Cerrar periodo',
                'errors'=>$validate->errors()
            );

        }else{
            
            $numero = $jwtAuth->fecha_conta123($params->fecini, $params->fecfin);

            if(sizeof($numero)>0){

                foreach($numero as $nu){

                    

                    $faltantes_encontrado = $jwtAuth->traerFaltantesNumero($nu['numero']);

                    $sobrantesInv = $jwtAuth->pasar_sobrantes($nu['numero']);

                    $cerrarPeriodo = $jwtAuth->cerrarPeriodo($params->periodo);
                }
                $signup = array(
                    'status'=>'success',
                    'message'=>'Faltantes remplazados',
                    'sobrantes'=>$sobrantesInv,
                    'faltantes'=>$faltantes_encontrado,
                    'cerrarPeriodo'=>$cerrarPeriodo

                );

            }else{
                $signup = array(
                    'status'=>'success',
                    'message'=>'No Existen datos!'                
                );

            }

        }
        return response()->json($signup, 200);

    }
}
