<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EncomendasController extends Controller
{
    public function index()
    {
        return view('orders.Orders')->withPageTitle('Encomenda');
    }
}