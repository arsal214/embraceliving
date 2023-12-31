window.AGORA_BROADCAST_UI = {
    // UI buttons
    enableUiControls: function() {

        jQuery("#mic-btn").prop("disabled", false);
        jQuery("#video-btn").prop("disabled", false);
        jQuery("#exit-btn").prop("disabled", false);
        jQuery("#add-rtmp-btn").prop("disabled", false);
        jQuery("#cloud-recording-btn").prop("disabled", false);

        jQuery('#fullscreen-expand').click(window.AGORA_UTILS.toggleFullscreen);

        jQuery("#mic-btn").click(function() {
            window.AGORA_BROADCAST_UI.toggleMic();
        });

        jQuery("#video-btn").click(function() {
            window.AGORA_BROADCAST_UI.toggleVideo();
        });

        jQuery("#cloud-recording-btn").click(function() {
            window.AGORA_BROADCAST_UI.toggleRecording();
        });

        jQuery("#exit-btn").click(function() {
            console.log("so sad to see you leave the channel");
            window.AGORA_BROADCAST_CLIENT.agoraLeaveChannel();
			jQuery('#watch-live-overlay').show();
			//jQuery('#agora_chat_css').remove();
				//jQuery('#agora_chat_js').remove();
			delete_remote_user_house_name();
        });

        jQuery("#screen-share-btn").click(function() {
            window.AGORA_SCREENSHARE_UTILS.toggleScreenShareBtn(); // set screen share button icon
            const loaderIcon = jQuery(this).find('.spinner-border');
            const closeIcon = jQuery('#screen-share-icon');
            loaderIcon.show();
            closeIcon.hide();

            const toggleLoader = function(err, next) {
                loaderIcon.hide();
                closeIcon.show();
                jQuery("#screen-share-btn").prop("disabled", false);

                if (err) {
                    window.AGORA_SCREENSHARE_UTILS.toggleScreenShareBtn();
                }
            }

            jQuery(this).prop("disabled", true); // disable the button on click
            if (window.screenShareActive) {
                window.AGORA_SCREENSHARE_UTILS.stopScreenShare(toggleLoader);
				jQuery('#screen-share-icon').attr('src', window.share_btn_inactive);
            } else {
                window.AGORA_SCREENSHARE_UTILS.initScreenShare(toggleLoader);
				jQuery('#screen-share-icon').attr('src', window.share_btn_active);
				
            }
        });

        jQuery("#start-RTMP-broadcast").click(function() {

            const thisBtn = jQuery(this);
            const loaderIcon = jQuery('#rtmp-loading-icon');
            const configIcon = jQuery('#rtmp-config-icon');

            if (thisBtn.hasClass('btn-danger')) {
                thisBtn.toggleClass('btn-danger');
                window.agoraClient.stopLiveStreaming(window.externalBroadcastUrl);
                return false;
            } else if (thisBtn.hasClass('load-rec')) {
                return false;
            } else {
                thisBtn.toggleClass('load-rec');
                configIcon.hide()
                loaderIcon.show()
            }

            if (window.defaultConfigRTMP['rtmpServerURL'] && window.defaultConfigRTMP['rtmpServerURL'].length > 1) {
                window.AGORA_BROADCAST_CLIENT.startLiveTranscoding();
                // next step: function setupLiveStreamListeners on agora-broadcast-client.js
            }
            // jQuery('#addRtmpConfigModal').modal('toggle');
        });

        jQuery("#add-external-stream").click(function() {
            const formValid = document.getElementById('external-inject-config').checkValidity();
            const errorEl = jQuery('#external-url-error');
            const errorLong = jQuery('#external-url-too-long');
            errorEl.hide();
            errorLong.hide();

            if (!formValid) {
                errorEl.show();
                return;
            }

            const externalUrl = jQuery('#input_external_url').val();
            if (externalUrl.length > 255) {
                errorLong.show();
                return;
            }


            const thisBtn = jQuery('#add-rtmp-btn');
            const loaderIcon = jQuery('#add-rtmp-loading-icon');
            const captureIcon = jQuery('#add-rtmp-icon');

            if (thisBtn.hasClass('load-rec')) {
                return false;
            } else {
                thisBtn.toggleClass('load-rec');
                captureIcon.hide()
                loaderIcon.show()
            }

            // 
            window.AGORA_BROADCAST_CLIENT.addExternalSource();
            jQuery('#add-external-source-modal').modal('toggle');
        });


        jQuery("#stop-rtmp-btn").click(function() {
            window.agoraClient.removeInjectStreamUrl(window.injectedStreamURL);
        });
    },

    toggleCaptureStreamBtn: function(err, status) {
        const thisBtn = jQuery('#add-rtmp-btn');
        const cancelInjectStreamBtn = jQuery('#stop-rtmp-btn')
        const loaderIcon = jQuery('#add-rtmp-loading-icon');
        const captureIcon = jQuery('#add-rtmp-icon');

        const labelStart = thisBtn.parent().find('#label-inject-start');
        const labelStop = thisBtn.parent().find('#label-inject-stop');

        if (status === 'started') {
            thisBtn.toggleClass('load-rec');
            thisBtn.hide();
            cancelInjectStreamBtn.show();
            loaderIcon.hide();
            captureIcon.show();

            labelStart.hide();
            labelStop.show();
        } else if (status === 'stopped') {
            thisBtn.show();
            cancelInjectStreamBtn.hide();

            labelStop.hide();
            labelStart.show();
        }
    },

    toggleMic: function() {
        // window.AGORA_UTILS.toggleBtn(jQuery("#mic-btn")); // toggle button colors
        // window.AGORA_UTILS.toggleBtn(jQuery("#mic-dropdown"));
        if (jQuery("#mic-icon").hasClass('agora-microphone')) {
            window.localStreams.camera.stream.unmuteAudio(); // enable the local mic
            //window.AGORA_UTILS.toggleVisibility("#mute-overlay", false); // hide the muted mic icon
            jQuery("#mic-icon img").attr("src", window.agora_base_url + 'img/hg_sound.png');
        } else {
            window.localStreams.camera.stream.muteAudio(); // mute the local mic
            //window.AGORA_UTILS.toggleVisibility("#mute-overlay", true); // show the muted mic icon
            jQuery("#mic-icon img").attr("src", window.agora_base_url + 'img/hg_sound1.png');
        }
		jQuery("#mic-icon").toggleClass('agora-microphone'); // toggle the mic icon
    },

    toggleVideo: function() {
        if (window.localStreams.camera.stream) {
            // window.AGORA_UTILS.toggleBtn(jQuery("#video-btn")); // toggle button colors
            // window.AGORA_UTILS.toggleBtn(jQuery("#cam-dropdown"));
			jQuery("#video-icon").toggleClass('agora-video'); // toggle the video icon
            if (jQuery("#video-icon").hasClass('agora-video')) {
                window.localStreams.camera.stream.muteVideo(); // enable the local video
                // console.log("muteVideo");
                jQuery("#video-icon img").attr("src", window.agora_base_url + 'img/hg_vid1.png');
            } else {
                window.localStreams.camera.stream.unmuteVideo(); // disable the local video
                // console.log("unMuteVideo");
                jQuery("#video-icon img").attr("src", window.agora_base_url + 'img/hg_video.png');
            }
        }
    },

    toggleRecording: function() {
        if (window.loadingRecord) {
            return false;
        }

        var btn = jQuery("#cloud-recording-btn");
        if (btn.hasClass('start-rec')) {
            window.loadingRecord = true;
            btn.removeClass('start-rec').addClass('load-rec').attr('title', 'Stop Recording');
            console.log("Starting rec...");
            window.AGORA_CLOUD_RECORDING.startVideoRecording(function(err, res) {
                if (err) { window.AGORA_UTILS.showErrorMessage(err); }

                if (res) {
                    btn.removeClass('load-rec').addClass('stop-rec');
                } else {
                    btn.removeClass('load-rec').addClass('start-rec').attr('title', 'Start Recording');
                }
                window.loadingRecord = false;
            });
        } else {
            console.log("Stoping rec...");
            window.AGORA_CLOUD_RECORDING.stopVideoRecording(function(err, res) {
                if (err) {
                    // console.error(err);
                    window.AGORA_UTILS.showErrorMessage(err);
                } else {
                    if (!res.errors) {
                        console.log(res);
                        btn.removeClass('stop-rec').addClass('start-rec').attr('title', 'Start Recording');
                    } else {
                        console.error(res.errors);
                        window.AGORA_UTILS.showErrorMessage(res.errors);
                    }
                }
            })
        }
    },

    calculateVideoScreenSize: function() {
        var container = jQuery('#full-screen-video');
        var size = window.AGORA_BROADCAST_UI.getSizeFromVideoProfile();

        // https://math.stackexchange.com/a/180805
        var newHeight = container.outerWidth() * size.height / size.width;
        container.outerHeight(newHeight);
        console.log('Video SIZE:', newHeight);
    },

    getSizeFromVideoProfile: function() {
        // https://docs.agora.io/en/Interactive%20Broadcast/videoProfile_web?platform=Web#video-profile-table
        switch (window.cameraVideoProfile) {
            case '480p_8':
            case '480p_9':
                return { width: 848, height: 480 };
            case '720p':
            case '720p_1':
            case '720p_2':
            case '720p_3':
                return { width: 1280, height: 720 };
            case '720p_6':
                return { width: 960, height: 720 };
            case '1080p':
            case '1080p_1':
            case '1080p_2':
            case '1080p_3':
            case '1080p_5':
                return { width: 1920, height: 1080 };
        }
    }

}