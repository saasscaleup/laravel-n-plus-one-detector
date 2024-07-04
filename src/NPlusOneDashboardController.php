<?php

namespace Saasscaleup\NPlusOneDetector;

use Illuminate\Routing\Controller;
use Saasscaleup\NPlusOneDetector\Models\NplusoneWarning;
use Illuminate\Support\Facades\Session;

class NPlusOneDashboardController extends Controller
{
    public function index()
    {
        session(['success'=> 'Warning deleted successfully.']);

        $warnings = NplusoneWarning::orderBy('created_at', 'desc')->paginate(2); // Paginate the results
        $message = session()->pull('success');
        return view('n-plus-one::dashboard', compact('warnings'))->with('message', $message);
    }

    public function destroy($id)
    {
        $warning = NplusoneWarning::findOrFail($id);
        return redirect()->route('n-plus-one.dashboard');

    }
}