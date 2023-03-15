<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class TranscriptionController extends Controller
{
    public function index()
    {
        return view('transcription');
    }

    public function transcribe(Request $request)
    {
        $request->validate([
            'audio_file' => 'required_without:audio_link|mimes:mp3',
            'audio_link' => 'required_without:audio_file|url',
        ]);

        if ($request->has('audio_file') && $request->has('audio_link')) {
            return redirect()->back()->withErrors(['error' => 'Only one audio source can be submitted at a time.']);
        }

        if ($request->has('audio_file')) {
            $file = $request->file('audio_file')->getPathname();
        } else {
            $file = $request->input('audio_link');
        }
        $model = 'whisper-1';
        $token = env('OPEN_API');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.openai.com/v1/audio/transcriptions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'file' => new \CURLFILE($file),
                'model' => $model,
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: multipart/form-data'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $transcription = json_decode($response)->text;

        // if (!$request->has('audio_file')) {
        //     unlink($file);
        // }

        return view(
            'transcription',
            [
                'transcription' => $transcription,
                'audio_url' => $request->has('audio_file') ? $request->file('audio_file')->getPathname() : $request->input('audio_link'),
                'subtitle_url' => $transcription
            ]
        );
    }

    private function downloadAudio($url)
    {
        $outputPath = storage_path('app/public/' . uniqid() . '.mp3');
        $command = "youtube-dl --extract-audio --audio-format mp3 --output \"$outputPath\" \"$url\"";
        exec($command);

        return $outputPath;
    }
}
