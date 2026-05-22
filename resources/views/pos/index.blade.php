@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h1>POS Counter</h1>
            <p>Search fast, add to cart, validate stock, and finish the sale in one screen.</p>
        </div>
    </div>

    <div class="grid grid-2">
        <section class="panel">
            <div class="split">
                <h2>Medicine Search</h2>
                <input id="searchInput" placeholder="Search by name, generic, company, barcode" style="max-width: 360px;">
            </div>

            <div class="table-wrap" style="margin-top: 12px; max-height: 640px; overflow-y: auto;">
                <table>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Category</th>
                        <th>Barcode</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="medicineTable">
                    @foreach($medicines as $medicine)
                        <tr
                            data-search="{{ strtolower($medicine->name . ' ' . $medicine->generic_name . ' ' . $medicine->brand . ' ' . $medicine->barcode) }}"
                            data-id="{{ $medicine->id }}"
                            data-name="{{ $medicine->name }}"
                            data-price="{{ $medicine->sell_price }}"
                            data-stock="{{ $medicine->stock }}"
                        >
                            <td>
                                <strong>{{ $medicine->name }}</strong>
                                <div class="meta">{{ $medicine->generic_name ?: 'No generic' }}</div>
                            </td>
                            <td>{{ $medicine->brand ?: '-' }}</td>
                            <td>{{ $medicine->category ?: '-' }}</td>
                            <td>{{ $medicine->barcode ?: '-' }}</td>
                            <td>{{ number_format($medicine->sell_price, 2) }}</td>
                            <td>
                                <span class="pill {{ $medicine->stock <= 5 ? 'pill-danger' : ($medicine->stock <= 10 ? 'pill-warn' : 'pill-ok') }}">
                                    {{ $medicine->stock }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-soft" type="button" onclick="addToCart({{ $medicine->id }}, '{{ addslashes($medicine->name) }}', {{ $medicine->sell_price }}, {{ $medicine->stock }})">Add</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <h2>Checkout</h2>
            <div class="stack">
                <div>
                    <label for="customer_id">Customer</label>
                    <select id="customer_id">
                        <option value="">Walk-in Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} | Due {{ number_format($customer->total_due, 2) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th class="numeric">Price</th>
                            <th class="numeric">Qty</th>
                            <th class="numeric">Amount</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="cartTable">
                        <tr>
                            <td colspan="5">Cart is empty.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="panel soft" style="padding: 14px;">
                        <label>Total</label>
                        <div id="totalDisplay" style="font-size: 26px; font-weight: 700;">0.00</div>
                    </div>
                    <div>
                        <label for="paid_amount">Paid Amount</label>
                        <input id="paid_amount" type="number" min="0" step="0.01" value="0">
                    </div>
                </div>

                <div class="panel soft" style="padding: 14px;">
                    <div class="split">
                        <strong>Due Amount</strong>
                        <strong id="dueDisplay">0.00</strong>
                    </div>
                </div>

                <button class="btn btn-primary btn-full" type="button" onclick="checkout()">Complete Sale</button>
                <div id="checkoutMessage" class="meta"></div>
            </div>
        </section>
    </div>

    <script>
        const cart = [];

        function currency(value) {
            return Number(value).toFixed(2);
        }

        function addToCart(medicineId, name, price, stock) {
            const existing = cart.find(item => item.medicine_id === medicineId);

            if (existing) {
                if (existing.quantity >= existing.stock) {
                    alert('Not enough stock for this medicine.');
                    return;
                }

                existing.quantity += 1;
            } else {
                if (stock <= 0) {
                    alert('This medicine is out of stock.');
                    return;
                }

                cart.push({
                    medicine_id: medicineId,
                    name,
                    price: Number(price),
                    quantity: 1,
                    stock: Number(stock)
                });
            }

            renderCart();
        }

        function changeQty(medicineId, nextQty) {
            const item = cart.find(entry => entry.medicine_id === medicineId);

            if (!item) {
                return;
            }

            if (nextQty < 1) {
                removeItem(medicineId);
                return;
            }

            if (nextQty > item.stock) {
                alert('Not enough stock for this quantity.');
                return;
            }

            item.quantity = nextQty;
            renderCart();
        }

        function removeItem(medicineId) {
            const index = cart.findIndex(item => item.medicine_id === medicineId);

            if (index >= 0) {
                cart.splice(index, 1);
            }

            renderCart();
        }

        function getTotal() {
            return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        }

        function renderCart() {
            const table = document.getElementById('cartTable');
            const total = getTotal();
            const paid = Number(document.getElementById('paid_amount').value || 0);
            const due = Math.max(total - paid, 0);

            if (cart.length === 0) {
                table.innerHTML = '<tr><td colspan="5">Cart is empty.</td></tr>';
            } else {
                table.innerHTML = cart.map(item => `
                    <tr>
                        <td>${item.name}</td>
                        <td class="numeric">${currency(item.price)}</td>
                        <td class="numeric">
                            <div class="actions" style="justify-content: flex-end;">
                                <button class="btn btn-soft" type="button" onclick="changeQty(${item.medicine_id}, ${item.quantity - 1})">-</button>
                                <input type="number" min="1" max="${item.stock}" value="${item.quantity}" onchange="changeQty(${item.medicine_id}, Number(this.value))" style="width: 72px; text-align: right;">
                                <button class="btn btn-soft" type="button" onclick="changeQty(${item.medicine_id}, ${item.quantity + 1})">+</button>
                            </div>
                        </td>
                        <td class="numeric">${currency(item.price * item.quantity)}</td>
                        <td><button class="btn btn-danger" type="button" onclick="removeItem(${item.medicine_id})">Remove</button></td>
                    </tr>
                `).join('');
            }

            document.getElementById('totalDisplay').textContent = currency(total);
            document.getElementById('dueDisplay').textContent = currency(due);
        }

        async function checkout() {
            const total = getTotal();
            const paid = Number(document.getElementById('paid_amount').value || 0);
            const customerId = document.getElementById('customer_id').value;
            const message = document.getElementById('checkoutMessage');

            if (cart.length === 0) {
                alert('Cart is empty.');
                return;
            }

            if (paid > total) {
                alert('Paid amount cannot be greater than total.');
                return;
            }

            message.textContent = 'Saving sale...';

            try {
                const response = await fetch('{{ route('sales.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        customer_id: customerId || null,
                        items: cart.map(item => ({
                            medicine_id: item.medicine_id,
                            quantity: item.quantity
                        })),
                        paid: paid
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    if (data.errors) {
                        const flatErrors = Object.values(data.errors).flat();
                        throw new Error(flatErrors.join(' '));
                    }

                    throw new Error(data.message || 'Sale could not be completed.');
                }

                message.textContent = 'Sale completed. Opening invoice...';
                cart.splice(0, cart.length);
                document.getElementById('paid_amount').value = '0';
                document.getElementById('customer_id').value = '';
                renderCart();
                window.location.href = data.redirect_url;
            } catch (error) {
                message.textContent = '';
                alert(error.message);
            }
        }

        document.getElementById('paid_amount').addEventListener('input', renderCart);
        document.getElementById('searchInput').addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('#medicineTable tr').forEach(row => {
                row.style.display = row.dataset.search.includes(query) ? '' : 'none';
            });
        });

        renderCart();
    </script>
@endsection
