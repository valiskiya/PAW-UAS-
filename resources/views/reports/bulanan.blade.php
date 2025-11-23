@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Laporan Bulanan - {{ $year }}</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Pendapatan</th>
                <th>Transaksi</th>
                <th>Diskon</th>
            </tr>
        </thead>
        <tbody>
            @foreach($months as $m)
                <tr>
                    <td>{{ DateTime::createFromFormat('!m', $m->month)->format('F') }}</td>
                    <td>{{ number_format($m->revenue, 2) }}</td>
                    <td>{{ $m->transactions_count }}</td>
                    <td>{{ number_format($m->total_discount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th>{{ number_format($totalRevenue, 2) }}</th>
                <th>{{ $totalTransactions }}</th>
                <th>{{ number_format($totalDiscount, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
