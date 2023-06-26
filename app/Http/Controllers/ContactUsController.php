<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contactUs = ContactUs::all();
        return response()->json($contactUs, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateStoreRequest($request);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        $contactUs = ContactUs::create([
            'email' => $request->email,
            'name' => $request->name,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        if (is_null($contactUs)) {
            return response()->json("Cannot store this message", 400);
        }
        return response()->json($contactUs, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contactUs = ContactUs::find($id);
        if (is_null($contactUs)) {
            return response()->json("Contact us not found", 404);
        }
        return response()->json($contactUs, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contactUs = ContactUs::find($id);
        if (is_null($contactUs)) {
            return response()->json("Contact us not found", 404);
        }
        $contactUs->delete();
        return response()->json(null, 204);
    }

    /**
     * Validate the incoming request for the store method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateStoreRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required|min:3|max:20',
            'subject' => 'required|min:5|max:50',
            'message' => 'required|min:10|max:191',
        ]);
    
        return $validator;
    }
}
