/*
 *  Copyright (c) 2015 The WebRTC project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a BSD-style license
 *  that can be found in the LICENSE file in the root of the source
 *  tree.
 */

'use strict';

var videoElement = document.querySelector('#video');
var audioInputSelect = document.querySelector('select#mic-list');
var videoSelect = document.querySelector('select#camera-list');
var selectors = [audioInputSelect, videoSelect];

function gotDevices(deviceInfos) {
    // Handles being called several times to update labels. Preserve values.
    const values = selectors.map(select => select.value);
    selectors.forEach(select => {
        while (select.firstChild) {
            select.removeChild(select.firstChild);
        }
    });
    for (let i = 0; i !== deviceInfos.length; ++i) {
        const deviceInfo = deviceInfos[i];
        const option = document.createElement('option');
        option.value = deviceInfo.deviceId;
        if (deviceInfo.kind === 'audioinput') {
            option.text = deviceInfo.label || `microphone ${audioInputSelect.length + 1}`;
            audioInputSelect.appendChild(option);
        } else if (deviceInfo.kind === 'videoinput') {
            option.text = deviceInfo.label || `camera ${videoSelect.length + 1}`;
            videoSelect.appendChild(option);
        } else {
            console.log('Some other kind of source/device: ', deviceInfo);
        }
    }
    selectors.forEach((select, selectorIndex) => {
        if (Array.prototype.slice.call(select.childNodes).some(n => n.value === values[selectorIndex])) {
            select.value = values[selectorIndex];
        }
    });
}

navigator.mediaDevices.enumerateDevices().then(gotDevices).catch(handleError);

// Attach audio output device to video element using device/sink ID.
function attachSinkId(element, sinkId) {
    if (typeof element.sinkId !== 'undefined') {
        element.setSinkId(sinkId)
            .then(() => {
                console.log(`Success, audio output device attached: ${sinkId}`);
            })
            .catch(error => {
                let errorMessage = error;
                if (error.name === 'SecurityError') {
                    errorMessage = `You need to use HTTPS for selecting audio output device: ${error}`;
                }
                console.error(errorMessage);
            });
    } else {
        console.warn('Browser does not support output device selection.');
    }
}

function changeAudioDestination() {
    const audioDestination = audioOutputSelect.value;
    attachSinkId(videoElement, audioDestination);
}

function gotStream(stream) {
    window.stream = stream; // make stream available to console
    videoElement.srcObject = stream;
    // Refresh button list in case labels have become available
    return navigator.mediaDevices.enumerateDevices();
}

function handleError(error) {
    console.log('navigator.MediaDevices.getUserMedia error: ', error.message, error.name);
}

function agora_start_preview() {
    if (window.stream) {
        window.stream.getTracks().forEach(track => {
            track.stop();
        });
    }
    const audioSource = audioInputSelect.value;
    const videoSource = videoSelect.value;
    const constraints = {
        audio: false,
        video: {
            deviceId: videoSource ? {
                exact: videoSource
            } : undefined
        }
    };
    navigator.mediaDevices.getUserMedia(constraints).then(gotStream).then(gotDevices).catch(handleError);
}

audioInputSelect.onchange = agora_start_preview;

videoSelect.onchange = agora_start_preview;

agora_start_preview();