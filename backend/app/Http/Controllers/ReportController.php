<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use App\Models\Product;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $businessStats = [
            'total' => Business::count(),
            'approved' => Business::where('status', 'approved')->count(),
            'pending' => Business::where('status', 'pending')->count(),
            'rejected' => Business::where('status', 'rejected')->count(),
        ];

        $userStats = [
            'total' => User::count(),
            'customers' => User::whereHas('role', fn($q) => $q->where('name', 'customer'))->count(),
            'owners' => User::whereHas('role', fn($q) => $q->where('name', 'business_owner'))->count(),
        ];

        $recentInquiries = Inquiry::with('business')->latest()->take(10)->get();

        return view('admin.reports', compact('businessStats', 'userStats', 'recentInquiries'));
    }

    public function exportCsv(Request $request)
    {
        $type = $request->get('type', 'businesses');
        $fileName = "business_connect_{$type}_report_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($type) {
            $file = fopen('php://output', 'w');

            if ($type === 'businesses') {
                fputcsv($file, ['ID', 'Business Name', 'Owner', 'Email', 'Phone', 'City', 'Status', 'Created At']);
                $records = Business::all();
                foreach ($records as $row) {
                    fputcsv($file, [$row->id, $row->name, $row->owner_name, $row->email, $row->phone, $row->city, $row->status, $row->created_at]);
                }
            } elseif ($type === 'users') {
                fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 'Registered At']);
                $records = User::with('role')->get();
                foreach ($records as $row) {
                    fputcsv($file, [$row->id, $row->name, $row->email, $row->phone, $row->role->label ?? 'User', $row->status, $row->created_at]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
