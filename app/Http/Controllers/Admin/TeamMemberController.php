<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamMemberController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/TeamMembers/Index', [
            'members' => TeamMember::orderBy('sort_order')->paginate(15),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/TeamMembers/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'facebook' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|max:2048',
        ]);
        $member = TeamMember::create(collect($validated)->except('photo')->toArray());
        if ($request->hasFile('photo')) {
            $member->addMediaFromRequest('photo')->toMediaCollection('photo');
        }
        return redirect()->route('admin.team-members.index')->with('success', 'Team member added.');
    }

    public function edit(TeamMember $teamMember)
    {
        return Inertia::render('Admin/TeamMembers/Edit', ['member' => $teamMember->load('media')]);
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'facebook' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|max:2048',
        ]);
        $teamMember->update(collect($validated)->except('photo')->toArray());
        if ($request->hasFile('photo')) {
            $teamMember->clearMediaCollection('photo');
            $teamMember->addMediaFromRequest('photo')->toMediaCollection('photo');
        }
        return redirect()->route('admin.team-members.index')->with('success', 'Team member updated.');
    }

    public function destroy(TeamMember $teamMember)
    {
        $teamMember->clearMediaCollection();
        $teamMember->delete();
        return redirect()->route('admin.team-members.index')->with('success', 'Team member deleted.');
    }
}
