<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit() { return Inertia::render('Client/Profile/Edit', ['user'=>auth()->user()]); }

    public function update(Request $request)
    {
        $validated = $request->validate(['name'=>'required|string|max:255','phone'=>'nullable|string|max:20','company'=>'nullable|string|max:255','company_address'=>'nullable|string|max:500','avatar'=>'nullable|image|max:2048']);
        $user = auth()->user();
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $validated['avatar'] = $request->file('avatar')->store('avatars','public');
        }
        $user->update($validated);
        return back()->with('success','Profile updated.');
    }
}
