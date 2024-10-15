<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ubication;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
  public function index()
  {
    if (auth()->user()->hasRole('admin')) {
      $users = User::with('ubications')->get();
    } elseif (auth()->user()->hasRole('supervisor')) {
      $userUbications = auth()->user()->ubications->pluck('id');
      $users = User::whereHas('ubications', function ($query) use ($userUbications) {
        $query->whereIn('ubications.id', $userUbications);
      })->with('ubications')->get();
    } else {
      return redirect()->route('home')->with('error', 'No tienes permiso para ver usuarios.');
    }
    return view('admin.users', compact('users'));
  }

  public function createView()
  {
    if (auth()->user()->hasRole('admin')) {
      $ubications = Ubication::all();
      $roles = Role::all();
    } elseif (auth()->user()->hasRole('supervisor')) {
      $userUbications = auth()->user()->ubications->pluck('id');
      $ubications = Ubication::whereIn('id', $userUbications)->get();
      $roles = Role::whereIn('name', ['supervisor', 'worker'])->get();
    } else {
      return redirect()->route('home')->with('error', 'No tienes permiso para crear usuarios.');
    }
    return view('admin.creates.user', compact('ubications', 'roles'));
  }

  public function create(Request $request)
  {
    if (auth()->user()->hasRole('supervisor')) {
      // Verificar que todas las ubicaciones seleccionadas pertenezcan al supervisor
      $userUbications = auth()->user()->ubications->pluck('id')->toArray();
      if (!empty(array_diff($request->ubication_ids, $userUbications))) {
        return redirect()->route('admin.users')->with('error', 'No puedes asignar ubicaciones que no te pertenecen.');
      }
    }

    $request->validate([
      'name' => 'required',
      'email' => 'required|email',
      'password' => 'required',
      'role' => 'required|exists:roles,name',
      'ubication_ids' => 'required|array'
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    $user->ubications()->attach($request->ubication_ids);
    $user->assignRole($request->role);

    return redirect()->route('admin.users')->with('success', 'Usuario creado.');
  }

  public function editView($id)
  {
    if (auth()->user()->hasRole('admin')) {
      $user = User::with('ubications')->find($id);
      $ubications = Ubication::all();
      $roles = Role::all();
    } elseif (auth()->user()->hasRole('supervisor')) {
      $user = User::whereHas('ubications', function ($query) use ($id) {
        $query->where('user_id', $id);
      })->with('ubications')->find($id);
      $userUbications = auth()->user()->ubications->pluck('id');
      $ubications = Ubication::whereIn('id', $userUbications)->get();
      $roles = Role::whereIn('name', ['supervisor', 'worker'])->get();
    } else {
      return redirect()->route('home')->with('error', 'No tienes permiso para editar usuarios.');
    }
    return view('admin.edit.user', compact('user', 'ubications', 'roles'));
  }

  public function edit(Request $request, $id)
  {
    if (auth()->user()->hasRole('supervisor')) {
      // Verificar que todas las ubicaciones seleccionadas pertenezcan al supervisor
      $userUbications = auth()->user()->ubications->pluck('id')->toArray();
      if (!empty(array_diff($request->ubication_ids, $userUbications))) {
        return redirect()->route('admin.users')->with('error', 'No puedes asignar ubicaciones que no te pertenecen.');
      }
    }

    $request->validate([
      'name' => 'required',
      'email' => 'required|email',
      'role' => 'required|exists:roles,name',
      'ubication_ids' => 'required|array',
      'password' => 'nullable'
    ]);

    $user = User::find($id);
    $user->update([
      'name' => $request->name,
      'email' => $request->email,
    ]);

    if ($request->password) {
      $user->update([
        'password' => Hash::make($request->password)
      ]);
    }

    $user->ubications()->sync($request->ubication_ids);
    $user->syncRoles([$request->role]);

    return redirect()->route('admin.users')->with('success', 'Usuario actualizado.');
  }

  public function destroy($id)
  {
    User::destroy($id);
    return redirect()->route('admin.users')->with('success', 'Usuario eliminado.');
  }
}
