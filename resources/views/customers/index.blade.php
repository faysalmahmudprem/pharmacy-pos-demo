@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h1>Customers</h1>
            <p>Track due customers, ledger history, and payment collection.</p>
        </div>
    </div>

    <div class="grid grid-2">
        <section class="panel">
            <h2>Add Customer</h2>
            <form method="POST" action="{{ route('customers.store') }}" class="stack">
                @csrf
                <div class="row">
                    <div>
                        <label for="customer_name">Customer Name</label>
                        <input id="customer_name" name="name" required>
                    </div>
                    <div>
                        <label for="phone">Phone</label>
                        <input id="phone" name="phone">
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Save Customer</button>
            </form>
        </section>

        <section class="panel soft">
            <h2>Due Summary</h2>
            <div class="stack">
                <div class="meta">Customers with due can be selected during checkout and paid later from their ledger page.</div>
                <div class="meta">Walk-in sales stay supported, but due sales require a customer so the balance has somewhere to live.</div>
            </div>
        </section>
    </div>

    <section class="panel" style="margin-top: 18px;">
        <div class="split">
            <h2>Customer List</h2>
            <div class="meta">{{ $customers->count() }} customers</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Sales</th>
                    <th>Due</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone ?: '-' }}</td>
                        <td>{{ $customer->sales_count }}</td>
                        <td>
                            <span class="pill {{ $customer->total_due > 0 ? 'pill-warn' : 'pill-ok' }}">
                                {{ number_format($customer->total_due, 2) }}
                            </span>
                        </td>
                        <td>
                            <a class="btn btn-soft" href="{{ route('customers.show', $customer) }}">Open Ledger</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No customers found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
