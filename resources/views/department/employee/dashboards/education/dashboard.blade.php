@extends('layouts.stitch_employee')

@section('title', 'Education Management Dashboard')
@section('header_icon', 'school')
@section('portal_name', 'EduManager')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm border-t-4 border-t-primary transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">groups</span>
            <p class="text-xs font-bold text-slate-400">Total Students</p>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white">2,450</p>
    </div>
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm border-t-4 border-t-emerald-500 transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-emerald-500 bg-emerald-500/10 p-2 rounded-lg">fact_check</span>
            <p class="text-xs font-bold text-slate-400">Attendance Rate</p>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white">96.8%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-lg">Upcoming Schedule / Classes</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800 transition hover:border-primary/30 cursor-pointer">
                    <div class="text-center w-12 border-r border-slate-200 dark:border-slate-700 pr-4">
                        <p class="text-xs font-black text-primary">09:00</p>
                        <p class="text-[10px] text-slate-400 uppercase">AM</p>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Advanced Mathematics</p>
                        <p class="text-xs text-slate-500">Block A • Room 302</p>
                    </div>
                    <span class="px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-black uppercase rounded">Lecturing</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">assignment</span>
            Edu-Tasks
        </h3>
        <div class="space-y-4">
            <div class="flex items-start gap-3 p-3 rounded-lg bg-primary/5 border border-primary/10">
                <div class="mt-0.5 size-4 rounded-full border-2 border-primary"></div>
                <div>
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Finalize Curriculum Q4</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Due in 2 days</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
