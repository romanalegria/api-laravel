<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Helpers\JwtAuth;
use App\Data\Dao\HomeDao;
use App\Util\validatorUtil;

class HomeController extends Controller
{
    public function index()
    {
       $homes = HomeDao::all();
       return response()->json(array(
        'hogares' => $hogares,
        'status' => 'success'
       ),200);
    }

   

    public function show($id)
    {
        $home = HomeDao::byId($id);
		$data = ControllerResponses::okResp($home);
        return response()->json($data, $data->code);
    }


    public function store(Request $request)
    {
        /* Validar token */
        //$hash = $request->header('Authorization', null);

        //$jwtAuth = new JwtAuth();
        //$checkToken = $jwtAuth->checkToken($hash);

        
        //if($checkToken)
        //{
           
            //Recoger datos por post
            $json = $request->input('json',null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);


            // Conseguir el usuario identificado
            //$user = $jwtAuth->checkToken($hash, true);

            //validacion                       
                $validate = \Validator::make($params_array,[
                    'nickHogar' => 'required|min:5',
                    'avatar' => 'required',
                    'mail' => 'required',
                    'password' => 'required',
                ]);
          
                if($validate->fails()){
                    return response()->json($validate->errors(),400);
                }
                
                $pwd = hash('sha256',$params->password);
                
				$home = HomeDao::save($params->nickHogar, $params->avatar, $params->mail,$pwd);
				
				if($home != null){
					$data = ControllerResponses::createdResp(['id' => $home->id_hogar, 'image' => $params->avatar, 'email' => $params->mail, 'nick' => $params->nickHogar] );
				}else{
					$data = ControllerResponses::notFoundResp();
				}
        //}else
        //{
			//$data = ControllerResponses::unprocesableResp();
        //}  

        /*Fin validar token */

        return response()->json($data, $data->code);
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
            $habitant = HabitantDao::save($params->nombre, $params->apellidos, $params->fecha_nacimiento, $params->id);
			
			if($habitant != null){
				$data = array(
					'habitante' => $habitante,
					'status' => 'success',
					'code' => 200
				);
			}else{
				$data = array(
					'habitante' => null,
					'status' => 'error',
					'code' => 404
				);
			}

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

    public function validateMail(Request $request)
    {

          //Recorer parametros POST
          $json = $request->input('json',null);
          $params = json_decode($json);
          $params_array = json_decode($json,true);

          //validar si el mail viene con un formato correcto
          $mail = validatorUtil::checkMail($params->mail);
        
          if($mail != null)
          {                         
                // Obtenemos el Mail
                $mailHome = HomeDao::getMail($params->mail);
                
                if($mailHome != null){                   
                    $data = ControllerResponses::okResp(['exists'=>'true','message'=>'El correo ingresado ya existe']);
                }else{
                    $data = ControllerResponses::notFoundResp();
                }  
            }else
            {
                $data = ControllerResponses::nadRequestResp();
            } 
        

             

        return response()->json($data, $data->code);
    }

} //end class