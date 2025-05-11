<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['reporter', 'reported'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['reporter', 'reported']);
        return view('admin.reports.show', compact('report'));
    }

    public function resolve(Report $report)
    {
        $report->update(['status' => 'resolved']);
        return back()->with('success', 'Report has been marked as resolved.');
    }
}
