<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Ubication;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    //Funciones para la administración de devices
    public function index()
    {
        if(auth()->user()->hasRole('supervisor')){
            $ubication = Auth::user()->ubications->pluck('id');
            $devices = Device::with('ubications')->where('ubication_id', $ubication)->get();
            return view('admin.devices', compact('devices'));
        }else{
            $devices = Device::with('ubications')->get();
            return view('admin.devices', compact('devices'));
        }

    }

    public function createView()
    {
        if(auth()->user()->hasRole('supervisor')){
            $ubications = Auth::user()->ubications;
        }else{
            $ubications = Ubication::all();
        }
        return view('admin.creates.device', compact('ubications'));
    }

    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'ubication_id' => 'required'
        ]);
        Device::create([
            'name' => $request->name,
            'ubication_id' => $request->ubication_id
        ]);
        return redirect()->route('admin.devices');
    }

    public function editView($id)
    {
        if(auth()->user()->hasRole('supervisor')){
            $ubication = Auth::user()->ubications->pluck('id');
            $ubications = Ubication::whereIn('id', $ubication)->get();
            $device = Device::with('ubications')->where('ubication_id', $ubication)->find($id);
        }else{
            $ubications = Ubication::all();
            $device = Device::with('ubications')->find($id);
        }
        return view('admin.edit.device', compact('device','ubications'));
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'ubication_id' => 'required'
        ]);
        $device = Device::find($id);
        $device->update([
            'name' => $request->name,
            'ubication_id' => $request->ubication_id
        ]);
        return redirect()->route('admin.devices');
    }

    public function destroy($id)
    {
        Device::destroy($id);
        return redirect()->route('admin.devices');
    }
}
