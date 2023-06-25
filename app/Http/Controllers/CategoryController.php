<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;



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



    public function store(Request $request)
    {

        $validation = $this->validation($request);
        if ($validation instanceof Response) {
            return $validation;
        }
        $image = cloudinary()->upload($request->file('img')->getRealPath())->getSecurePath();

        $name = $request->name;
        $Categorys = Category::create([
            'name' => $name,
            'img' => $image
        ]);

        if ($Categorys) {
            return response()->json($Categorys, 200);
        }

        return response()->json("Cannot send this message", 400);
    }







    public function validation($request)
    {
        return $this->apiValidation($request, [
            'name' => 'required|min:3|max:30',
            'img' => 'required|image|mimes:jpg,jpeg,png',
        ]);
    }
}
