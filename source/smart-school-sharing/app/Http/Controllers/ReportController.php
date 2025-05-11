<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    protected $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function show($id)
    {
        return response()->json($this->service->findById($id));
    }

    public function store(Request $request, $reportedUserId)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        // Check if the reported user exists
        $reportedUser = User::findOrFail($reportedUserId);

        // Prevent self-reporting
        if (Auth::id() == $reportedUserId) {
            Log::warning("User #".Auth::id()." attempted to report themselves");
            return back()->with('error', 'You cannot report yourself.');
        }

        // Check for duplicate reports
        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('reported_user_id', $reportedUserId)
            ->where('status', 'pending')
            ->first();

        if ($existingReport) {
            Log::warning("Duplicate report attempt by user #".Auth::id()." for user #".$reportedUserId);
            return back()->with('error', 'You have already submitted a report for this user.');
        }

        // Create report
        $report = Report::create([
            'reporter_id'      => Auth::id(),
            'reported_user_id' => $reportedUserId,
            'reason'           => $request->reason,
            'status'           => 'pending',
        ]);

        Log::info("Report #{$report->id} created by user #".Auth::id()." against user #{$reportedUserId}");

        // Send email notification
        try {
            Log::info("Attempting to send report notification email for report #{$report->id}");
            $emailService = app('email-service');
            $emailSent = $emailService->sendUserReportNotification($report);

            if ($emailSent) {
                Log::info("Report notification email successfully sent for report #{$report->id}");
            } else {
                Log::warning("Failed to send report notification email for report #{$report->id}");
            }
        } catch (\Exception $e) {
            Log::error("Report notification failed for report #{$report->id}: ".$e->getMessage(), [
                'exception' => $e,
                'report_id' => $report->id,
                'reporter_id' => Auth::id(),
                'reported_user_id' => $reportedUserId
            ]);
        }
        return back()->with('success', 'Your report has been submitted. Thank you.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        return response()->json($this->service->update($id, $data));
    }

    public function destroy($id)
    {
        return response()->json($this->service->delete($id));
    }
}
