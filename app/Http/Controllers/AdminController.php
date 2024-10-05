<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Macaddress;
use App\Models\Output;
use App\Models\Summaries;
use App\Models\Type;
use App\Models\Ubication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;




class AdminController extends Controller
{
    public function index()
    {
        return view('layouts.indexAdmin');
    }
}
