<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::orderBy('group')->orderBy('key')->get();
        $groupedSettings = $settings->groupBy('group');
        
        // Get individual settings for easier access in view
        $siteName = SiteSetting::where('key', 'site_name')->first();
        $navbarLogo = SiteSetting::where('key', 'navbar_logo')->first();
        $footerLogo = SiteSetting::where('key', 'footer_logo')->first();
        $favicon = SiteSetting::where('key', 'favicon')->first();
        $footerQrCode = SiteSetting::where('key', 'footer_qr_code')->first();
        
        return view('admin.settings.index', compact(
            'groupedSettings', 
            'siteName', 
            'navbarLogo', 
            'footerLogo', 
            'favicon', 
            'footerQrCode'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'navbar_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg,gif,svg|max:1024',
            'footer_qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Update site name
        if ($request->has('site_name')) {
            SiteSetting::set('site_name', $request->site_name);
        }

        // Handle navbar logo upload
        if ($request->hasFile('navbar_logo')) {
            $this->handleLogoUpload($request->file('navbar_logo'), 'navbar_logo');
        }

        // Handle footer logo upload
        if ($request->hasFile('footer_logo')) {
            $this->handleLogoUpload($request->file('footer_logo'), 'footer_logo');
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $this->handleLogoUpload($request->file('favicon'), 'favicon');
        }

        // Handle QR code upload
        if ($request->hasFile('footer_qr_code')) {
            $this->handleLogoUpload($request->file('footer_qr_code'), 'footer_qr_code');
        }

        // Handle logo removal
        if ($request->has('remove_navbar_logo')) {
            $this->removeLogo('navbar_logo');
        }

        if ($request->has('remove_footer_logo')) {
            $this->removeLogo('footer_logo');
        }

        if ($request->has('remove_favicon')) {
            $this->removeLogo('favicon');
        }

        if ($request->has('remove_footer_qr_code')) {
            $this->removeLogo('footer_qr_code');
        }

        SiteSetting::clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    private function handleLogoUpload($file, $key)
    {
        // Delete old logo if exists
        $oldSetting = SiteSetting::where('key', $key)->first();
        if ($oldSetting && $oldSetting->value) {
            Storage::disk('public')->delete($oldSetting->value);
        }

        // Store new logo
        $path = $file->store('logos', 'public');
        SiteSetting::set($key, $path, 'image', 'logo');
    }

    private function removeLogo($key)
    {
        $setting = SiteSetting::where('key', $key)->first();
        if ($setting && $setting->value) {
            Storage::disk('public')->delete($setting->value);
            $setting->update(['value' => null]);
        }
    }
}
