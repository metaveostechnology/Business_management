@extends('layouts.stitch_employee')

@section('title', 'Executive Financial Health Dashboard')
@section('header_icon', 'query_stats')
@section('portal_name', 'FinExec Suite')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Financial Health Overview</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Quarterly Performance & Strategic Operations</p>
    </div>
    <div class="flex gap-3">
        <button class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm">
            <span class="material-symbols-outlined text-lg text-primary">calendar_today</span>
            Oct 1, 2023 - Dec 31, 2023
        </button>
        <button class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 transition shadow-md shadow-primary/20">
            <span class="material-symbols-outlined text-lg">download</span>
            Export Report
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-2">
            <p class="text-slate-500 dark:text-slate-400 text-sm font-semibold">Total Revenue</p>
            <span class="material-symbols-outlined text-primary">trending_up</span>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white">$4.28M</p>
        <p class="text-emerald-600 text-sm font-bold mt-1 flex items-center gap-1">
            <span class="material-symbols-outlined text-xs">arrow_upward</span>
            +12.5% <span class="text-slate-400 font-normal">vs last quarter</span>
        </p>
    </div>
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-2">
            <p class="text-slate-500 dark:text-slate-400 text-sm font-semibold">Net Profit</p>
            <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white">$1.12M</p>
        <p class="text-rose-600 text-sm font-bold mt-1 flex items-center gap-1">
            <span class="material-symbols-outlined text-xs">arrow_downward</span>
            -2.3% <span class="text-slate-400 font-normal">vs last quarter</span>
        </p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <div class="xl:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-lg">Cashflow Status</h3>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="size-2 rounded-full bg-primary animate-pulse"></span>
                    <span class="text-xs text-slate-500 font-bold">Inflow</span>
                </div>
                <select class="bg-slate-50 dark:bg-slate-800 border-none text-xs font-bold rounded-lg py-1 px-3 focus:ring-1 focus:ring-primary cursor-pointer">
                    <option>Last 6 Months</option>
                    <option>Last Year</option>
                </select>
            </div>
        </div>
        <div class="h-64 w-full relative">
            <svg class="w-full h-full" preserveaspectratio="none" viewbox="0 0 800 240">
                <defs>
                    <lineargradient id="gradient" x1="0" x2="0" y1="0" y2="1">
                        <stop offset="0%" stop-color="#1152d4" stop-opacity="0.3"></stop>
                        <stop offset="100%" stop-color="#1152d4" stop-opacity="0"></stop>
                    </lineargradient>
                </defs>
                <path d="M0,180 C50,160 100,200 150,150 C200,100 250,120 300,80 C350,40 400,90 450,70 C500,50 550,100 600,60 C650,20 700,50 800,30 L800,240 L0,240 Z" fill="url(#gradient)"></path>
                <path d="M0,180 C50,160 100,200 150,150 C200,100 250,120 300,80 C350,40 400,90 450,70 C500,50 550,100 600,60 C650,20 700,50 800,30" fill="none" stroke="#1152d4" stroke-width="3"></path>
            </svg>
            <div class="flex justify-between mt-4 px-2 text-[10px] text-slate-400 uppercase font-bold tracking-wider">
                <span>Jul</span><span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <h3 class="font-bold text-lg mb-6">Revenue vs Expense</h3>
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500 font-medium">Gross Revenue</span>
                    <span class="font-bold text-slate-900 dark:text-white">$1.2M</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden">
                    <div class="bg-primary h-full rounded-full transition-all duration-1000" style="width: 85%"></div>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500 font-medium">Total Expenses</span>
                    <span class="font-bold text-slate-900 dark:text-white">$840K</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden">
                    <div class="bg-rose-500 h-full rounded-full transition-all duration-1000" style="width: 60%"></div>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <p class="text-sm font-bold mb-4">Balance Sheet Snapshot</p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-lg border border-slate-100 dark:border-slate-800">
                        <p class="text-[10px] text-slate-500 uppercase font-black">Total Assets</p>
                        <p class="text-lg font-black text-slate-900 dark:text-white">$12.4M</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
