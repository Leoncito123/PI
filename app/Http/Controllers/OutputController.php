<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Output;
use App\Models\Device;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;



class OutputController extends Controller
{
  public function index()
  {
    if (auth()->user()->hasRole('supervisor')) {
      $ubication = Auth::user()->ubications->pluck('id');
      $device = Device::whereIn('ubication_id', $ubication)->pluck('id');

      if ($device->isEmpty()) {
        // Si no hay dispositivos, devolver una vista vacía o con un mensaje
        return view('admin.outputs', ['outputs' => collect()]);
      }

      $outputs = Output::with('devices', 'types')->whereIn('dev_id', $device)->get();
      return view('admin.outputs', compact('outputs'));
    } else {
      $outputs = Output::with('devices', 'types')->get();
      return view('admin.outputs', compact('outputs'));
    }
  }

  public function createView()
  {
    $types = Type::all();
    $devices = Device::all();
    return view('admin.creates.output', compact('types', 'devices'));
  }

  public function create(Request $request)
  {
    $request->validate([
      'name' => 'required',
      'dev_id' => 'required',
      'status' => 'required',
      'type_id' => 'required'
    ]);

    Output::create([
      'name' => $request->name,
      'dev_id' => $request->dev_id,
      'status' => $request->status,
      'type_id' => $request->type_id
    ]);

    return redirect()->route('admin.outputs')->with('success', 'Su registro ha sido exitoso');
  }

  public function editView($id)
  {
    $output = Output::find($id);
    $types = Type::all();
    $devices = Device::all();

    return view('admin.edit.output', compact('types', 'devices', 'output'));
  }

  public function edit(Request $request, $id)
  {

    $request->validate([
      'name' => 'required',
      'dev_id' => 'required',
      'status' => 'required',
      'type_id' => 'required'
    ]);
    $output = Output::find($id);
    $output->update([
      'name' => $request->name,
      'dev_id' => $request->dev_id,
      'status' => $request->status,
      'type_id' => $request->type_id
    ]);
    return redirect()->route('admin.outputs')->with('success', 'Ha sido exitoso sus cambios');
  }

  function destroy($id)
  {
    Output::destroy($id);
    return redirect()->route('admin.outputs')->with('succes', 'Se ha eliminado exitoso su registro');
  }
}
