<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\League;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    /**
     * แสดงรายการลีกทั้งหมด
     */
    public function index(Request $request)
    {
        $query = League::query();

        // Search by name or country
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('name_en', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('name_th', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('country', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->where('country', 'LIKE', "%{$request->country}%");
        }

        $leagues = $query->orderBy('name')->paginate(15)->appends($request->query());

        return view('admin.leagues.index', compact('leagues'));
    }

    /**
     * อัปเดตข้อมูลลีก
     */
    public function update(Request $request, League $league)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_th' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($league->logo && file_exists(public_path('storage/' . $league->logo))) {
                unlink(public_path('storage/' . $league->logo));
            }

            $logoPath = $request->file('logo')->store('uploads/leagues', 'public');
            $validatedData['logo'] = $logoPath;
        }

        $league->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'อัปเดตข้อมูลลีกเรียบร้อยแล้ว',
            'league' => $league->fresh()
        ]);
    }
}
