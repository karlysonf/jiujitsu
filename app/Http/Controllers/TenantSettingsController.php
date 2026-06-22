<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TenantSettingsController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-settings');
        
        $tenant = Tenant::current();

        return view('settings.index', compact('tenant'));
    }

    public function update(Request $request)
    {
        Gate::authorize('manage-settings');

        $tenant = Tenant::current();

        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'primary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'asaas_api_key' => 'nullable|string',
            'asaas_environment' => 'required|in:sandbox,production',
        ]);

        $data = [
            'name' => $request->name,
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'asaas_environment' => $request->asaas_environment,
        ];

        if ($request->filled('asaas_api_key')) {
            $data['asaas_api_key'] = $request->asaas_api_key;
        }

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($tenant->logo) {
                Storage::disk('public')->delete($tenant->logo);
            }
            $data['logo'] = $request->file('logo')->store('tenants/logos', 'public');
        }

        $tenant->update($data);

        return back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
