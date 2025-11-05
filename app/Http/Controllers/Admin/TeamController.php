<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * แสดงรายการทีมทั้งหมด
     */
    public function index(Request $request)
    {
        $query = Team::query();

        // Search by team name or UUID
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_en', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('name_th', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('uuid', 'LIKE', "%{$searchTerm}%");
            });
        }

        $teams = $query->orderBy('name_en')->paginate(15)->appends($request->query());

        return view('admin.teams.index', compact('teams'));
    }

    /**
     * อัปเดตข้อมูลทีม
     */
    public function update(Request $request, Team $team)
    {
        $validatedData = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_th' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($team->logo && file_exists(public_path('storage/' . $team->logo))) {
                unlink(public_path('storage/' . $team->logo));
            }

            $logoPath = $request->file('logo')->store('uploads/teams', 'public');
            $validatedData['logo'] = $logoPath;
        }

        $team->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'อัปเดตข้อมูลทีมเรียบร้อยแล้ว',
            'team' => $team->fresh()
        ]);
    }
}
