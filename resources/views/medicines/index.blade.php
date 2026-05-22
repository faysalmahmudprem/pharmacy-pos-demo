@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h1>Medicine Management</h1>
            <p>Add, update, deactivate, and track stock from one place.</p>
        </div>
    </div>

    <div class="grid grid-2">
        <section class="panel">
            <h2>Add Medicine</h2>
            <form method="POST" action="{{ route('medicines.store') }}" class="stack">
                @csrf
                <div class="row">
                    <div>
                        <label for="name">Brand Name</label>
                        <input id="name" name="name" required>
                    </div>
                    <div>
                        <label for="generic_name">Generic Name</label>
                        <input id="generic_name" name="generic_name">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="brand">Company</label>
                        <input id="brand" name="brand">
                    </div>
                    <div>
                        <label for="category">Category</label>
                        <input id="category" name="category" placeholder="Tablet, Syrup, Injection">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="barcode">Barcode</label>
                        <input id="barcode" name="barcode">
                    </div>
                    <div>
                        <label for="stock">Stock</label>
                        <input id="stock" name="stock" type="number" min="0" value="0" required>
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="purchase_price">Purchase Price</label>
                        <input id="purchase_price" name="purchase_price" type="number" step="0.01" min="0" required>
                    </div>
                    <div>
                        <label for="sell_price">Sell Price</label>
                        <input id="sell_price" name="sell_price" type="number" step="0.01" min="0" required>
                    </div>
                </div>
                <div>
                    <label><input type="checkbox" name="is_active" value="1" checked style="width:auto;"> Active</label>
                </div>
                <button class="btn btn-primary" type="submit">Save Medicine</button>
            </form>
        </section>

        <section class="panel soft">
            <h2>Medicine Notes</h2>
            <div class="stack meta">
                <div>Use inactive status when a product should stay in history but stop showing in POS.</div>
                <div>Barcode stays unique so future scanner support can plug in cleanly.</div>
                <div>Stock edits here are the manual stock update path from your PRD.</div>
            </div>
        </section>
    </div>

    <section class="panel" style="margin-top: 18px;">
        <div class="split">
            <h2>Medicine List</h2>
            <div class="meta">{{ $medicines->total() }} medicines</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Generic</th>
                    <th>Company</th>
                    <th>Category</th>
                    <th>Barcode</th>
                    <th>Buy</th>
                    <th>Sell</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($medicines as $medicine)
                    <tr>
                        <td colspan="10" style="padding: 0;">
                            <form method="POST" action="{{ route('medicines.update', $medicine) }}">
                                @csrf
                                @method('PUT')
                                <table style="width:100%; border-collapse: collapse;">
                                    <tr>
                                        <td><input name="name" value="{{ $medicine->name }}" required></td>
                                        <td><input name="generic_name" value="{{ $medicine->generic_name }}"></td>
                                        <td><input name="brand" value="{{ $medicine->brand }}"></td>
                                        <td><input name="category" value="{{ $medicine->category }}"></td>
                                        <td><input name="barcode" value="{{ $medicine->barcode }}"></td>
                                        <td><input name="purchase_price" type="number" step="0.01" min="0" value="{{ $medicine->purchase_price }}" required></td>
                                        <td><input name="sell_price" type="number" step="0.01" min="0" value="{{ $medicine->sell_price }}" required></td>
                                        <td><input name="stock" type="number" min="0" value="{{ $medicine->stock }}" required></td>
                                        <td>
                                            <select name="is_active">
                                                <option value="1" @selected($medicine->is_active)>Active</option>
                                                <option value="0" @selected(! $medicine->is_active)>Inactive</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="actions">
                                                <button class="btn btn-primary" type="submit">Update</button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <form method="POST" action="{{ route('medicines.destroy', $medicine) }}" style="padding: 0 8px 12px;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this medicine?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">No medicines found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $medicines->links() }}
        </div>
    </section>
@endsection
