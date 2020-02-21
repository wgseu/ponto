<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function process($path)
    {
        if (!Storage::exists($path)) {
            return abort(404);
        }
        if (preg_match('/^docs\/bills\//', $path)) {
            return $this->bills($path);
        }
        return abort(404);
    }

    private function bills($path)
    {
        if (!auth()->check() || !auth()->user()->can('conta:view')) {
            return redirect('/login');
        }
        return Storage::download($path);
    }
}
