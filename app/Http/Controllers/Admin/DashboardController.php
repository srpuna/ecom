<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Inquiry; // Assuming Model exists, controller references it.
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        // Check if Inquiry model exists or count directly from table if needed.
        // Based on AdminInquiryController usage, App\Models\Inquiry should exist.
        $totalInquiries = \App\Models\Inquiry::count(); 
        
        return view('admin.dashboard', compact('totalProducts', 'totalCategories', 'totalInquiries'));
    }

    public function toggleMaintenance(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Incorrect password.');
        }

        $current = Cache::get('maintenance_mode', false);
        if ($current) {
            Cache::forget('maintenance_mode');
            $message = 'Maintenance mode disabled. Site is live.';
        } else {
            Cache::forever('maintenance_mode', true);
            $message = 'Maintenance mode enabled. Site is hidden from public.';
        }

        return back()->with('success', $message);
    }
}
