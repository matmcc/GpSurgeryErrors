<?php

namespace App\Http\Controllers;

use App\Prescription;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PrescriptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web,admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $user = User::find($id);
        $prescriptions = $user->prescriptions()->get();
        //dd(compact($user, $prescriptions));

        return view('prescriptions.prescriptions', compact('prescriptions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function user(User $user)
    {
        $prescriptions = $user->prescriptions()->get();
        return view('prescriptions.prescriptions', compact('prescriptions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function edit(Prescription $prescription)
    {
        \Session::flash('success', "Renewal request for $prescription->name sent");

        return back();
    }

}
