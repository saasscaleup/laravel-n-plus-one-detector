<?php

namespace Saasscaleup\NPlusOneDetector;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class NPlusOneDashboardController extends Controller
{
    public function index()
    {
        return view('n-plus-one::dashboard');
    }

    public function logs()
    {
        $logPath = storage_path('logs/n-plus-one.log');
        return response()->json([
            'logs' => File::exists($logPath) ? File::get($logPath) : '',
        ]);
    }
}