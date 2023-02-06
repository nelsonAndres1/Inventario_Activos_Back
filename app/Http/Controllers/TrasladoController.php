<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Conta19;
use App\Models\Conta124;
use App\Models\Conta123;
use App\Models\Conta65;
use App\Models\Gener02;

class TrasladoController extends Controller
{   
    public function updateConta19(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array=json_decode($json, true);


        if(!empty($params) && !empty($params_array)){
            $params_array = array_map('trim',$params_array);

            $validate = Validator::make($params_array, [
                'codact'=>'required',
                'coddep'=>'required',
                'codubi'=>'required',
                'cedtra'=>'required'
            ]);

            if($validate->fails()){
                $data = array(
                    'status'=>'error',
                    'code'=>404,
                    'message'=>'no se puede updateConta19',
                    'errors'=>$validate->errors()
                );
            }else{
                
                $codcen=str_split($params_array['coddep']);

                $codcenR=$codcen[0].$codcen[1].$codcen[2].$codcen[3];

                $codare=$codcen[0].$codcen[1];

                $conta19 = Conta19::where('codact','=',$params_array['codact'])
                ->where('cnt','01')->update(['codare'=>$codare]);

                $conta19 = Conta19::where('codact','=',$params_array['codact'])
                ->where('cnt','01')->update(['coddep'=>$params_array['coddep']]);


                $conta19 = Conta19::where('codact','=',$params_array['codact'])
                ->where('cnt','01')->update(['cedtra'=>$params_array['cedtra']]);

                $conta19 = Conta19::where('codact','=',$params_array['codact'])
                ->where('cnt','01')->update(['codcen'=>$codcenR]);

                $conta19 = Conta19::where('codact','=',$params_array['codact'])
                ->where('cnt','01')->update(['codubi'=>$params_array['codubi']]);

                $data = array(
                    'status' => 'success',
                    'code'   => 200,
                    'message' => 'si creado',
                );
            }
        }else{
            $data = array(
                'status' => 'error',
                'code'   => 404,
                'message' => 'no se puede updateConta19'      
            );
        }
        return response()->json($data, $data['code']);
    }
    public function SaveConta65(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params) && !empty($params_array)){
            $params_array = array_map('trim', $params_array);

            $validate = Validator::make($params_array, [
                'cnt'=>'required',
                'documento'=>'required',
                'usuario'=>'required',
                'codact'=>'required',
                'subcod'=>'required',
                'detalle'=>'required',
                'areori'=>'required',
                'depori'=>'required',
                'ubiori'=>'required',
                'cedori'=>'required',
                'aredes'=>'required',
                'depdes'=>'required',
                'ubides'=>'required',
                'cedtra'=>'required',
                'estado'=>'required'
            ]);

            if($validate->fails()){
                $data = array(
                    'status'=>'error',
                    'code'=>404,
                    'message'=>'no se puede saveconta65',
                    'errors'=>$validate->errors()
                );
            }else{

                $num = intval($params_array['documento']);
                $length = 7;
                $string = substr(str_repeat(0,$length).$num, - $length);

                $conta65 = new Conta65();
                $conta65->documento = $string;
                $conta65->cnt = $params_array['cnt'];
                $conta65->usuario=$params_array['usuario'];
                $conta65->fecha=date("Y-m-d");
                $conta65->codact=$params_array['codact'];
                $conta65->subcod=$params_array['subcod'];
                $conta65->detalle=$params_array['detalle'];
                $conta65->areori=$params_array['areori'];
                $conta65->depori=$params_array['depori'];
                $conta65->ubiori=$params_array['ubiori'];
                $conta65->cedori=$params_array['cedori'];
                $conta65->aredes=$params_array['aredes'];
                $conta65->depdes=$params_array['depdes'];
                $conta65->ubides=$params_array['ubides'];
                $conta65->cedtra=$params_array['cedtra'];
                $conta65->estado=$params_array['estado'];
                $conta65->save();

                $data = array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'si creado saveconta65'
                );
            }
            return response()->json($data, $data['code']);
        }
    }
    public function getConta116(Request $request){
        $jwtAuth = new \JwtAuth();
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, [
            'coddep'=>'required'
        ]);
        if($validate->fails()){
            $signup=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Noooo no!',
                'errors'=>$validate->errors()
            );
        
        }else{
            $signup = $jwtAuth->getConta116($params->coddep);
            if(!empty($params->gettoken)){
                $signup=$jwtAuth->getConta116($params->coddep);
            }
        }
        return response()->json($signup, 200);
    }
}
