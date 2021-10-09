<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<!-- <body class="antialiased" style="text-align:center; font-size:30px;">

    <p>This project is handled by API's.</p>
    <p>Nothing to see here</p>

    </body> -->

<link href="https://vjs.zencdn.net/7.8.2/video-js.css" rel="stylesheet" />
<script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
<script src="https://vjs.zencdn.net/7.8.2/video.js"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <video id="my-video" class="video-js" controls preload="auto" width="1280" height="720" data-setup="{}">
                <source src="http://{{$_SERVER['SERVER_NAME']}}:8080/hls/mystream.m3u8" type="application/x-mpegURL" res="9999" label="auto" />
                <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
            </video>
        </div>
    </div>
</div>


</html>
