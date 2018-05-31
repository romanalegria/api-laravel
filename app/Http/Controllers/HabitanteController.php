<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\JwtAuth;
use App\Habitante;

class HabitanteController extends Controller
{
    public function index()
    {
       $habitantes = Habitante::all();
       return response()->json(array(
        'habitantes' => $habitantes,
        'status' => 'success'
       ),200);
    }

    public function show($id)
    {
        $habitante = Habitante::find($id);
        return response()->json(array('habitante' => $habitante, 'status' => 'success'));
    }


    public function store(Request $request)
    {
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
           
            //Recoger datos por post
            $json = $request->input('json',null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);


            // Conseguir el usuario identificado
            $user = $jwtAuth->checkToken($hash, true);

            //validacion                       
                $validate = \Validator::make($params_array,[
                    'nombre' => 'required|min:5',
                    'apellidos' => 'required',
                    'fecha_nacimiento' => 'required',                    
                ]);
          
                if($validate->fails()){
                    return response()->json($validate->errors(),400);
                }
            
            //Guardar el habitante
                $habitante = new Habitante();
                $habitante->nombre = $params->nombre;
                $habitante->apellidos = $params->apellidos;
                $habitante->fecha_nacimiento = $params->fecha_nacimiento;
                $habitante->save();

                $data = array(
                    'habitante' => $habitante,
                    'status' => 'success',
                    'code' => 200
                );
               
        }else{
            // Devolver un error
            $data = array(
                'message' => 'Login incorrecto',
                'status' => 'error',
                'code' => 300
            );
        }

        return response()->json($data, 200);
    }

    public function update($id, Request  $request)
    {
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);


        if($checkToken){  

            //Recorer parametros POST
              $json = $request->input('json',null);
              $params = json_decode($json);
              $params_array = json_decode($json,true);

            

            // Validar los datos            
            $validate = \Validator::make($params_array,[
               'nombre' => 'required|min:5',
                'apellidos' => 'required',
                'fecha_nacimiento' => 'required', 
            ]);
      
            if($validate->fails()){
                return response()->json($validate->errors(),400);
            }

            // Actualizar el habitante
            $habitante = Habitante::where('id','=',$id)->update($params_array);

            $data = array(
                'habitante' => $params,
                'status' => 'success',
                'code' => 200
            ); 

        }else
        {
            // Devolver un error
            $data = array(
                'message' => 'Login incorrecto',
                'status' => 'error',
                'code' => 300
            );
        } 

        return response()->json($data, 200);
    }


     public function destroy($id, Request  $request)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            // Comprobar que exista el registro
            $habitante = Habitante::find($id);

            //Borrarlo
            if(!is_null($habitante)) 
            {
               $habitante->delete();
               // Devolverlo
                 $data = array(
                    'habitante' => $habitante,
                    'status' => 'success', 
                    'code' => 200
                 );
            }else
            {
                $data = array(
                    'message' => 'ID incorrecto',
                    'status' => 'error',
                    'code' => 300
                );    
            }
            
        }else
        {
            // Devolver un error
            $data = array(
                'message' => 'Login incorrecto',
                'status' => 'error',
                'code' => 300
            );
        } 
        
        return response()->json($data, 200);
    }

} //end class
