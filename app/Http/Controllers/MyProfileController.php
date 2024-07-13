<?php

namespace App\Http\Controllers;

class MyProfileController extends Controller
{
    public function index()
    {
        return view('admin.user.my-profile.index');
    }

    public function edit()
    {
        $myProfile = user();
        $genders = config('var.genders');

        return view('admin.user.my-profile.edit', compact('myProfile', 'genders'));
    }
}
