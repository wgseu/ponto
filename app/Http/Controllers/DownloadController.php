<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function bills($name)
    {
        if (!auth()->check() || !auth()->user()->can('conta:view')) {
            return redirect('/login');
        }
        if (!Storage::exists("/docs/accounts/$name")) {
            return abort(404);
        }
        return Storage::download("/docs/accounts/$name");
    }
}
