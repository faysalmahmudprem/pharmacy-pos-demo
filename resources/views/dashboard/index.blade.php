@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h1>Dashboard</h1>
            <p>Quick counter summary for today and the stock that needs attention.</p>
        </div>
        <div class="actions">
            <a class="btn btn-primary" href="{{ route('pos.index') }}">Open POS</a>
            <a class="btn btn-soft" href="{{ route('medicines.index') }}">Manage Stock</a>
        </div>
    </div>

    <section class="stats">
        <div class="stat">
            <div class="stat-label">Today Sales</div>
            <div class="stat-value">{{ number_format($todaySales, 2) }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Today Revenue</div>
            <div class="stat-value">{{ number_format($todayRevenue, 2) }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Due Amount</div>
            <div class="stat-value">{{ number_format($dueAmount, 2) }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Today Invoices</div>
            <div class="stat-value">{{ $todayInvoices }}</div>
        </div>
    </section>

    <div class="grid grid-2" style="margin-top: 18px;">
        <section class="panel">
            <div class="split">
                <h2>Low Stock Alert</h2>
                <div class="meta">{{ $totalMedicines }} total medicines</div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Category</th>
                        <th>Stock</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($lowStock as $medicine)
                        <tr>
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->category ?: '-' }}</td>
                            <td>
                                <span class="pill {{ $medicine->stock <= 5 ? 'pill-danger' : 'pill-warn' }}">
                                    {{ $medicine->stock }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No low stock items right now.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <h2>Recent Sales</h2>
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Due</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentSales as $sale)
                        <tr>
                            <td><a href="{{ route('invoice.show', $sale) }}">{{ $sale->invoice_no }}</a></td>
                            <td>{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                            <td>{{ number_format($sale->total, 2) }}</td>
                            <td>{{ number_format($sale->due, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No sales yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
