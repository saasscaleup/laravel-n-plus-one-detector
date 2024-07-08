<?php

namespace Saasscaleup\NPlusOneDetector;

use Illuminate\Routing\Controller;
use Saasscaleup\NPlusOneDetector\Models\NplusoneWarning;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * N+1 Dashboard Controller
 *
 * This controller handles the N+1 queries dashboard.
 * It displays the list of N+1 queries and allows for deleting specific queries.
 */
class NPlusOneDashboardController extends Controller
{
    /**
     * Display the N+1 queries dashboard.
     *
     * @param Request $request The HTTP request object.
     * @return \Illuminate\View\View The rendered view of the dashboard.
     */
    public function index(Request $request)
    {
        // Retrieve the N+1 queries from the database and paginate the results.
        $warnings = NplusoneWarning::orderBy('created_at', 'desc')
            ->paginate(config('n-plus-one.dashboard_records_pagination'));

        // Return the view with the retrieved queries.
        return view('n-plus-one::dashboard', compact('warnings'));
    }

    /**
     * Delete a specific N+1 query.
     *
     * @param Request $request The HTTP request object.
     * @param int $id The ID of the N+1 query to delete.
     * @return \Illuminate\Http\RedirectResponse Redirects to the dashboard.
     */
    public function destroy(Request $request, $id)
    {
        // Set the success message.
        $message = 'Record deleted successfully!';

        try {
            // Find the N+1 query with the given ID and delete it.
            $warning = NplusoneWarning::findOrFail($id);
            $warning->delete();

            // Flash the success message.
            session()->flash('success', $message);
        } catch (\Exception $e) {
            // If there was an error, flash an error message.
            $message = "Error deleting N+1 query record ({$e->getMessage()}). Please try again.";
            session()->flash('error', $message);

            // Log the error.
            Log::error('laravel-n-plus-one-detector->destroy: ' .$e->getMessage());
        }

        // Redirect back to the dashboard.
        return redirect()->route('n-plus-one.dashboard');
    }
}
