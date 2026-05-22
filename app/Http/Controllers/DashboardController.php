<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $todaySales = Sale::query()->whereDate('created_at', $today)->sum('total');
        $todayRevenue = Sale::query()->whereDate('created_at', $today)->sum('paid');
        $todayInvoices = Sale::query()->whereDate('created_at', $today)->count();
        $dueAmount = Customer::query()->sum('total_due');
        $totalMedicines = Medicine::query()->count();
        $lowStock = Medicine::query()->where('stock', '<=', 10)->orderBy('stock')->get();
        $recentSales = Sale::query()->with('customer')->latest()->take(8)->get();

        return view('dashboard.index', compact(
            'todaySales',
            'todayRevenue',
            'todayInvoices',
            'dueAmount',
            'totalMedicines',
            'lowStock',
            'recentSales'
        ));
    }
}
