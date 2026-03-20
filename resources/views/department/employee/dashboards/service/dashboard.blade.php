@extends('layouts.stitch_employee')

@section('title', 'Service Management Dashboard')
@section('header_icon', 'construction')
@section('portal_name', 'ServiceHub')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm border-r-4 border-r-primary transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">confirmation_number</span>
            <p class="text-xs font-bold text-slate-400">Open Tickets</p>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white">42</p>
    </div>
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm border-r-4 border-r-emerald-500 transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-emerald-500 bg-emerald-500/10 p-2 rounded-lg">done_all</span>
            <p class="text-xs font-bold text-slate-400">Resolved Index</p>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white">98.4%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <h3 class="font-bold text-lg">Active Service Requests</h3>
            <span class="px-2 py-1 bg-amber-50 text-amber-600 text-[10px] font-black uppercase rounded border border-amber-100">8 Critical</span>
        </div>
        <div class="p-0">
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                <div class="p-6 flex items-center gap-6 hover:bg-slate-50 transition cursor-pointer">
                    <div class="size-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-black text-slate-400">#45</div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-800">Maintenance Request - L3 HVAC</p>
                        <p class="text-xs text-slate-500">Reported by: Security Team • 2h ago</p>
                    </div>
                    <span class="material-symbols-outlined text-slate-300">chevron_right</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <h3 class="font-bold text-lg mb-6">Dispatch Status</h3>
        <div class="space-y-4">
            <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100">
                <div class="size-2 bg-emerald-500 rounded-full animate-ping"></div>
                <div>
                    <p class="text-xs font-bold text-slate-800">Field Unit C41</p>
                    <p class="text-[10px] text-slate-500">Status: En-route to Site B</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
