
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/tone@14"></script>
        <script src="ses.js"></script>
    <title>Ses Kaydı Alma</title>
    <style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }

    .container {
        text-align: center;
    }
</style>
</head>
<body>
    <div class="row">
        <div class="container">
            <h1>Ses Kaydı Alma</h1>
            <br>
            <button id="startRecording">Kayda Başla</button>
            <button id="stopRecording" disabled>Duraklat</button>
            <br><br>
            <audio id="audioPlayer" controls></audio>
         </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const startRecordingButton = document.getElementById('startRecording');
            const stopRecordingButton = document.getElementById('stopRecording');
            const audioPlayer = document.getElementById('audioPlayer');
            let mediaRecorder;
            let audioChunks = [];

            startRecordingButton.addEventListener('click', startRecording);
            stopRecordingButton.addEventListener('click', stopRecording);

            async function startRecording() {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);

                mediaRecorder.ondataavailable = function (event) {
                    if (event.data.size > 0) {
                        audioChunks.push(event.data);
                    }
                };

                mediaRecorder.onstop = function () {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    const audioUrl = URL.createObjectURL(audioBlob);
                    audioPlayer.src = audioUrl;
                    audioPlayer.controls = true;
                };

                startRecordingButton.disabled = true;
                stopRecordingButton.disabled = false;

                audioChunks = [];
                mediaRecorder.start();
            }

            function stopRecording() {
                if (mediaRecorder.state === 'recording') {
                    mediaRecorder.stop();
                }

                startRecordingButton.disabled = false;
                stopRecordingButton.disabled = true;
            }
        });
    </script>
</body>
</html>
