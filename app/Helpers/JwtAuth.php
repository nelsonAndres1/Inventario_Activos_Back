<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\Gener02;
use App\Models\Conta19;
use App\Models\Conta28;
use App\Models\nomin02;
use App\Models\Conta65;
use App\Models\Conta123;
use App\Models\Conta124;
use App\Models\nomin02Emp;
use App\Models\Conta116;
use App\Models\Gener21;
use App\Models\Conta148;
use App\Models\Conta12;
use App\Models\Conta20;
/* require_once("/resources/libs/UserReportPdf/UserReportPdf.php");
require_once("/resources/libs/UserReportExcel/UserReportExcel.php");
 */
class JwtAuth{

    public $key;
    
    
    public function __construct(){
        $this->key = '_clave_-32118';
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

        $cadena = str_replace(
            array('¤', '¥'),
            array('N', 'N'),
            $cadena
            );
		
		return $cadena;
	}


    public function getConta19_($codact){

        $datos=array();
        $signup = false;
        $conta19 = Conta19::where('codact','=',$codact)
        ->where('cnt','01')
        ->get();

        if(sizeof($conta19)>0){
            $signup = true;
        }else{
            $token = array(
                'status'=>'error',
                'message'=>'Activo no encontrado',
                'bendera'=>false
            );   
        }

        if($signup){
            foreach($conta19 as $c19){
                 $conta12 = Conta12::where('claact',$c19->claact)
                ->where('cnt','01')
                ->first();

                $conta20 = Conta20::where('tipo',$conta12->tipo)
                ->where('cnt','01')
                ->first();

                if($conta20){
                    $con_det = $conta20->detalle;
                }else{
                    $con_det = '';
                }


                $conta28 = Conta28::where('coddep','=',trim($c19->coddep))
                ->first();
                
                if($conta28){
                    $c19_detalle = $c19->coddep.' - '.$conta28->detalle;
                }else{
                    $c19_detalle = '';
                }

                
                $nomin02 = nomin02Emp::where('docemp','=',trim($c19->cedtra))->first(); 

                if($nomin02){
                    $nombre = $this->eliminar_acentos($nomin02->priape).' '.$this->eliminar_acentos($nomin02->segape).' '.$this->eliminar_acentos($nomin02->nomemp).' '.$this->eliminar_acentos($nomin02->segnom);

                }else{
                    $nombre = '';
                }

                $token = array(
                     'codact'=>$c19->codact,
                     'claact'=>$con_det,
                     'coddep'=>utf8_decode($c19_detalle),
                     'nombre'=>$nombre
                );

                array_push($datos, $token);


                $datos = $this->convert_from_latin1_to_utf8_recursively($datos);
            }
       

        }else{
            $datos = array(
                'status'=>'error',
                'message'=>'Activo no encontrado',
                'bendera'=>false
            );   
        }
        $jwt = JWT::encode($datos, $this->key, 'HS256');
        $decoded = JWT::decode($jwt, $this->key, ['HS256']); 
    
        $data = $decoded;

        return $data;
    }


    public function traerCedula($cedtra){

        $nomin02 = nomin02Emp::where('docemp',trim($cedtra))->get();

        
        $signup = false;
        if(sizeof($nomin02)>0){
            $signup = true;
        }else{

            $token = array(
                'status'=>'error',
                'message'=>'Usuario no encontrado',
                'bendera'=>false
            );
            
        }
        if($signup){
            foreach($nomin02 as $que){
                $token = array(
                    'docemp'=>$que->docemp,
                    'ciuced'=>$que->ciuced,
                    'coddoc'=>$que->coddoc,
                    'priape'=>utf8_decode($this->eliminar_acentos($que->priape)),
                    'segape'=>utf8_decode($this->eliminar_acentos($que->segape)),
                    'nomemp'=>utf8_decode($this->eliminar_acentos($que->nomemp)),
                    'segnom'=>utf8_decode($this->eliminar_acentos($que->segnom)),
                    'fecnac'=>$que->fecnac,
                    'codciu'=>$que->codciu,
                    'codsex'=>$que->codsex,
                    'estciv'=>$que->estciv,
                    'codzon'=>$que->codzon,
                    'coddep'=>$que->coddep,
                    'coddes'=>$que->coddes,
                    'tipnom'=>$que->tipnom,
                    'tipcon'=>$que->tipcon,
                    'contra'=>$que->contra,
                    'codsal'=>$que->codsal,
                    'bandera'=>true,
                );

            }
        }

        return $this->convert_from_latin1_to_utf8_recursively($token);

    }


    public function fecha_conta123($fecini, $fecfin){

        $getToken = true;
        $conta123 = Conta123::whereBetween('fecha', [$fecini, $fecfin])->get();

        $signup = false;
        if(is_object($conta123)){
            $signup = true;
        }
        if($signup){
            $token = array();
            foreach($conta123 as $c123){
                $token1 = array(
                    'numero' =>$c123->numero
                );
                array_push($token, $token1);
            }            
            if(is_null($getToken)){
                $data = $token;
            }else{
                $data = $token;
            }
        }else{
            $data = array(
                'status'=>'error',
                'message'=>'error JWT fecha_conta123'
            );
        }
        return $data;

    }

    public function cerrarPeriodo($periodo){

        $Cerrarperiodo = Conta148::where("periodo",$periodo)
        ->update(["estado"=>'C']);

        if($Cerrarperiodo==true){
            $data = array(
                'status'=>'success',
                'message'=>'Periodo Cerrado'
            );
        }else{
            $data = array(
                'status'=>'error',
                'message'=>'Periodo no cerrado'
            );
        }
        return $data;
    }

    public function pasar_sobrantes($numero){


        $sobrantes =Conta124::where("numero", $numero)
        ->where("estinv","S")
        ->update(["estinv"=>'Z']);

        if($sobrantes==true){
            $data = array(
                'status'=>'success',
                'message'=>'Sobrantes Actualizados'
            );
            

        }else{
            $data = array(
                'status'=>'error',
                'message'=>'Sobrantes No Actualizados'
            );
        }
        return $data;

    }

    public function preguntarContinuarInventario($cedtra){
        $conta148 = Conta148::where("estado","A")->get();
        $fecini = '';
        $fecfin = '';
        $numero = 0;
        if(count($conta148)>0){
            foreach($conta148 as $c148){
                $fecini = $c148->fecini;
                $fecfin = $c148->fecfin;
            }
            $conta123 = Conta123::where('cedori',$cedtra)->whereBetween('fecha', [$fecini, $fecfin])->get();
            
            if(count($conta123)>0){
                foreach($conta123 as $c123){
                    $numero = $c123->numero;
                }
                $conta124 = Conta124::where('numero',$numero)->where('estinv','I')->orWhere('estinv', '=', 'S')->get();
                $arrayCodAct = array();
                $arrayActivos = array();
                if(count($conta124)>0){
                    foreach($conta124 as $c124){
                        $arrayCodAct = array(
                            'codact' =>$c124->codact,
                            'estado' =>$c124->estado,
                            'observacion'=>utf8_decode($c124->observacion)
                        );
                        $arrayActivos[] = $arrayCodAct;
                    }
                    $data = $arrayActivos;
                }
            }else{
                $data = array(
                    'status'=>'error',
                    'message'=>'No existen activos inventariados!',
                    'res'=>$conta123
                );                
            }
           
        }else{
            $data = array(
                'status'=>'error',
                'message'=>'No exite periodo Activo'
            );
        }

        return $data;
    }

    public function traerFaltantesNumero($numero){
        
        $faltantes = Conta124::where("numero",$numero)
        ->where("estinv","F")->get();

        $signup = false;
        if(sizeof($faltantes)>0){
            $signup = true;
        }else{
            $data = array(
                'status'=>'error',
                'message'=>'sin activos registrados'
            );
        }

        if($signup){
            foreach($faltantes as $fa){
                $sobrantes = Conta124::where("codact",$fa->codact)
                ->where("estinv","S")->get();
                if(sizeof($sobrantes)>0){
                    
                    $faltante = Conta124::where("numero",$numero)
                    ->where("codact",$fa->codact)
                    ->where("estinv","F")
                    ->delete();

                    $data = array(
                        'status'=>'success',
                        'message'=>'eliminado'.$fa->codact
                    );
                }else{

                    $data = array(
                        'status'=>'success',
                        'message'=>'NO eliminado'.$fa->codact
                    );
                }

            }
        }else{
            $data = array(
                'status'=>'success',
                'message'=>'No Encontrado!'
            );
        }

        return $data;

    }



    public function nombre_nomin02($docemp){

        $getToken = true; 
        $nomin02 = nomin02::where("docemp","=",$docemp)->get();

        if(is_object($nomin02)){
            foreach($nomin02 as $n02){
                $datos = $n02->nomemp.' '.$n02->segnom.' '.$n02->priape.' '.$n02->segape;
            
            }

            $jwt = JWT::encode($datos, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']); 
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data = $decoded;
            }

        }
        return $array;

    }



    public function permisos($docemp){
        $getToken = true;
        $datos=array();

        $nomin02 = nomin02::where("docemp","=",$docemp)->get();

        if(is_object($nomin02)){
            foreach($nomin02 as $n02){
                $array = array(
                    'coddep'=>$n02->coddep
                );
            array_push($datos, $array);
            }
        }
        $jwt = JWT::encode($datos, $this->key, 'HS256');
        $decoded = JWT::decode($jwt, $this->key, ['HS256']); 
        if(is_null($getToken)){
            $data = $jwt;
        }else{
            $data = $decoded;
        }
        return $data;
    }


    public function consulta(){
        $getToken = true;
        $gener21 = Gener21::get();
        $dato=0;
        $array = array();
        $arrayF = array();
        foreach($gener21 as $g21){
            $gener02=Gener02::where("tipfun","=",$g21->tipfun)
            ->where("estado","=","A")
            ->get();
            $dato=count($gener02);
            $array = array(
                'codfun'=>$g21->tipfun,
                'Funcionario'=>$g21->detalle,
                'Total'=>$dato
            );
        array_push($arrayF, $array);
        }
        $jwt = JWT::encode($arrayF, $this->key, 'HS256');
        $decoded = JWT::decode($jwt, $this->key, ['HS256']); 
        if(is_null($getToken)){
            $data = $jwt;
        }else{
            $data = $decoded;
        }
        return $data;
    }

    function write_to_console($data) {
        $console = $data;
        if (is_array($console)){
            $console = implode(',', $console);
        }
        echo "<script>console.log('Console: " . $console . "' );</script>";
    }
    public function getConta116($coddep){
        $getToken = true;
        $conta116 = Conta116::where(
            "coddep","=",$coddep
        )->where(
            "cnt",'=',"01"
        )->get();
        $signup=false;
        if(is_object($conta116)){
            $signup = true;
        }
        if($signup){
            $token=array();
            foreach($conta116 as $c116){
                $token1 = array(
                    'codubi'=>$c116->codubi,
                    'detalle'=>$c116->detalle
                );
                array_push($token, $token1);
            }
            if(is_null($getToken)){
                $data = $token;
            }else{
                $data = $token;
            }
        }else{
            $data = array(
                'status'=>'error',
                'message'=>'error JWT getConta116'
            );
        }
        return $data;
    }

    public function getConta19($cedtra){
        $conta19 = Conta19::where(
            "cedtra","=", $cedtra
        )->where(
            "estado","!=","B"
        )->where(
            "estado","!=","V"
        )->where(
            "cnt","=","01"
        )->orderBy('codact', 'ASC')
        ->get();

        $signup=false;
        if(is_object($conta19)){
            $signup = true;
        }
        if($signup){
            $n=1;
            $token = array();
            foreach($conta19 as $c10){
                $ubicacion = Conta28::select('detalle')->where('coddep','=',$c10->coddep)->where('cnt','=','01')->get();
                    foreach ($ubicacion as $ubi) {
                        $token1 = array(
                            'numero'=>$n,
                            'codact'=>$c10->codact,
                            'coddep'=>$c10->coddep,
                            'detalle'=>utf8_decode($c10->detalle),
                            'dependencia'=>$ubi->detalle,
                            'cedtra'=>$c10->cedtra,
                            'codare'=>$c10->codare,
                            'coddep'=>$c10->coddep,
                            'usuario'=>$c10->usuario,
                            'subcod'=>$c10->subcod,
                            'codbar'=>$c10->codbar,
                            'codubi'=>$c10->codubi,
                            'codcen'=>$c10->codcen,
                            'est'=>$c10->estado,
                            'checked'=>false
                        );
                        $ubicacion='';
                        array_push($token, $token1);
                        $n=$n+1; 
                    }
           
            }
            $data=$token;

        }else{
            array(
                'status' => 'error',
                'message' => 'Datos No Incorrectos'
            );
        }
        return $this->convert_from_latin1_to_utf8_recursively($data);
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
    /* public function getConta19($cedtra){
        $getToken = true;
        $signup = false;

        $conta19 = Conta19::where(
            "cedtra","=",$cedtra
        )->get();
        
        if(is_object($conta19)){
            $signup = true;
        }else{
            $data = array(
                'status' => 'error',
                'message' => 'Datos Incorrectos'
            );
        }
        if($signup){
            $n=1;
            $token = array();
            foreach($conta19 as $c10){
                
                $ubicacion = Conta28::where('coddep','=',$c10->coddep)->where('cnt','=','01')->get();
                foreach ($ubicacion as $ubi) {
                    $token1 = array(
                        'numero'=>$n,
                        'codact'=>$c10->codact,
                        'coddep'=>$c10->coddep,
                        'detalle'=>$c10->detalle,
                        'dependencia'=>$ubi->detalle,
                        'cedtra'=>$c10->cedtra,
                        'codare'=>$c10->codare,
                        'coddep'=>$c10->coddep,
                        'usuario'=>$c10->usuario,
                        'subcod'=>$c10->subcod,
                        'codbar'=>$c10->codbar,
                        'codubi'=>$c10->codubi,
                        'codcen'=>$c10->codcen,
                        'est'=>$c10->estado
                    );
                    array_push($token, $token1);
                    $n=$n+1; 
                }
            }
            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data = $decoded;
            }
        }else{
            array(
                'status' => 'error',
                'message' => 'Datos No Incorrectos'
            );
        }
    return $data;

    } */


    public function findGener02($cedtra){
        $getToken = true;

/*         $gener02 = Gener02::where(
            'cedtra',"=",$cedtra
        )->first();
        
        $signup = false;
        if(is_object($gener02)){
            $signup = true;
        } */
        if(true){        
            $nomin02 = nomin02Emp::where(
                'docemp','=',trim($cedtra)
            )->first();
            if(is_object($nomin02)){
                $token = array(
                    'nombre'=>$this->eliminar_acentos($nomin02->nomemp).' '.$this->eliminar_acentos($nomin02->segnom).' '.$this->eliminar_acentos($nomin02->priape).' '.$this->eliminar_acentos($nomin02->segape), 
                    'cedtra'=>$nomin02->docemp,
                    'coddep'=>$nomin02->coddep,
                    'bandera'=>true
                );
            }else{
                $token = array(
                    'nombre'=>$gener02->nombre,
                    'cedtra'=>$gener02->cedtra,
                    'coddep'=>'',
                    'bandera'=>true
                );
            }
            $jwt = JWT::encode($this->convert_from_latin1_to_utf8_recursively($token), $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']); 
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data = $decoded;
            }
        }else{
            $data = array(
                'status' => 'error',
                'message' => 'Login Incorrecto',
                'bandera' => false
            );
        }
        return $this->convert_from_latin1_to_utf8_recursively($data);
    }
    public function signup($usuario, $clave, $getToken = null){
        //Buscar
        $gener02 = Gener02::where([
            'usuario' =>$usuario,
            'clave' =>$clave
        ])->first();
        //Comprobar si son correctas
        $signup = false;
        if(is_object($gener02)){
            $signup = true;
        }
        //Generar el token con los datos del identificado
        if($signup){
            $token = array(
                'sub' => $gener02->usuario,
                'email' => $gener02->email,
                'name' => $gener02->nombre,
                'cedtra' => $gener02->cedtra,
                'iat' => time(),
                'exp' => time()+(7*24*60*60)
            );
            $jwt=JWT::encode($token, $this->key, 'HS256');
            //Devolver los datos identificados o el token, en funcion de un parametro
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            if(is_null($getToken)){
                $data = $jwt;

            }else{
                $data = $decoded;
            }
        }else{
            $data = array(
                'status' => 'error',
                'message' => 'Login Incorrecto'
            );
        }
        return $data;
    }

    public function checkToken($jwt, $getIdentity= false){
        
        $auth = false;
        try {
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
       
        } catch (\UnexpectedValueException $e) {
            //throw $th;
            $auth = false;
        }catch (\DomainException $e){
            $auth = false;
        }
        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }else{
            $auth = false;
        }
        if($getIdentity){
            return $decoded;
        }
        return $auth;
    }

    public function getConta65(){
        $getToken = true;
        $conta65 = Conta65::select('documento')->orderByDesc('documento')->limit(1)->get();
        $signup = false;

        if(is_object($conta65)){
            $signup = true;
        }
        if($signup){
            $token=array();
            foreach($conta65 as $c65){
                $token1 = array(
                    'documento'=>$c65->documento
                );
                array_push($token, $token1);
            }
            if(is_null($getToken)){
                $data = $token;
            }else{
                $data = $token;
            }
        }else{
            $data = array(
                'status'=>'error',
                'message'=>'error JWT conta650'
            );
        }
        return $data;
    }

    public function consultarConta148($periodo){

        $conta148 = Conta148::where('periodo',$periodo)->get();

        if(sizeof($conta148)>0){
            return true;
        }else{
            return false;
        }
    }

    public function getConta148_C(){

        $conta148 = Conta148::where('estado','C')->get();
        $getToken = true;
        if(is_object($conta148)){
            $signup = true;
        }
        if($signup){
            $token = array();
            foreach($conta148 as $c148){
                $token2 = array(
                    'periodo'=>$c148->periodo,
                    'fecini'=>$c148->fecini,
                    'fecfin'=>$c148->fecfin,
                    'estado'=>$c148->estado
                );
                array_push($token, $token2);
            }
            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data =$decoded;
            }
        }else{
            $data = array(
                'status' => 'error',
                'message' => 'No existen periodos abiertos!',
                'bandera' => false
            );

        }
        return $data;
    }

    public function getConta148(){

        $conta148 = Conta148::where('estado','A')->get();
        $getToken = true;
        if(is_object($conta148)){
            $signup = true;
        }
        if($signup){
            $token = array();
            foreach($conta148 as $c148){
                $token2 = array(
                    'periodo'=>$c148->periodo,
                    'fecini'=>$c148->fecini,
                    'fecfin'=>$c148->fecfin,
                    'estado'=>$c148->estado
                );
                array_push($token, $token2);
            }
            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data =$decoded;
            }
        }else{
            $data = array(
                'status' => 'error',
                'message' => 'No existen periodos abiertos!',
                'bandera' => false
            );

        }
        return $data;
    }


    public function consultConta19($codact,$cedtra){
        $aredes = '';
        $depdes = '';
        $ubides = '';

        $getToken = true;
        $conta19=Conta19::where('codact',$codact)->where('cnt','01')->first();
        $signup = false;
        if(is_object($conta19)){
            $signup = true;
        }
        if($signup){
    
            $nomin02 = nomin02Emp::where('docemp','=','"'.trim($cedtra).'"')->first();

            if(is_object($nomin02)){

                foreach($nomin02 as $n2){
                    $coddep = $n2->coddep;
                    $arr2 = str_split($coddep);
                    $aredes = $arr2[0].$arr2[1];
                    $depdes = $coddep;
    
                    $token = array(
                        'codact'=>$conta19->codact,
                        'subcod'=>$conta19->subcod,
                        'areori'=>$conta19->codare,
                        'depori'=>$conta19->coddep,
                        'ubiori'=>$conta19->codubi,
                        'cedori'=>$conta19->cedtra,
                        'aredes'=>$aredes,
                        'depdes'=>$depdes,
                        'estado'=>'C'
                    );
                }

                $coddep = $nomin02->coddep;
                $arr2 = str_split($coddep);
                $aredes = $arr2[0].$arr2[1];
                $depdes = $coddep;

                $token = array(
                    'codact'=>$conta19->codact,
                    'subcod'=>$conta19->subcod,
                    'areori'=>$conta19->codare,
                    'depori'=>$conta19->coddep,
                    'ubiori'=>$conta19->codubi,
                    'cedori'=>$conta19->cedtra,
                    'aredes'=>$aredes,
                    'depdes'=>$depdes,
                    'estado'=>'C',
                    'checked'=>false
                );


            }else{
                $token = array(
                    'codact'=>$conta19->codact,
                    'subcod'=>$conta19->subcod,
                    'areori'=>$conta19->codare,
                    'depori'=>$conta19->coddep,
                    'ubiori'=>$conta19->codubi,
                    'cedori'=>$conta19->cedtra,
                    'aredes'=>'',
                    'depdes'=>'',
                    'estado'=>'C',
                    'checked'=>false
                );
            }

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data =$decoded;
            }   
        }else{
            $data = array(
                'status' => 'error',
                'message' => $codact,//el codact no se encuentra en la conta65
                'bandera' => false
            );
        }
        return $data;
    }
    public function reporteHistorico($cedori){

        $cedori=trim($cedori);
        $variable = 'A';
        $getToken = true;
        $conta123 = Conta123::where([
            'cedori'=>$cedori,
            'estado'=>$variable
        ])->get();
        $signup = false;
        if(is_object($conta123)){
            $signup = true;
        }
        if($signup){
            $token = array();
            foreach($conta123 as $c23){
                $token2 = array(
                    'numero'=>$c23->numero,
                    'fecha'=>$c23->fecha
                );
                array_push($token, $token2);
            }
            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data =$decoded;
            }
        }else{
            $data = array(
                'status' => 'error',
                'message' => $codact,//el codact no se encuentra en la conta65
                'bandera' => false
            );
        }
        return $data;
    }


    public function reporte_general($fecini, $fecfin){
        
        $conta124 = Conta124::whereBetween('fecha', [$fecini, $fecfin])->get();
        $getToken = true;
        if(is_object($conta124)){
            $token = array();
            foreach($conta124 as $c124){

                $conta19 = Conta19::select('detalle')->where('codact',$c124->codact)->first();
                
                $token2 = array(
                    'numero'=>$c124->numero,
                    'fecha'=>$c124->fecha,
                    'codact'=>$c124->codact,
                    'detalle'=>utf8_decode($conta19->detalle),
                    'estact'=>$c124->estact,
                    'estinv'=>$c124->estinv,
                    'observacion'=>utf8_decode($c124->observacion)
                );
                array_push($token, $token2);
            }

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data =$decoded;
            }
        }else{
            $data = array(
                'status' => 'error',
                'message' => 'No es objeto',
                'bandera' => false
            );
        }
        return $data;
    }


    public function reporteH2($numero){
        $getToken = true;
        $conta124 = Conta124::where([
            'numero'=>$numero
        ])->get();
        if(is_object($conta124)){
            $token = array();
            foreach($conta124 as $c24){
                $conta19=Conta19::select('detalle','fecsis')->where('codact','=',$c24->codact)->first();
                $conta28=Conta28::select('detalle','codcen')->where('coddep','=',$c24->coddep)->where('cnt','=','01')->first();
                $token2 = array(
                    'codact'=>$c24->codact,
                    'detalle'=>utf8_decode($conta19->detalle),
                    'dependencia'=>utf8_decode($conta28->detalle),
                    'fecha'=>$conta19->fecsis,
                    'estado'=>$c24->estinv
                );
                array_push($token, $token2);
            }
            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data =$decoded;
            }
        }else{
            $data = array(
                'status' => 'error',
                'message' => 'No es objeto',
                'bandera' => false
            );
        }
        return $data;
    }
    public function reporte($cedori){
        
        $cedori=trim($cedori);

        $variable = 'A';
        $getToken = true;
         $conta123 = Conta123::where([
            'cedori'=>$cedori,
            'estado'=>$variable
        ])->orderByDesc('numero')->limit(1)->get();
        
        $signup = false;

        if(is_object($conta123)){
            $signup = true;
        }
        if($signup){
            $token = array();
            foreach($conta123 as $c23){
                $conta124 = Conta124::where([
                    'numero'=>$c23->numero
                ])->orderBy('codact', 'ASC')->get();
                
                foreach($conta124 as $c24){
                    $conta19=Conta19::select('detalle','fecsis')->where('codact','=',$c24->codact)->first();
                    $conta28=Conta28::select('detalle','codcen')->where('coddep','=',$c24->coddep)->first();
                    $token2 = array(
                        'codact'=>$c24->codact,
                        'detalle'=>utf8_decode($conta19->detalle),
                        'dependencia'=>utf8_decode($conta28->detalle),
                        'fecha'=>$conta19->fecsis,
                        'estado'=>$c24->estinv,
                        'estado_fisico'=>$c24->estact
                    );
                    array_push($token, $token2);
                }   
            }
            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data =$decoded;
            }
        }else{
            $data = array(
                'status' => 'error',
                'message' => $codact,//el codact no se encuentra en la conta65
                'bandera' => false
            );
        }
        return $data;
    }
    
}