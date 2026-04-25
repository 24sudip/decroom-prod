<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class Translator
{
    public static function translate($text, $target = 'bn', $source = 'en')
    {
        if ($target == $source) return $text;

        $cacheKey = "translate_{$source}_{$target}_" . md5($text);

        return Cache::remember($cacheKey, 60*60*24, function() use ($text, $target, $source) {
            $prompt = "Translate this text from $source to $target: $text";

            $response = Http::withToken(env('OPENAI_API_KEY'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ]
                ]);

            if ($response->successful()) {
                $result = $response->json()['choices'][0]['message']['content'] ?? null;
                return $result ?: $text; // fallback to original if null/empty
            }

            return $text; // fallback
        });
    }
}
