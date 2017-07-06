<?php

namespace App\Http\Controllers;

use App\Quote;
use Illuminate\Http\Request;
use JWTAuth;

class QuoteController extends Controller
{
    public function postQuote(Request $request){
        $user = JWTAuth::parseToken()->toUser();
        $this->validate($request, [
            'comment' => 'required'
        ]);
        $quote = new Quote();
        $quote->comment = $request->input('comment');
        $quote->save();
        return response()->json(['quote' => $quote, 'user' => $user], 201);
    }

    public function getQuotes()
    {
        $quotes = Quote::all();
        $response = [
            'quotes' => $quotes
        ];
        return response()->json($response, 200);
    }

    public function putQuote(Request $request, $id)
    {
        $quote = Quote::find($id);
        if(!$quote){
            return response()->json(['message' => 'Not found'], 404);
        }
        $quote->comment = $request->input('comment');
        $quote->save();
        return response()->json(['quote' => $quote], 200);
    }

    public function deleteQuote($id)
    {
        $quote = Quote::find($id);
        $quote->delete();
        return response()->json(['quote' => 'Quote delete'], 200);
    }
}
