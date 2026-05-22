<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::query()
            ->withCount('sales')
            ->orderBy('name')
            ->get();

        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        Customer::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'total_due' => 0,
        ]);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer added successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load([
            'sales' => fn ($query) => $query->latest()->with('items'),
            'payments' => fn ($query) => $query->latest(),
        ]);

        return view('customers.show', compact('customer'));
    }

    public function addPayment(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($customer, $validated) {
            $freshCustomer = Customer::query()
                ->lockForUpdate()
                ->findOrFail($customer->id);

            if ((float) $freshCustomer->total_due <= 0) {
                throw ValidationException::withMessages([
                    'amount' => ['This customer has no due balance.'],
                ]);
            }

            $amount = min(round((float) $validated['amount'], 2), (float) $freshCustomer->total_due);

            Payment::create([
                'customer_id' => $freshCustomer->id,
                'amount' => $amount,
                'note' => $validated['note'] ?? null,
            ]);

            $freshCustomer->decrement('total_due', $amount);
        });

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Payment recorded successfully.');
    }
}
