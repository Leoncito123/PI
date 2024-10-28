<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class TypeController extends Controller
{
  public function index()
  {
    if (Auth::user()->hasRole('admin')) {
      $types = Type::all();
    } elseif (Auth::user()->hasRole('supervisor')) {
      $types = Auth::user()->types;
    } else {
      return redirect()->route('home')->with('error', 'No tienes permiso para ver tipos de sensores.');
    }
    return view('admin.types', compact('types'));
  }

  public function createView()
  {
    if (!Auth::user()->hasRole(['admin', 'supervisor'])) {
      return redirect()->route('admin.sensType')->with('error', 'No tienes permiso para crear tipos de sensores.');
    }
    return view('admin.creates.type');
  }

  public function create(Request $request)
  {
    $request->validate([
      'name' => 'required',
      'identifier' => 'required',
      'unit' => 'required',
      'icon' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg',
      'min_value' => 'required',
      'max_value' => 'required',
      'segment' => 'required',
      'interval' => 'required'
    ]);
    $image = $request->file('icon') ? $request->file('icon')->store('types', 'public') : null;
    $type = Type::create([
      'name' => $request->name,
      'identifier' => $request->identifier,
      'unit' => $request->unit,
      'icon' => $image,
      'min_value' => $request->min_value,
      'max_value' => $request->max_value,
      'segment' => $request->segment,
      'interval' => $request->interval
    ]);

    // Asignar el tipo al usuario que lo creó
    Auth::user()->types()->attach($type->id);

    return redirect()->route('admin.sensType')->with('success', 'Tipo de sensor creado exitosamente.');
  }

  public function editView($id)
  {
    $type = Type::findOrFail($id);

    if (!Auth::user()->hasRole('admin') && !Auth::user()->types->contains($type)) {
      return redirect()->route('admin.sensType')->with('error', 'No tienes permiso para editar este tipo de sensor.');
    }

    return view('admin.edit.type', compact('type'));
  }

  public function edit(Request $request, $id)
  {
    $type = Type::findOrFail($id);

    if (!Auth::user()->hasRole('admin') && !Auth::user()->types->contains($type)) {
      return redirect()->route('admin.sensType')->with('error', 'No tienes permiso para editar este tipo de sensor.');
    }

    $request->validate([
      'name' => 'required',
      'identifier' => 'required',
      'unit' => 'required',
      'icon' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg',
      'min_value' => 'required',
      'max_value' => 'required',
      'segment' => 'required',
      'interval' => 'required'
    ]);

    $image = $request->file('icon') ? $request->file('icon')->store('types', 'public') : $type->icon;
    $type->update([
      'name' => $request->name,
      'identifier' => $request->identifier,
      'unit' => $request->unit,
      'icon' => $image,
      'min_value' => $request->min_value,
      'max_value' => $request->max_value,
      'segment' => $request->segment,
      'interval' => $request->interval
    ]);

    return redirect()->route('admin.sensType')->with('success', 'Tipo de sensor actualizado exitosamente.');
  }

  public function destroy($id)
  {
    $type = Type::findOrFail($id);

    if (!Auth::user()->hasRole('admin') && !Auth::user()->types->contains($type)) {
      return redirect()->route('admin.sensType')->with('error', 'No tienes permiso para eliminar este tipo de sensor.');
    }

    $type->delete();
    return redirect()->route('admin.sensType')->with('success', 'Tipo de sensor eliminado exitosamente.');
  }
}
