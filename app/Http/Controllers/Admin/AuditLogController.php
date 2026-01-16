<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Inertia\Inertia;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer', 'subject')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('properties', 'like', "%{$search}%")
                    ->orWhereHas('causer', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->tab === 'users') {
            $query->where(function ($q) {
                $q->where('subject_type', 'like', '%User%')
                    ->orWhere('subject_type', 'like', '%Customer%');
            });
        } elseif ($request->tab === 'quotes') {
            $query->where(function ($q) {
                $q->where('subject_type', 'like', '%Quote%')
                    ->orWhere('subject_type', 'like', '%QuoteOption%');
            });
        }

        $activities = $query->paginate(20)->withQueryString();

        return Inertia::render('Admin/Audit/Index', [
            'activities' => $activities,
            'filters' => $request->only(['search', 'tab'])
        ]);
    }

    public function show($id)
    {
        $activity = Activity::with('causer', 'subject')->findOrFail($id);

        return Inertia::render('Admin/Audit/Show', [
            'activity' => $activity
        ]);
    }
}
