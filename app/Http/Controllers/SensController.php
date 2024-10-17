<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Summaries;
use App\Models\Ubication;
use App\Models\Type;
use App\Models\Macaddress;
use Illuminate\Support\Facades\Auth;

class SensController extends Controller
{
    //Funciones para la administración de sensores
    public function index()
    {
        if (auth()->user()->hasRole('supervisor')) {
            $ubication = Auth::user()->ubications->pluck('id');
            $sens = Summaries::with(['ubications', 'type', 'macaddress'])->where('ubication_id', $ubication)->get();
            $groups = $sens->groupBy('macaddress.macaddress'); // Agrupar por la dirección MAC
            return view('admin.sens', compact('groups'));
        } else {
            $sens = Summaries::with(['ubications', 'type', 'macaddress'])->get();
            $groups = $sens->groupBy('macaddress.macaddress'); // Agrupar por la dirección MAC
            return view('admin.sens', compact('groups'));
        }
    }


    public function createView()
    {
        if (Auth::user()->hasRole('supervisor')) {
            $ubications = Auth::user()->ubications;
            $types = Auth::user()->types;
        } else {
            $ubications = Ubication::all();
            $types = Type::all();
        }

        return view('admin.creates.sens', compact('types', 'ubications'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'macaddress' => 'required',
            'sector' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'types' => 'required|array',
            'types.*' => 'required|exists:types,id',
            'ubication_id' => 'required'
        ]);

        $macAddress = Macaddress::create([
                'name' => $request->name,
                'macaddress' => $request->macaddress,
                'sector' => $request->sector,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude
            ]);
        foreach ($request->types as $typeId) {
            Summaries::create(
                [
                    'ubication_id' => $request->ubication_id,
                    'macaddress_id' => $macAddress->id,
                    'type_id' => $typeId
                ]
            );
        }

        return redirect()->route('admin.sens')->with('success', 'Sensor creado correctamente');
    }

    public function editView($id)
    {
        if (Auth::user()->hasRole('supervisor')) {
            $ubications = Auth::user()->ubications;
            $types = Auth::user()->types;
        } else {
            $ubications = Ubication::all();
            $types = Type::all();
        }
        $sens = Macaddress::find($id);
        $currentTypes = Summaries::where('macaddress_id', $sens->id)->pluck('type_id')->toArray();
        return view('admin.edit.sens', compact('sens', 'types', 'ubications', 'currentTypes'));
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'macaddress' => 'required',
            'sector' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'types' => 'required|array',
            'types.*' => 'required|exists:types,id',
            'ubication_id' => 'required'
        ]);

        $macAddress = Macaddress::find($id);
        $macAddress->update(
            [
                'name' => $request->name,
                'macaddress' => $request->macaddress,
                'sector' => $request->sector,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude
            ]
        );

        $currentTypes = Summaries::where('macaddress_id', $macAddress->id)->where('ubication_id', $request->ubication_id)->pluck('type_id')->toArray();
        $typesToDelete = array_diff($currentTypes, $request->types);

        Summaries::where('macaddress_id', $macAddress->id)
            ->where('ubication_id', $request->ubication_id)
            ->whereIn('type_id', $typesToDelete)
            ->delete();

        foreach ($request->types as $typeId) {
            Summaries::updateOrCreate(
                [
                    'ubication_id' => $request->ubication_id, // Clave para buscar
                    'macaddress_id' => $macAddress->id,
                    'type_id' => $typeId,
                ]
            );
        }

        return redirect()->route('admin.sens');
    }

    public function destroy($sens)
    {
        $sens = Macaddress::find($sens);
        $sens->delete();
        return redirect()->route('admin.sens');
    }

    public function destroySummary($id)
    {
        $summary = Summaries::find($id);
        if ($summary) {
            $summary->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'Tipo no encontrado'], 404);
    }
}
