<?php

namespace App\Http\Controllers;

use App\Models\Ubication;
use Illuminate\Http\Request;


class UbicationController extends Controller
{
    public function index()
    {
        $ubications = Ubication::all();
        return view('admin.ubications', compact('ubications'));
    }

    public function createUbicationView()
    {
        return view('admin.creates.ubication');
    }

    public function create(Request $request)
    {
        if (auth()->user()->hasRole('supervisor')) {
            return redirect()->route('admin.ubications');
        }

        $request->validate([
            'name' => 'required',
            'sector' => 'required',
            'longitude' => 'required',
            'latitude' => 'required'
        ]);
        Ubication::create([
            'name' => $request->name,
            'sector' => $request->sector,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude
        ]);
        return redirect()->route('admin.ubications');
    }

    public function editView($id)
    {

        $ubication = Ubication::find($id);
        return view('admin.edit.ubication', compact('ubication'));
    }

    public function edit(Request $request, $id)
    {
        $ubications = Ubication::find($id);
        if (auth()->user()->hasRole('supervisor') && auth()->user()->ubication_id != $ubications->id) {
            return redirect()->route('admin.ubications');
        }
        $request->validate([
            'name' => 'required',
            'sector' => 'required',
            'longitude' => 'required',
            'latitude' => 'required'
        ]);

        $ubications->update([
            'name' => $request->name,
            'sector' => $request->sector,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude
        ]);
        return redirect()->route('admin.ubications');
    }

    public function destroy($id)
    {
        Ubication::destroy($id);
        return redirect()->route('admin.ubications');
    }
}
