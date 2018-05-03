<?php

namespace App\Http\Controllers;

use App\Result;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ResultController extends Controller
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
        $results = $user->results()->get();

        return view('results.results', compact('results'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function show(Result $result)
    {
        return view('results.result', compact('result'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function user(User $user)
    {
        $results = $user->results()->get();
        return view('results.results', compact('results'));
    }

}
