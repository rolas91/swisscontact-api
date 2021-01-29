<?php

namespace App\Http\Controllers;

use App\Functions\Emails;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function uploadimage(Request $request)
    {
        if ($request->hasFile('imagen')) {
            $file      = $request->file('imagen');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = date('His').'-'.$filename;
            $file->move(public_path('img'), $picture);
            return response()->json(["message" => "Image Uploaded Succesfully"]);
        } else {
            return response()->json(["message" => "Select image first."]);
        }
    }


    public function TestEmail(Request $request)
    {
        $correo= $request['correo'];
        Emails::EnviarCorreoPrueba($correo);
    }
}
