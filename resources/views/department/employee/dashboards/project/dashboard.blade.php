@extends('layouts.stitch_employee')

@section('title', 'Project Management Dashboard')
@section('header_icon', 'dashboard_customize')
@section('portal_name', 'OpsManager')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm border-l-4 border-l-primary">
        <div class="flex items-center justify-between mb-4">
            <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">assignment</span>
            <span class="text-emerald-600 text-xs font-black px-2 py-1 bg-emerald-50 rounded-full">+12%</span>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-sm font-bold uppercase tracking-wider">Total Tasks</p>
        <p class="text-slate-900 dark:text-white text-3xl font-black mt-1">1,248</p>
    </div>
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm border-l-4 border-l-amber-500">
        <div class="flex items-center justify-between mb-4">
            <span class="material-symbols-outlined text-amber-500 bg-amber-500/10 p-2 rounded-lg">verified</span>
            <span class="text-rose-600 text-xs font-black px-2 py-1 bg-rose-50 rounded-full">-2%</span>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-sm font-bold uppercase tracking-wider">SLA Compliance</p>
        <p class="text-slate-900 dark:text-white text-3xl font-black mt-1">94.2%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 h-full">
    <div class="lg:col-span-2 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">view_kanban</span>
                Task Board
            </h2>
            <button class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg shadow-primary/20 hover:opacity-90 transition">
                New Task
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 h-full">
            <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800 min-h-[400px]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">To-Do</h3>
                    <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded text-[10px] font-bold">12</span>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-slate-100 dark:border-slate-800 hover:border-primary/50 transition cursor-pointer">
                    <span class="text-[10px] bg-rose-100 text-rose-600 px-2 py-0.5 rounded uppercase font-black">Urgent</span>
                    <p class="text-sm font-bold mt-2">Security Audit</p>
                    <p class="text-xs text-slate-500 mt-1">Quarterly firewall review</p>
                </div>
            </div>
            <div class="bg-primary/5 p-4 rounded-xl border border-primary/10 min-h-[400px]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary">Processing</h3>
                    <span class="bg-primary text-white px-2 py-0.5 rounded text-[10px] font-bold">4</span>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-md border border-primary/20 ring-1 ring-primary/10">
                    <span class="text-[10px] bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded uppercase font-black">Design</span>
                    <p class="text-sm font-bold mt-2">Client Portal UX</p>
                    <div class="mt-3 w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-primary h-full transition-all duration-1000" style="width: 65%"></div>
                    </div>
                </div>
            </div>
            <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 min-h-[400px]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-emerald-600">Done</h3>
                    <span class="bg-emerald-500 text-white px-2 py-0.5 rounded text-[10px] font-bold">42</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="space-y-8">
        <section class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-900 dark:text-white">Critical Alerts</h3>
            </div>
            <div class="p-4 space-y-3">
                <div class="p-3 bg-rose-50 rounded-lg border-l-4 border-rose-500">
                    <p class="text-[10px] font-black text-rose-600 uppercase">SLA Breach</p>
                    <p class="text-sm font-bold text-slate-900">Ticket #8841 Unassigned</p>
                    <p class="text-xs text-slate-500">Elapsed: 42 mins.</p>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
