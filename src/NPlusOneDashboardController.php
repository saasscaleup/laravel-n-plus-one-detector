<?php

namespace Saasscaleup\NPlusOneDetector;

use Illuminate\Routing\Controller;
use Saasscaleup\NPlusOneDetector\Models\NplusoneWarning;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NPlusOneDashboardController extends Controller
{
    public function index(Request $request)
    {
        $warnings = NplusoneWarning::orderBy('created_at', 'desc')->paginate(5); // Paginate the results
        return view('n-plus-one::dashboard', compact('warnings'));
    }

    public function destroy(Request $request, $id)
    {
        $message = 'Record deleted successfully!';

        try{
            $warning = NplusoneWarning::findOrFail($id);
            $warning->delete();
            session()->flash('success', $message);
        }catch(\Exception $e){
            $message = "Error deleting N+1 query record ({$e->getMessage()}). Please try again.";
            session()->flash('error', $message);
            Log::error('laravel-n-plus-one-detector->destroy: ' .$e->getMessage());
        }
        return redirect()->route('n-plus-one.dashboard');
    }
}