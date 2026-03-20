@extends('layouts.stitch_employee')

@section('title', 'HR Management Dashboard')
@section('header_icon', 'group')
@section('portal_name', 'Nexus HR')

@section('content')
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <div class="p-2 bg-primary/10 text-primary rounded-lg">
                <span class="material-symbols-outlined">group</span>
            </div>
            <span class="text-green-600 text-xs font-bold bg-green-50 px-2 py-1 rounded">+2.5%</span>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Employees</p>
        <p class="text-slate-900 dark:text-white text-2xl font-bold mt-1">1,248</p>
    </div>
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <div class="p-2 bg-orange-100 text-orange-600 rounded-lg">
                <span class="material-symbols-outlined">how_to_reg</span>
            </div>
            <span class="text-red-600 text-xs font-bold bg-red-50 px-2 py-1 rounded">-1.2%</span>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Present Today</p>
        <p class="text-slate-900 dark:text-white text-2xl font-bold mt-1">1,102</p>
    </div>
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <div class="p-2 bg-purple-100 text-purple-600 rounded-lg">
                <span class="material-symbols-outlined">event_busy</span>
            </div>
            <span class="text-green-600 text-xs font-bold bg-green-50 px-2 py-1 rounded">+5%</span>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Pending Leaves</p>
        <p class="text-slate-900 dark:text-white text-2xl font-bold mt-1">14</p>
    </div>
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                <span class="material-symbols-outlined">person_search</span>
            </div>
            <span class="text-slate-400 text-xs font-bold bg-slate-50 px-2 py-1 rounded">Stable</span>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Open Roles</p>
        <p class="text-slate-900 dark:text-white text-2xl font-bold mt-1">8</p>
    </div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                <h3 class="text-lg font-bold">Employee Directory</h3>
                <button class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary/90 transition shadow-sm">Add Employee</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Employee</th>
                            <th class="px-6 py-4">Department</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Performance</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-full bg-cover" style="background-image: url('https://ui-avatars.com/api/?name=Alex+Johnson&background=random');"></div>
                                    <div>
                                        <p class="font-semibold text-sm">Alex Johnson</p>
                                        <p class="text-xs text-slate-500">UX Designer</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">Design</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded">Active</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="w-full bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-primary h-full" style="width: 85%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="material-symbols-outlined text-slate-400 cursor-pointer hover:text-primary transition-colors">more_vert</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h3 class="text-lg font-bold mb-6">Recruitment Tracking Funnel</h3>
            <div class="space-y-6">
                <div class="relative">
                    <div class="flex justify-between mb-2 text-sm">
                        <span class="font-medium text-slate-700 dark:text-slate-300">Total Applicants</span>
                        <span class="text-slate-500 font-bold">452</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-8 rounded-lg overflow-hidden flex">
                        <div class="bg-primary w-full h-full flex items-center px-4"></div>
                    </div>
                </div>
                <div class="relative">
                    <div class="flex justify-between mb-2 text-sm">
                        <span class="font-medium text-slate-700 dark:text-slate-300">Screening / Interview</span>
                        <span class="text-slate-500 font-bold">128</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-8 rounded-lg overflow-hidden flex">
                        <div class="bg-primary/70 w-[60%] h-full flex items-center px-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="space-y-8">
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold">Attendance</h3>
                <div class="text-primary text-sm font-bold cursor-pointer hover:underline">{{ date('M Y') }}</div>
            </div>
            <div class="grid grid-cols-7 gap-2 text-center text-xs font-bold text-slate-400 mb-2 uppercase tracking-tighter">
                <div>S</div><div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div>
            </div>
            <div class="grid grid-cols-7 gap-2">
                @for($i=1; $i<=28; $i++)
                    <div class="h-8 flex items-center justify-center rounded-lg {{ $i == 6 ? 'bg-primary text-white font-bold' : 'bg-slate-50 dark:bg-slate-800 text-xs font-medium' }}">{{ $i }}</div>
                @endfor
            </div>
        </div>

        <div class="bg-primary/5 rounded-xl border border-primary/20 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-primary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">payments</span>
                Payroll Status
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-600 dark:text-slate-400 font-medium">Current Cycle</span>
                    <span class="font-bold">May 15 - May 31</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 h-2 rounded-full overflow-hidden">
                    <div class="bg-primary h-full transition-all duration-1000" style="width: 65%"></div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-500 font-medium">65% Processed</span>
                    <span class="px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-bold uppercase rounded">In Progress</span>
                </div>
                <button class="w-full bg-primary text-white py-2.5 rounded-lg text-sm font-bold shadow-lg shadow-primary/20 hover:opacity-90 transition transform active:scale-95">Finalize Payroll</button>
            </div>
        </div>
    </div>
</div>
@endsection
