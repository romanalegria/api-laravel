<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use App\Helpers\JwtAuth;
use App\Car;


class CarController extends Controller
{
    public function index()
    {
       $cars = Car::all()->load('user');
       return response()->json(array(
        'cars' => $cars,
        'status' => 'success'
       ),200);
        
    }

    public function show($id)
    {
        $car = Car::find($id)->load('user');
        return response()->json(array('car' => $car, 'status' => 'success'));
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
                    'title' => 'required|min:5',
                    'description' => 'required',
                    'price' => 'required',
                    'status' => 'required',
                    'price' => 'required'
                ]);
          
                if($validate->fails()){
                    return response()->json($validate->errors(),400);
                }
            
            //Guardar el auto             
                $car = new Car();
                $car->user_id = $user->sub;
                $car->title = $params->title;
                $car->description = $params->description;
                $car->price = $params->price;
                $car->status = $params->status;
                $car->save();

                $data = array(
                    'car' => $car,
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
                'title' => 'required|min:5',
                'description' => 'required',
                'price' => 'required',
                'status' => 'required',
                'price' => 'required'
            ]);
      
            if($validate->fails()){
                return response()->json($validate->errors(),400);
            }

            // Actualizar el car
            $car = Car::where('id','=',$id)->update($params_array);

            $data = array(
                'car' => $params,
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
            $car = Car::find($id);

            //Borrarlo
            if(!is_null($car)) 
            {
               $car->delete();
               // Devolverlo
                 $data = array(
                    'car' => $car,
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
