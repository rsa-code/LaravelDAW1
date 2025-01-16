<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'productid' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $rating = new Rating();
        $rating->product_id = $request->productid;
        $rating->user_id = Auth::id();
        $rating->rating = $request->rating;
        $rating->save();

        return redirect()->back()->with('success', 'Avaliação registrada com sucesso.');
    }
}

