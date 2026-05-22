<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Medicine;

class POSController extends Controller
{
    public function index()
    {
        $medicines = Medicine::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $customers = Customer::query()
            ->orderBy('name')
            ->get();

        return view('pos.index', compact('medicines', 'customers'));
    }
}
