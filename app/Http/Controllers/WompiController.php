<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class WompiController extends Controller
{
    public function index (Request $request, $fair_id, $meeting_id, $name = '', $email = '', $token = '') {
		return view('wompi.paymentViewer');
    }
}
