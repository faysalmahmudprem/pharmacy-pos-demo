<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::query()
            ->latest()
            ->paginate(20);

        return view('medicines.index', compact('medicines'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateMedicine($request);

        Medicine::create($validated);

        return redirect()
            ->route('medicines.index')
            ->with('success', 'Medicine added successfully.');
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $this->validateMedicine($request, $medicine->id);

        $medicine->update($validated);

        return redirect()
            ->route('medicines.index')
            ->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()
            ->route('medicines.index')
            ->with('success', 'Medicine deleted successfully.');
    }

    private function validateMedicine(Request $request, ?int $medicineId = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'generic_name' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'barcode' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('medicines', 'barcode')->ignore($medicineId),
            ],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
