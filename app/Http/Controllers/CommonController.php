<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommonController extends Controller
{
    public function showPhoto(Request $request)
    {
        return response()->file(storage_path('app/private/' . $request['img_path']));
    }

    public function deletePhoto($img_path)
    {
        if (isset($img_path) && Storage::disk('private')->exists($img_path)) {
            Storage::disk('private')->delete($img_path);
            return true;
        }
        return false;
    }

    public function formatException(\Exception $e)
    {
        return 'Message: ' . $e->getMessage() . ' Line: ' . $e->getLine();
    }
}
