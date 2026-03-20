@extends('layouts.stitch_employee')

@section('title', 'Sales & Marketing Dashboard')
@section('header_icon', 'rocket_launch')
@section('portal_name', 'Nexus CRM')

@section('content')
<section class="flex flex-wrap justify-between items-end gap-4 mb-8">
    <div class="flex flex-col gap-1">
        <h1 class="text-slate-900 dark:text-white text-3xl font-black leading-tight tracking-tight">Executive Overview</h1>
        <p class="text-slate-500 dark:text-slate-400 text-base font-normal">Welcome back. Here's your performance for the current quarter.</p>
    </div>
    <div class="flex gap-3">
        <button class="flex items-center justify-center rounded-lg h-10 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-sm font-bold gap-2 shadow-sm">
            <span class="material-symbols-outlined text-lg">calendar_today</span>
            <span>Last 30 Days</span>
        </button>
        <button class="flex items-center justify-center rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold gap-2 shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-lg">download</span>
            <span>Export Report</span>
        </button>
    </div>
</section>

<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <div class="p-2 bg-primary/10 rounded-lg text-primary">
                <span class="material-symbols-outlined">payments</span>
            </div>
            <span class="text-emerald-500 text-xs font-bold bg-emerald-50 px-2 py-1 rounded">+12.5%</span>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Revenue Progress</p>
        <h3 class="text-slate-900 dark:text-white text-2xl font-bold mt-1">$842,000</h3>
        <div class="mt-4">
            <div class="flex justify-between text-xs mb-1">
                <span class="text-slate-400 font-medium">Goal: $1M</span>
                <span class="text-primary font-bold">84%</span>
            </div>
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                <div class="bg-primary h-full transition-all duration-1000" style="width: 84%;"></div>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <div class="p-2 bg-indigo-500/10 rounded-lg text-indigo-500">
                <span class="material-symbols-outlined">conversion_path</span>
            </div>
            <span class="text-emerald-500 text-xs font-bold bg-emerald-50 px-2 py-1 rounded">+3.2%</span>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Lead Conv. Rate</p>
        <h3 class="text-slate-900 dark:text-white text-2xl font-bold mt-1">24.8%</h3>
        <p class="text-slate-400 text-xs mt-2 italic">Avg. Industry: 18.2%</p>
    </div>
</section>

<section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold">Sales Funnel</h3>
            <button class="text-primary text-sm font-bold flex items-center gap-1">
                View Full Analysis <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </button>
        </div>
        <div class="space-y-4">
            <div class="relative">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-semibold">Leads (Top of Funnel)</span>
                    <span class="text-sm font-bold">12,400</span>
                </div>
                <div class="w-full h-10 bg-primary rounded-lg flex items-center justify-center text-white text-xs font-bold">100%</div>
            </div>
            <div class="relative pl-8">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-semibold">SQLs</span>
                    <span class="text-sm font-bold">3,500</span>
                </div>
                <div class="w-full h-10 bg-primary/70 rounded-lg flex items-center justify-center text-white text-xs font-bold">28%</div>
            </div>
            <div class="relative pl-16">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-semibold">Closed Won</span>
                    <span class="text-sm font-bold">412</span>
                </div>
                <div class="w-full h-10 bg-emerald-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">3.3%</div>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
        <h3 class="text-lg font-bold mb-6">Deal Pipeline</h3>
        <div class="space-y-4">
            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition shadow-sm cursor-pointer group">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-primary bg-primary/10 px-2 py-0.5 rounded">Tech</span>
                    <span class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors">more_horiz</span>
                </div>
                <h5 class="text-sm font-bold">Global Logistics Inc.</h5>
                <p class="text-xs text-slate-500 mt-1">$45,000 • Discovery</p>
            </div>
            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition shadow-sm cursor-pointer group">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded">Finance</span>
                    <span class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors">more_horiz</span>
                </div>
                <h5 class="text-sm font-bold">Apex Banking Corp</h5>
                <p class="text-xs text-slate-500 mt-1">$120,000 • Proposal Sent</p>
            </div>
        </div>
    </div>
</section>
@endsection
