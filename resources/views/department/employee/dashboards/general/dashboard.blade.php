@extends('layouts.stitch_employee')

@section('title', 'Employee Dashboard')
@section('header_icon', 'dashboard')
@section('portal_name', 'Employee Portal')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
        <p class="text-slate-500 dark:text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Welcome Back</p>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">Nexus Portal</h1>
        <p class="text-xs text-primary font-bold mt-2">All systems operational</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-10 flex flex-col items-center justify-center text-center space-y-6">
        <div class="size-24 bg-primary/5 rounded-full flex items-center justify-center text-primary">
            <span class="material-symbols-outlined !text-5xl">rocket_launch</span>
        </div>
        <h2 class="text-2xl font-black">Your Dynamic Workspace</h2>
        <p class="text-slate-500 max-w-md mx-auto">Welcome to the Nexus Employee Portal. We're currently configuring your department-specific modules. Check back soon for full integration.</p>
        <div class="flex gap-4">
            <button class="bg-primary text-white px-6 py-3 rounded-lg font-bold shadow-lg shadow-primary/20 hover:scale-105 transition">Explore Features</button>
            <button class="border border-slate-200 px-6 py-3 rounded-lg font-bold hover:bg-slate-50 transition">Help Center</button>
        </div>
    </div>
    
    <div class="space-y-8">
        <div class="bg-slate-900 text-white p-6 rounded-xl shadow-xl relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="font-bold text-lg mb-4">Corporate Feed</h3>
                <div class="space-y-4">
                    <div class="border-l-2 border-primary pl-4">
                        <p class="text-sm font-bold">Quarterly Town-Hall</p>
                        <p class="text-xs text-slate-400 mt-1">Starting in 45 mins • Zoom</p>
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 size-32 bg-primary/20 rounded-full blur-3xl"></div>
        </div>
    </div>
</div>
@endsection
