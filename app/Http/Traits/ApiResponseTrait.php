<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Validator;

trait ApiResponseTrait{

     public $paginateNumber = 10;

/*
 * [
 *  'data' =>
 *  'status' => true , false
 *  'error' => ''
 * ]
 */

public function apiResponse($data = null , $error = null , $code = 200){

    $array = [
        'data' => $data,
        'status' => in_array($code , $this->successCode()) ? true : false,
        'error' => $error
    ];

    return response($array , $code);

}

public function successCode(){
    return [
        200 , 201 , 202
    ];
}

public function createdResponse($data){
    return $this->apiResponse($data, null, 201);
}

public function deleteResponse(){
    return $this->apiResponse(true, null, 200);
}







}


?>
