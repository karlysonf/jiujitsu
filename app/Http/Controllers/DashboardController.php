<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        if (auth()->user()->hasRole('root')) {
            return redirect()->route('root.tenants.index');
        }

        $data = $this->dashboardService->getDashboardData();
        return view('dashboard', $data);
    }
}
