<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Conta19;
use App\Models\Conta124;
use App\Models\Conta123;
use App\Models\Conta65;
use App\Models\Gener02;
use App\Models\nomin02Emp;


class FormularioController extends Controller
{

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

    public function searchGener02(Request $request){
        $res = '';
        $query = nomin02Emp::query();
        $data = $request->input('search');


        if($data != ''){
            $query->whereRaw("docemp LIKE '%".$data."%'");
            $res=$this->convert_from_latin1_to_utf8_recursively($query->get());
        }else{
            $query='';
            $res = $this->convert_from_latin1_to_utf8_recursively($query);
        }
        return $res;
    }
}
