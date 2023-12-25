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

            // Uzaylı ses efekti uygula
            applyAlienEffect(audioUrl);
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

    function applyAlienEffect(audioUrl) {
        // Uzaylı ses efekti uygula
        const distortion = new Tone.Distortion(0.8).toDestination();
        const pitchShift = new Tone.PitchShift({ pitch: 4 }).toDestination();
        const player = new Tone.Player(audioUrl).connect(distortion).connect(pitchShift);
        player.start();
    }
});
