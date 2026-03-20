@extends('layouts.stitch_employee')

@section('title', 'Hotel Management Dashboard')
@section('header_icon', 'hotel')
@section('portal_name', 'StayNexus')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm border-b-4 border-b-primary transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">bed</span>
            <p class="text-xs font-bold text-slate-400">Total Bookings</p>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white">128</p>
    </div>
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm border-b-4 border-b-emerald-500 transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-emerald-500 bg-emerald-500/10 p-2 rounded-lg">meeting_room</span>
            <p class="text-xs font-bold text-slate-400">Occupancy Rate</p>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white">84%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800">
            <h3 class="font-bold text-lg">Room Status Matrix</h3>
        </div>
        <div class="p-6 grid grid-cols-5 md:grid-cols-8 gap-3">
            @for($i=101; $i<=124; $i++)
                <div class="aspect-square flex flex-col items-center justify-center rounded-lg border {{ $i % 3 == 0 ? 'bg-rose-50 border-rose-100 text-rose-600' : 'bg-green-50 border-green-100 text-green-600' }} transition hover:scale-105 cursor-pointer">
                    <p class="text-xs font-black">{{ $i }}</p>
                    <span class="material-symbols-outlined text-xs">@if($i % 3 == 0) block @else check_circle @endif</span>
                </div>
            @endfor
        </div>
    </div>
    
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <h3 class="font-bold text-lg mb-6">Service Performance</h3>
        <div class="space-y-6">
            <div class="flex flex-col gap-2">
                <div class="flex justify-between text-xs font-bold uppercase text-slate-500">
                    <span>Housekeeping Efficiency</span>
                    <span class="text-primary font-black">92%</span>
                </div>
                <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="bg-primary h-full transition-all duration-1000" style="width: 92%"></div>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <div class="flex justify-between text-xs font-bold uppercase text-slate-500">
                    <span>Guest Satisfaction</span>
                    <span class="text-emerald-500 font-black">4.8/5.0</span>
                </div>
                <div class="flex gap-1 text-primary">
                    @for($i=0; $i<5; $i++) <span class="material-symbols-outlined text-sm">star</span> @endfor
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
