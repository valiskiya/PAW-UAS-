@extends('layouts.app')

@section('title', 'POS / Kasir')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">POS / Kasir</h1>
        <span class="text-sm text-gray-500">
            Kasir: <strong>{{ auth()->user()->full_name }}</strong>
        </span>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded mb-4">
            <ul class="list-disc list-inside text-red-700 text-sm">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($errors->has('pos'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded mb-4">
            <p class="text-red-700 text-sm">{{ $errors->first('pos') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('kasir.pos.store') }}" id="posForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kolom kiri: Pelanggan & Pembayaran --}}
            <div class="space-y-4">
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-lg font-semibold mb-3">Data Pelanggan</h2>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Pilih Pelanggan
                    </label>
                    <select name="customer_id" id="customerSelect"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Umum (Non Member)</option>
                        @foreach($customers as $cust)
                            <option value="{{ $cust->id }}"
                                data-type="{{ $cust->type }}"
                                data-discount="{{ $cust->discount_percentage }}"
                                data-free-shipping="{{ $cust->free_shipping ? 1 : 0 }}">
                                {{ $cust->code }} - {{ $cust->name }}
                                @if($cust->type === 'member')
                                    (Member)
                                @elseif($cust->type === 'wholesale_low')
                                    (Grosir 5%)
                                @elseif($cust->type === 'wholesale_high')
                                    (Grosir 10% + Free Ongkir)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Pilih <strong>Umum</strong> jika pelanggan tidak terdaftar / belanja biasa.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-lg font-semibold mb-3">Pembayaran</h2>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select name="payment_method" id="paymentMethod"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="cash">Tunai</option>
                            <option value="card">Kartu Debit/Kredit</option>
                            <option value="transfer">Transfer / E-Wallet</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                        <input type="number" step="0.01" min="0" name="payment_amount" id="paymentAmount"
                            value="{{ old('payment_amount') }}"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0">
                    </div>

                    <div class="mt-3 border-t pt-3 text-sm">
                        <div class="flex justify-between mb-1">
                            <span>Estimasi Total:</span>
                            <span id="estimateTotal" class="font-semibold text-gray-800">Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Estimasi Kembalian:</span>
                            <span id="estimateChange" class="font-semibold text-green-700">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom tengah: Input Produk --}}
            <div class="space-y-4 lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-lg font-semibold mb-3">Tambah Item</h2>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Scan / Ketik Kode Barcode
                            </label>
                            <input type="text" id="barcodeInput"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Scan barcode produk di sini">
                            <p class="text-xs text-gray-500 mt-1">
                                Setelah scan tekan <strong>Enter</strong> untuk menambahkan ke keranjang.
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Atau Pilih Produk
                            </label>
                            <select id="productSelect"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-code="{{ $p->code }}">
                                        [{{ $p->code }}] {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                            <input type="number" id="quantityInput" min="1" value="1"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select id="unitInput"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="small">Satuan (kecil)</option>
                                <option value="large">Kemasan (besar)</option>
                            </select>
                        </div>

                        <div class="md:col-span-4 flex justify-end">
                            <button type="button" id="addToCartBtn"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                                <i class="fas fa-cart-plus mr-2"></i>Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Keranjang --}}
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-lg font-semibold mb-3">Keranjang Belanja</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" id="cartTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left">Produk</th>
                                    <th class="px-3 py-2 text-center">Qty</th>
                                    <th class="px-3 py-2 text-center">Unit</th>
                                    <th class="px-3 py-2 text-right">Perkiraan Total</th>
                                    <th class="px-3 py-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="cartBody">
                                {{-- Diisi via JavaScript --}}
                            </tbody>
                        </table>
                    </div>

                    <p class="text-xs text-gray-500 mt-2">
                        Perhitungan akhir (subtotal, diskon, total) tetap mengikuti logika di server.
                    </p>
                </div>

                {{-- Ringkasan --}}
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span>Estimasi Subtotal:</span>
                                <span id="summarySubtotal" class="font-semibold">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Estimasi Diskon:</span>
                                <span id="summaryDiscount" class="font-semibold text-red-600">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-lg border-t pt-1">
                                <span>Estimasi Total:</span>
                                <span id="summaryTotal" class="font-bold text-green-700">Rp 0</span>
                            </div>
                        </div>

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                            <i class="fas fa-check mr-2"></i> Proses & Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Data product dari server untuk bantu hitung estimasi
    const products = @json($products);

    const productMap = {};
    products.forEach(p => { productMap[p.id] = p; });

    let cartIndex = 0;
    const cartBody = document.getElementById('cartBody');

    function formatRupiah(number) {
        number = Number(number) || 0;
        return 'Rp ' + number.toLocaleString('id-ID', {maximumFractionDigits: 0});
    }

    function getSelectedCustomerType() {
        const sel = document.getElementById('customerSelect');
        const opt = sel.options[sel.selectedIndex];
        return opt.getAttribute('data-type') || 'non_member';
    }

    function getDiscountPercentByType(type) {
        if (type === 'wholesale_low') return 5;
        if (type === 'wholesale_high') return 10;
        return 0;
    }

    function getPriceByCustomerType(product, type) {
        const retail   = Number(product.selling_price_retail || 0);
        const member   = Number(product.selling_price_member || retail);
        const low      = Number(product.selling_price_wholesale_low || member);
        const high     = Number(product.selling_price_wholesale_high || low);

        if (type === 'member')         return member;
        if (type === 'wholesale_low')  return low;
        if (type === 'wholesale_high') return high;
        return retail;
    }

    function recalcSummary() {
        let subtotal = 0;

        Array.from(cartBody.querySelectorAll('tr')).forEach(row => {
            const productId   = row.getAttribute('data-product-id');
            const unit        = row.getAttribute('data-unit');
            const qty         = Number(row.getAttribute('data-qty'));
            const product     = productMap[productId];
            const type        = getSelectedCustomerType();

            if (!product) return;

            const price = getPriceByCustomerType(product, type);
            const conv  = Number(product.conversion_factor || 1);
            const qtySmall = unit === 'large'
                ? qty * conv
                : qty;

            const rowSubtotal = price * qtySmall;
            subtotal += rowSubtotal;

            const estimateCell = row.querySelector('.estimate-cell');
            if (estimateCell) {
                estimateCell.textContent = formatRupiah(rowSubtotal);
            }
        });

        const customerType   = getSelectedCustomerType();
        const discountPercent= getDiscountPercentByType(customerType);
        const discountAmount = subtotal * discountPercent / 100;
        const total          = subtotal - discountAmount;

        document.getElementById('summarySubtotal').textContent = formatRupiah(subtotal);
        document.getElementById('summaryDiscount').textContent = formatRupiah(discountAmount);
        document.getElementById('summaryTotal').textContent    = formatRupiah(total);
        document.getElementById('estimateTotal').textContent   = formatRupiah(total);

        const pay    = Number(document.getElementById('paymentAmount').value || 0);
        const change = pay - total;
        document.getElementById('estimateChange').textContent  = formatRupiah(change > 0 ? change : 0);
    }

    function addToCart(productId, qty, unit) {
        const product = productMap[productId];
        if (!product) {
            alert('Produk tidak ditemukan');
            return;
        }
        if (!qty || qty < 1) qty = 1;

        const row = document.createElement('tr');
        row.className = 'border-b hover:bg-gray-50';
        row.setAttribute('data-product-id', product.id);
        row.setAttribute('data-unit', unit);
        row.setAttribute('data-qty', qty);

        row.innerHTML = `
            <td class="px-3 py-2">
                <div class="font-semibold">${product.name}</div>
                <div class="text-xs text-gray-500">[${product.code}]</div>
                <input type="hidden" name="items[${cartIndex}][product_id]" value="${product.id}">
            </td>
            <td class="px-3 py-2 text-center">
                <input type="number" min="1" value="${qty}"
                    class="w-16 px-2 py-1 border rounded text-center text-sm qty-input">
                <input type="hidden" name="items[${cartIndex}][quantity]" value="${qty}" class="qty-hidden">
            </td>
            <td class="px-3 py-2 text-center">
                <select name="items[${cartIndex}][unit]"
                    class="px-2 py-1 border rounded text-sm unit-select">
                    <option value="small" ${unit === 'small' ? 'selected' : ''}>${product.small_unit}</option>
                    <option value="large" ${unit === 'large' ? 'selected' : ''}>${product.large_unit}</option>
                </select>
            </td>
            <td class="px-3 py-2 text-right text-sm estimate-cell">
                -
            </td>
            <td class="px-3 py-2 text-center">
                <button type="button" class="text-red-600 hover:text-red-800 remove-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        cartBody.appendChild(row);
        cartIndex++;

        row.querySelector('.qty-input').addEventListener('input', function () {
            const newQty = Number(this.value) || 1;
            row.setAttribute('data-qty', newQty);
            row.querySelector('.qty-hidden').value = newQty;
            recalcSummary();
        });

        row.querySelector('.unit-select').addEventListener('change', function () {
            const newUnit = this.value;
            row.setAttribute('data-unit', newUnit);
            recalcSummary();
        });

        row.querySelector('.remove-btn').addEventListener('click', function () {
            row.remove();
            recalcSummary();
        });

        recalcSummary();
    }

    document.getElementById('addToCartBtn').addEventListener('click', function () {
        const select    = document.getElementById('productSelect');
        const productId = select.value;
        const qty       = Number(document.getElementById('quantityInput').value || 1);
        const unit      = document.getElementById('unitInput').value;

        if (!productId) {
            alert('Silakan pilih produk terlebih dahulu');
            return;
        }

        addToCart(productId, qty, unit);
    });

    document.getElementById('barcodeInput').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const code = this.value.trim();
            if (!code) return;

            const product = products.find(p => p.code === code);
            if (!product) {
                alert('Produk dengan kode tersebut tidak ditemukan');
                return;
            }

            const qty  = Number(document.getElementById('quantityInput').value || 1);
            const unit = document.getElementById('unitInput').value;
            addToCart(product.id, qty, unit);
            this.value = '';
        }
    });

    document.getElementById('customerSelect').addEventListener('change', recalcSummary);
    document.getElementById('paymentAmount').addEventListener('input', recalcSummary);

    document.getElementById('posForm').addEventListener('submit', function (e) {
        if (cartBody.children.length === 0) {
            e.preventDefault();
            alert('Keranjang masih kosong. Tambahkan minimal 1 produk.');
        }
    });
</script>
@endpush
