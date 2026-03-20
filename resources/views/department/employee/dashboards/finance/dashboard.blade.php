@extends('layouts.stitch_employee')

@section('title', 'Finance & Accounts Dashboard')
@section('header_icon', 'account_balance_wallet')
@section('portal_name', 'Finance Hub')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-4">
            <p class="text-slate-500 text-sm font-bold uppercase tracking-wider">Total Revenue</p>
            <span class="material-symbols-outlined text-green-500">trending_up</span>
        </div>
        <p class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">$428,500</p>
        <p class="text-xs text-green-600 mt-2 font-bold bg-green-50 inline-block px-2 py-1 rounded">+12.5% vs Last Mo</p>
    </div>
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-4">
            <p class="text-slate-500 text-sm font-bold uppercase tracking-wider">Pending Invoices</p>
            <div class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></div>
        </div>
        <p class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">$82,410</p>
        <p class="text-xs text-slate-500 mt-2 font-medium">14 invoices awaiting approval</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-lg">General Ledger</h3>
            <div class="flex gap-2">
                <button class="px-4 py-2 text-xs font-bold bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-100 transition shadow-sm">Export CSV</button>
                <button class="px-4 py-2 text-xs font-bold bg-primary text-white rounded-lg hover:bg-primary/90 transition shadow-md shadow-primary/20">Add Entry</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Description</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="px-6 py-4 text-sm font-medium">Oct 12, 2023</td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">Cloud Services Q3</td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white">$4,200.00</td>
                        <td class="px-6 py-4 text-right">
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded">Posted</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="space-y-8">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="font-bold text-lg mb-6">P&L Quick Look</h3>
            <div class="flex items-end justify-between gap-1 h-32 mb-4">
                @for($i=40; $i<=95; $i+=10)
                    <div class="flex-1 bg-primary/20 hover:bg-primary transition-all rounded-t cursor-pointer" style="height: {{ $i }}%"></div>
                @endfor
            </div>
            <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <span>Jun</span><span>Jul</span><span>Aug</span><span>Sep</span><span class="text-primary">Oct</span>
            </div>
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 mt-6 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500 font-medium">Gross Margin</span>
                    <span class="font-black text-slate-900 dark:text-white">64.2%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500 font-medium">Op. Profit</span>
                    <span class="font-black text-slate-900 dark:text-white">$142,000</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
