<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\Category;




class CategoryController extends Controller
{
    use ApiResponseTrait;



    public function index()
    {
        $Categorys = Category::with('courses')->get();
        return $this->apiResponse($Categorys);
    }

    public function show($id)
    {
        $Category = Category::with('courses')->find($id);
        if ($Category) {
            return $this->apiResponse($Category);
        }
        return $this->notFoundResponse();
    }













}
