<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class FacebookController extends Controller
{
    public function publicar()
    {
        $pageId = env('FACEBOOK_PAGE_ID');
        $token = env('FACEBOOK_PAGE_TOKEN');

        $response = Http::post("https://graph.facebook.com/v19.0/{$pageId}/feed", [
            'message' => 'Publicación desde Laravel 🚀',
            'access_token' => $token
        ]);

        return $response->json();
    }

    public function verPosts()
    {
        $pageId = env('FACEBOOK_PAGE_ID');
        $token = env('FACEBOOK_PAGE_TOKEN');

        $response = Http::get("https://graph.facebook.com/v19.0/{$pageId}/posts", [
            'access_token' => $token
        ]);

        $posts = $response->json();

        return view('facebook.posts', compact('posts'));
    }
}