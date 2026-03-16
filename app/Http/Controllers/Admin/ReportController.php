<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['user', 'post.user'])
            ->latest()
            ->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    public function update(Request $request, Report $report)
    {
        $request->validate([
            'status' => ['required', 'in:reviewed,dismissed'],
        ]);

        $report->update(['status' => $request->status]);

        return back()->with('status', 'Signalement mis à jour.');
    }
}