<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        return back()->with('success', 'Спасибо! Мы получили вашу заявку и скоро свяжемся с вами.');
    }
}