<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
// use Auth;


function dataUser()
{
    $data =  User::find(Auth::user()->id);
    return $data;
}
