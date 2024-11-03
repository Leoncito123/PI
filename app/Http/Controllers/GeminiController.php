<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;

class GeminiController extends Controller
{
  public function index(Request $request)
  {
    $request->validate([
      'question' => 'required',
    ]);
    $question = $request->question;
    // Initialize the Gemini API client with the API key from the .env file
    $client = new Client(env('GEMINI_API_KEY'));
    // Use the Gemini API to generate a response for the question
    $response = $client->geminiPro()->generateContent(
      new TextPart($question),
    );
    // Extract the answer from the API response
    $answer = $response->text();
    // Return the question and the generated answer as a JSON response
    return response()->json(['question' => $question, 'answer' => $answer]);
  }
}
