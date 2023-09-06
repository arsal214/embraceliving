var instantMeter = document.querySelector('#instant meter');
var instantValueDisplay = document.querySelector('#instant .value');

// Put variables in global scope to make them available to the browser console.
var constraints = window.constraints = {
    audio: true,
    video: false
};

let meterRefresh = null;

function handleSuccess(stream) {
    // Put variables in global scope to make them available to the
    // browser console.
    window.stream = stream;
    var soundMeter = window.soundMeter = new SoundMeter(window.audioContext);
    soundMeter.connectToSource(stream, function(e) {
        if (e) {
            alert(e);
            return;
        }
        meterRefresh = setInterval(() => {
            instantMeter.value =
                soundMeter.instant.toFixed(2);
        }, 200);
    });
}

function handleError(error) {
    console.log('navigator.MediaDevices.getUserMedia error: ', error.message, error.name);
}

function start_visualizer() {
    console.log('Requesting local stream');
    try {
        window.AudioContext = window.AudioContext || window.webkitAudioContext;
        window.audioContext = new AudioContext();
    } catch (e) {
        alert('Web Audio API not supported.');
    }

    navigator.mediaDevices
        .getUserMedia(constraints)
        .then(handleSuccess)
        .catch(handleError);
}