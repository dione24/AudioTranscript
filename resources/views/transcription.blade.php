@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center p-3 my-3 text-white bg-purple rounded shadow-sm">
    <img class="me-3" src="https://cdn-icons-png.flaticon.com/512/2778/2778608.png" alt="" width="48" height="38">
    <div class="lh-1">
        <h1 class="h6 mb-0 text-white lh-1">AudioWishper</h1>
        <small>Powered by Open AI Wishper</small>
    </div>
</div>

<div class="my-3 p-3 bg-body rounded shadow-sm">
    @if(!isset($transcription))
    <form action="{{ route('transcribe') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="audio_file" class="form-label">Audio file</label>
            <input type="file" class="form-control" name="audio_file" id="audio_file">
            @error('audio_file')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="audio_link" class="form-label">Audio or video link</label>
            <input type="text" class="form-control" name="audio_link" id="audio_link"
                placeholder="Enter link to audio or video">
            @error('audio_link')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
    </form>
    @endif

    @if(isset($transcription))
    <div class="mb-3">
        <audio id="player" controls>
            <source src="{{ $audio_url }}" type="audio/mpeg">
            <track kind="subtitles" src="{{ $subtitle_url }}" srclang="fr" label="French">
            Your browser does not support the audio element.
        </audio>
    </div>
    <div class="mb-3">
        <h5>Transcription</h5>
        <pre id="transcriptionText" style="white-space: pre-wrap;">{{ $transcription }}</pre>
    </div>
    <div class="mb-3">
        <button type="button" class="btn btn-secondary" id="copyBtn">Copy to Clipboard</button>
        <button type="button" class="btn btn-secondary"
            onclick="window.location.href='{{ route('home') }}'">Reset</button>
    </div>
    @endif
</div>
<div id="loading" style="display:none;">
    <div class="spinner-border text-primary" role="status">
    </div>
    <span>Loading...</span>
</div>
<script>
    $(document).ready(function() {
        $('#submitBtn').click(function() {
            $('#loading').show();
            $('form').attr('hidden', true);
        });

        $('#copyBtn').click(function() {
            var transcriptionText = $('#transcriptionText').text();
            var temp = $('<textarea>');
            $('body').append(temp);
            temp.val(transcriptionText).select();
            document.execCommand('copy');
            temp.remove();
        });

        @if(isset($transcription))
        $('#copyBtn').attr('hidden', false);
        $('form').hide();
        @endif
    });
</script>
<script>
    $(document).ready(function() {
    @if(isset($transcription))
    const player = new Plyr('#player');
    player.on('timeupdate', function() {
        playerVideo.currentTime = player.currentTime;
    });
    @endif
  });
</script>
@endsection
