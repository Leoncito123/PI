<?php

namespace App\Http\Controllers;

use App\Models\Summaries;
use App\Models\Type;
use App\Models\Ubication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;




class UbicationController extends Controller
{
    public function index()
    {   
        if(auth()->user()->hasRole('supervisor')){
            return redirect()->route('admin.infoUbication');
        }else{
            $ubications = Ubication::all();
        }
        return view('admin.ubications', compact('ubications'));
    }

    public function createView()
    {
        $supervisores = User::role('supervisor')->get();
        
        return view('admin.creates.ubication', compact('supervisores'));
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
            'latitude' => 'required',
            'supervisor_id' => 'required|exists:users,id'
        ]);
    
        $ubication = Ubication::create([
            'name' => $request->name,
            'sector' => $request->sector,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude
        ]);
    
        $supervisor = User::find($request->supervisor_id);
        $supervisor->ubications()->attach($ubication->id);
    
        return redirect()->route('admin.ubications');
    }

    public function editView($id)
    {
        $ubication = Ubication::find($id);
        $supervisores = User::role('supervisor')->get();
        $currentSupervisor = $ubication->users()->role('supervisor')->first();
        return view('admin.edit.ubication', compact('ubication', 'supervisores', 'currentSupervisor'));
    }

    public function edit(Request $request, $id)
    {
        $ubication = Ubication::find($id);
        if (auth()->user()->hasRole('supervisor') && !auth()->user()->ubications->contains($ubication)) {
            return redirect()->route('admin.ubications');
        }
    
        $request->validate([
            'name' => 'required',
            'sector' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'supervisor_id' => 'required|exists:users,id'
        ]);
    
        $ubication->update([
            'name' => $request->name,
            'sector' => $request->sector,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude
        ]);
    
        // Actualizar el supervisor
        $ubication->users()->detach(); // Elimina todas las asociaciones actuales
        $supervisor = User::find($request->supervisor_id);
        $supervisor->ubications()->attach($ubication->id);
    
        return redirect()->route('admin.ubications');
    }
    
    public function destroy($id)
    {
        Ubication::destroy($id);
        return redirect()->route('admin.ubications');
    }

    public function infoUbication()
    {
        $user = auth()->user();
        $isAdmin = !$user->hasRole('supervisor');
    
        if ($isAdmin) {
            $ubications = Ubication::all();
        } else {
            $ubications = $user->ubications;
        }
    
        $chartData = [];
        $allSensorTypes = collect();
    
        foreach ($ubications as $ubication) {
            $sensors = Summaries::where('ubication_id', $ubication->id)
                ->with('type')
                ->get();
    
            $sensorCounts = $sensors->groupBy('type.name')->map->count();
            
            $chartData[] = [
                'name' => $ubication->name,
                'data' => $sensorCounts->values()->toArray(),
            ];
    
            $allSensorTypes = $allSensorTypes->merge($sensors->pluck('type.name')->unique());
        }
    
        $sensorTypes = $allSensorTypes->unique()->values()->toArray();
    
        // Asegurarse de que cada ubicación tenga un valor para cada tipo de sensor
        foreach ($chartData as &$locationData) {
            $data = array_fill_keys($sensorTypes, 0);
            foreach ($locationData['data'] as $index => $count) {
                $data[$sensorTypes[$index]] = $count;
            }
            $locationData['data'] = array_values($data);
        }
    
        return view('admin.infoUbication', compact('ubications', 'chartData', 'sensorTypes'));
    }
}
