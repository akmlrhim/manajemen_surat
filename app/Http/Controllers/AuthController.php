<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
	public function index()
	{
		$title = 'Login Page';

		return view('login', compact('title'));
	}
}
