//Global variables
/* global $, grecaptcha, reCaptchaV3SiteKey */
var $trashLaterConfig = {
    config: {
        url: {
            textTrashLateApi: "/api/trashlater/texttrashlate",
            audioTrashLateApi: "/api/trashlater/audiotrashlate",
            imageTrashLateApi: "/api/trashlater/imagetrashlate"
        },
        textPlaceholder: "Para saber más, sube una foto del producto o material a traducir o escribe una palabra clave (ejemplo: papel, vidrio, Nescafé, etc.)",
        imageIntent: {
            width: 224,
            height: 224
        },
        actions: {
            text: "trashlater_text",
            audio: "trashlater_audio",
            image: "trashlater_image"
        },
        reCaptcha: {
            siteKey: reCaptchaV3SiteKey
        },
        errorMessages: [
            "Tú andas confundido o yo no te entendí, pregúntame otra vez. Intenta un término diferente o un material en la barra de arriba.",
            "¿Seguro que quieres reciclarlo? Qué tal un envase vacío, ese sí puede reciclarse.",
            "Empecemos por reciclar lo reciclable. Elige un material de la barra de arriba o vuelve a buscar con un término diferente. "
        ]
    }
};



//TRANSLATE WITH TEXT
//GET VALUE OF TEXTAREA
var textmessage = "";
var typemessage = 0;

//General functions
function sendTextIntent(message, type) {
    if (message.length <= 255) {
        grecaptcha.ready(function () {
            grecaptcha.execute($trashLaterConfig.config.reCaptcha.siteKey,
                { action: $trashLaterConfig.config.actions.text }).then(function (token) {
                    $.ajax({
                        url: $trashLaterConfig.config.url.textTrashLateApi,
                        data: {
                            MediaContent: message,
                            Token: token
                        },
                        type: "POST",
                        crossDomain: false,
                        dataType: "json",
                        success: function (data) {
                            //analyticsTrashLaterSearchEvent($trashLaterConfig.config.actions.text, data);
                            if (data.success) {
                                FillLaterControls(data);
                            } else {
                                ShowLaterErrorMessage(data);
                            }
                        },
                        error: function (jqXhr, textStatus, errorThrown) {
                            console.log("jqXHR: " + jqXhr);
                            console.log("textStatus: " + textStatus);
                            console.log("errorThrown: " + errorThrown);
                        }
                    });
                });
        });
    }
};


function sendAudioIntent(blob) {
    grecaptcha.ready(function () {
        grecaptcha.execute($trashLaterConfig.config.reCaptcha.siteKey, { action: $trashLaterConfig.config.actions.audio }).then(function (token) {
            var formData = new FormData();
            formData.append("audio-blob", blob);
            formData.append("token", token);
            $.ajax({
                url: $trashLaterConfig.config.url.audioTrashLateApi,
                data: formData,
                type: "POST",
                crossDomain: false,
                dataType: "text",
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    data = $.parseJSON(data);
                    //analyticsTrashLaterSearchEvent($trashLaterConfig.config.actions.audio, data);
                    if ((data.success) && (data.materialContent)) {
                        FillLaterControls(data);
                    } else {
              
                        ShowLaterErrorMessage(data);
                    }
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    console.log("jqXHR: " + jqXhr);
                    console.log("textStatus: " + textStatus);
                    console.log("errorThrown: " + errorThrown);
                }
            });
        });
    });
}
function sendImageIntent(imageResized) {
    grecaptcha.ready(function () {
        grecaptcha.execute($trashLaterConfig.config.reCaptcha.siteKey, { action: $trashLaterConfig.config.actions.image }).then(function (token) {
            var formData = new FormData();
            formData.append("image-blob", imageResized);
            formData.append("token", token);
            $.ajax({
                url: $trashLaterConfig.config.url.imageTrashLateApi,
                data: formData,
                type: "POST",
                crossDomain: false,
                dataType: "text",
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    data = $.parseJSON(data);
                    //analyticsTrashLaterSearchEvent($trashLaterConfig.config.actions.image, data);
                    if (data.success) {
                        FillLaterControls(data);
                    } else {
                        ShowLaterErrorMessage(data);
                    }
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    console.log("jqXHR: " + jqXhr);
                    console.log("textStatus: " + textStatus);
                    console.log("errorThrown: " + errorThrown);
                }
            });
        });
    });
}
//this function fills the later section
function FillLaterControls(data)
{
    $(".clearData").css("display","block");
    $("#recordButton").hide();
    $(".camara-icn").hide();
    $("#controls img.listeninfo").show();
    $(".result").show();
    $(".sound-map").show();
    $(".view-more").show();
    $("#dot-loader-div").css("display", "none");
    $(".errorMsg").hide();
    //title
    $('textarea').val(data.materialContent.nameMaterial);

    //description Material
    if (data.materialContent.descriptionMaterial) {
        $(".descriptionMaterial").html(data.materialContent.descriptionMaterial);
    } else {
        $(".descriptionMaterial").html('');
    }

    //image description
    if (data.materialContent.image) {
        $(".imgDescription").show();
        $(".imgDescription").attr('src', data.materialContent.image);
    }
    else {
        $(".imgDescription").hide();
        $(".imgDescription").attr('src', '');
    }

    // recycle
    if (data.materialContent.recycle) {
        $('.RecycleTitle').show();
        $(".Recycle").html(data.materialContent.recycle);
    }
    else {
        $('.RecycleTitle').hide();
        $(".Recycle").html('');
    }

    //suggestion
    if (data.materialContent.suggestion) {
        $(".SuggestionTitle").show();
        $(".Suggestion").html(data.materialContent.suggestion);
    } else {
        $(".SuggestionTitle").hide();
        $(".Suggestion").html('');
    }
    
    //reason to recycle
    if (data.materialContent.ReasontoRecycle) {
        $(".ReasontoRecycleTitle").show();
        $(".ReasontoRecycle").html(data.materialContent.ReasontoRecycle);
    } else {
        $(".ReasontoRecycleTitle").hide();
        $(".ReasontoRecycle").html('');
    }

    //After Recycle Copy
    if (!data.materialContent || !data.materialContent.afterRecycleCopy) {
        $('.AfterRecycleTitle').hide();
        $('.AfterRecycle').hide();
    } else {
        $('.AfterRecycleTitle').show();
        $('.AfterRecycle').show();
        $('.AfterRecycle').text(data.materialContent.afterRecycleCopy);
    };

    //Generate ID YOUTUBE
    if (data.materialContent.youTubeId) {
        var videoid = "https://www.youtube.com/embed/" + data.materialContent.youTubeId;
        var video = "<iframe width='100%' height='315' src='" + videoid + "' frameborder='0' allowfullscreen></iframe>";
        $('.video').html(video);
    }
    else {
        $('.video').html('');
    }

    //set url map
    if (data.materialContent.urlMap) {
        var urlMap = "<a  target='_blank' class='ubication'  href='" + data.materialContent.urlMap + "' ><span class='map'></span>¿Dónde reciclar?</a>";
        $('.mapacontainer').html(urlMap);
    } else {
        $('.mapacontainer').html('');
    }

    // infografia
    if ((data.materialContent.infographieDeskUrl) &&
        (data.materialContent.infographieMobUrl)) {
        $('.infografia').show();
        $('.infografiaTitle').show();
        $('.infografia picture img').attr("src", data.materialContent.infographieDeskUrl);
        $('.infografia picture source').attr("srcset", data.materialContent.infographieMobUrl);
        $('.recuerda').show();
    }
    else {
        $('.infografia').hide();
        $('.infografiaTitle').hide();
        $('.infografia picture img').attr("src", '');
        $('.infografia picture source').attr("srcset", '');
        $('.recuerda').hide();
    }

    //speaker gif
    if (data.materialContent.speakerGif) {
        $(".listeninfo").attr("src", data.materialContent.speakerGif);
    }
    else {
        $(".listeninfo").attr("src", '/img/gifs/Trash_animacion_general.gif');
    }

    //set audio element
    if (!data.materialContent || !data.materialContent.urlSpeech) {
        data.materialContent.urlSpeech = "/media/speech/general.mp3";
    }

    $("#later-audio").off();
    var audioElement = document.getElementById("later-audio");
    audioElement.setAttribute("src", data.materialContent.urlSpeech);
    audioElement.setAttribute("preload", "auto");
    audioElement.volume = 0.75;
    audioElement.addEventListener("canplay",
        function () {
            //$("#play-button").show();
        });

    audioElement.onended = function () {
        $(".listeninfo").attr("src", data.materialContent.speakerGif);
    };

    $(".listeninfo").off();
    $(".listeninfo").on("click",
        function () {
            var audElmt = document.getElementById("later-audio");
            if (audElmt.paused) {
                audElmt.play();
            } else {
                audElmt.pause();
            }
        });

    ShowSoundAnswer();
}

function ShowLaterErrorMessage(data) {
    $(".sound-map").hide();

    $(".result").hide();
    $("#dot-loader-div").hide();

    //load error message
    var errorMessagesIndex = Math.floor(Math.random() * ($trashLaterConfig.config.errorMessages.length));
    $(".errorMsg").html($trashLaterConfig.config.errorMessages[errorMessagesIndex]);
    $(".errorMsg").show();

    //load mp3
    $("#later-audio").off();
    $(".listeninfo").attr("src", '/img/gifs/Trash_animacion_general.gif');
    var audioElement = document.getElementById("later-audio");
    audioElement.setAttribute("src", "/media/speech/not-found-" + errorMessagesIndex.toString() + ".mp3");
    audioElement.setAttribute("preload", "auto");
    audioElement.volume = 0.75;
    audioElement.addEventListener("canplay",
        function () {
            //$("#play-button").show();
        });
    audioElement.onended = function () {
        $(".listeninfo").attr("src", "/img/sound.svg");
    };

    $(".listeninfo").off();
    $(".listeninfo").on("click",
        function () {
            var audElmt = document.getElementById("later-audio");
            if (audElmt.paused) {
                $(this).attr('src', '/img/pause.svg');
                audElmt.play();
            } else {
                $(this).attr('src', '/img/sound.svg');
                audElmt.pause();
            }
        });

    ShowSoundAnswer();


    $(".helper").hide();
    $(".clearData").show();

    //load query text if exist
    if (!data.search) {
        $("#trash-value").html("");
        $(".clearData").hide();
    } else {
        $('textarea').val(data.search);
    }
}

function ShowSoundAnswer() {
    $(".sound-map").show();
    $(".mapacontainer").hide();
}

function HideSoundAnswer() {
    $(".sound-map").hide();
}
    
//Events

//Typing function
(function($) {
$.fn.donetyping = function(callback){
    var _this = $(this);
    var x_timer;    
    _this.keyup(function (){
		$("#dot-loader-div").css("display", "inline-block");
        $("#play-button").hide();
        $("#recordButton").hide();
        $(".camara-icn").hide();
        document.getElementById("later-audio").pause();
		$("canvas").remove();
        clearTimeout(x_timer);
        x_timer = setTimeout(clear_timer, 1e3);
    }); 

    function clear_timer(){
        clearTimeout(x_timer);
        callback.call(_this);
    }
}
})(jQuery);

/*
$.fn.extend({
  donetyping: function (callback, timeout) {
    timeout = timeout || 1e3; // 1 second default timeout
    var timeoutReference,
      doneTyping = function (el) {
        if (!timeoutReference) {
          return;
        }
        timeoutReference = null;
        callback.call(el);
      };
    return this.each(function (i, el) {
      var $el = $(el);
      $el.is(":input") &&
        $el
          .on("keyup keypress paste", function (e) {
            // This catches the backspace button in chrome, but also prevents
            // the event from triggering too preemptively. Without this line,
            // using tab/shift+tab will make the focused element fire the callback.
            if (e.type == "keyup" && e.keyCode != 8) return;

            // Check if timeout has been set. If it has, "reset" the clock and
            // start over again.
            if (timeoutReference) clearTimeout(timeoutReference);
            $("#dot-loader-div").css("display", "inline-block");
            $("#play-button").hide();
            $("#recordButton").hide();
            $(".camara-icn").hide();
            document.getElementById("later-audio").pause();

            timeoutReference = setTimeout(function () {
              // if we made it here, our timeout has elapsed. Fire the
              // callback
              doneTyping(el);
            }, timeout);
          })
          .on("blur", function () {
            // If we can, fire the event since we're leaving the field
            doneTyping(el);
          });
    });
  },
});
*/

$("#trash-value").donetyping(function(){
  textmessage = $(this).val();
  typemessage = 1;
  if (textmessage == ""){
    initialmessage();
  }
  else{
    //$(".result").show();
    sendTextIntent(textmessage,typemessage);
  }
});


//Copy prefilter text in text area
 $('.click_material').click(function(){
	 $("canvas").remove();
  $(this).addClass('active').siblings('.active').removeClass('active');
      $("#dot-loader-div").css("display", "show");
      var search_material = $(this).attr('data-value');
      $('textarea').val(search_material);
      sendTextIntent(search_material, 1);
	});

  $("#capture").change(function () {
    if ($(this)[0].files.length > 0 && $(this)[0].files[0].type.indexOf("image/") > -1) {
        ResizeImage($(this)[0].files[0]);
    }
    $(".camara-icn").removeClass("active-camara");
    $("#dot-loader-div").css("display", "none");
});

// function to resize image to 224x224
function ResizeImage(file) {
  var reader = new FileReader();

  // Set the image once loaded into file reader
  reader.onload = function (e) {
      var img = new Image();
      img.src = this.result;

      setTimeout(function () {
          var newWidth = 224;
          var newHeight = 224;
          var aspectRadio = img.width / img.height;

          if (aspectRadio < 1) {
              newHeight = Math.trunc(newWidth / aspectRadio);
          } else {
              newWidth = Math.trunc(newHeight * aspectRadio);
          }
          var canvas = document.createElement("canvas");
          canvas.width = newWidth;
          canvas.height = newHeight;

          if (canvas.getContext) {
              var ctx = canvas.getContext("2d");
              ctx.fillStyle = "#FFFFFF";
              ctx.fillRect(0, 0, newWidth, newHeight);
              ctx.drawImage(img, 0, 0, newWidth, newHeight);
              var dataUrl = canvas.toDataURL("image/jpeg");
              var byteString = atob(dataUrl.split(",")[1]);
              var mimeString = dataUrl.split(",")[0].split(":")[1].split(";")[0];

              // write the bytes of the string to an ArrayBuffer
              var arrayBuffer = new ArrayBuffer(byteString.length);
              var _ia = new Uint8Array(arrayBuffer);
              for (var i = 0; i < byteString.length; i++) {
                  _ia[i] = byteString.charCodeAt(i);
              }
              var dataView = new DataView(arrayBuffer);
              var blob = new Blob([dataView], { type: mimeString });
              canvas.style.width = '100%';
              canvas.style.height = 'auto';
              canvas.style.maxWidth = `${img.width}px`; 
              $("canvas").remove();
              $("#later-value").prepend(canvas);

              sendImageIntent(blob);
             
          } else {
              // canvas-unsupported code here
              console.log("canvas unsupported");
          }
      }, 100);
  }
  // Load files into file reader
  reader.readAsDataURL(file);
}

//Menu mobile
$(".toggle-menu").click(function(){
  $("body").toggleClass("hidden");
  $(".nav-trashlater ul").toggleClass("open");
  $(".overlay").toggleClass("show");
  $(".icn-mobile-menu").toggleClass("open-menu");
  if ($('.icn-mobile-menu').hasClass('open-menu')) {
    $('.icn-mobile-menu').attr('src','img/close.svg')
  } 
  else{
    $('.icn-mobile-menu').attr('src','img/ham.svg')
  }
});
//Show more or less information
$(".view-more").click(function(){
  $(".truncatedbox").toggleClass("show-info");
  $(this).html( $(this).html() == 'Ver más' ? 'Ver menos' :'Ver más' );
});
//Clear data of textarea
$(".clearData").click(function(){
    initialmessage();
});
function cleartDataMaterial(){
        $(".clearData").hide();
        $(".errorMsg").hide();
        $("#recordButton").show();
        $(".camara-icn").show();
        $('#trash-value').val('');
        $(".truncatedbox").val('');
        $('.click_material').removeClass('active'); //Remove active material
        $(this).hide();
		$("canvas").remove();
        //stop sound if is play
          var audElmt = document.getElementById("later-audio");
          if (!audElmt.paused) {
              audElmt.pause();
          }
}


//Hide cookies-advice advice

$(".close").click(function(){
$(".cookiesContainer").hide();
});
if (!!localStorage.getItem("cookieconsent")) {
  document.body.classList.add("cookieconsent")
  $(".cookiesContainer").fadeOut();
} 
else {
  $(".accept-cookie").click(function() {
      localStorage.setItem("cookieconsent", "ok")
      $(".cookiesContainer").fadeOut();
  });
}



$(".camara-icn").click(function(){
  $(this).addClass("active-camara");
});

//Helpers
function initialmessage(){
    $(".result").hide();
    $("#dot-loader-div").css("display", "none");
    $(".helper").show();
    $(".view-more").hide();
    $(".sound-map").hide();
    cleartDataMaterial();
}


//---Recorder.js Section----//

//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream; 						//stream from getUserMedia()
var rec; 							//Recorder.js object
var input; 							//MediaStreamAudioSourceNode we'll be recording

// shim for AudioContext when it's not avb. 
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext //audio context to help us record

var recordButton = document.getElementById("recordButton");
var stopButton = document.getElementById("stopButton");
var pauseButton = document.getElementById("pauseButton");

//add events to those 2 buttons
recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
pauseButton.addEventListener("click", pauseRecording);

function startRecording() {
	
	$("#stopButton").addClass("stopRecord");
	
	/*
		Simple constraints object, for more advanced audio features see
		https://addpipe.com/blog/audio-constraints-getusermedia/
	*/
    
    var constraints = { audio: true, video:false }

 	/*
    	Disable the record button until we get a success or fail from getUserMedia() 
	*/
    recordButton.disabled = true;
    stopButton.disabled = false;
    pauseButton.disabled = false;

    /*
        We"re using the standard promise based getUserMedia() 
        https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
    */

    navigator.mediaDevices.getUserMedia(constraints).then(function (stream) {

        /*
            create an audio context after getUserMedia is called
            sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
            the sampleRate defaults to the one set in your OS for your playback device

        */
        // shim for AudioContext when it"s not avb. 
        var AudioContext = window.AudioContext || window.webkitAudioContext;
        var audioContext = new AudioContext();

        /*  assign to gumStream for later use  */
        gumStream = stream;

        /* use the stream */
        input = audioContext.createMediaStreamSource(stream);

        /* 
            Create the Recorder object and configure to record mono sound (1 channel)
            Recording 2 channels  will double the file size
        */
        rec = new Recorder(input, { numChannels: 1 });

        //start the recording process
        rec.record();

        // ---- begin onsilence  ----

        var silenceDelay = 2000;
        var minDecibels = -45;
        var analyser = audioContext.createAnalyser();
        input.connect(analyser);
        analyser.minDecibels = minDecibels;

        var data = new Uint8Array(analyser.frequencyBinCount); // will hold our data
        var silenceStart = performance.now();
        var triggered = false; // trigger only once per silence event

        function loop(time) {
            requestAnimationFrame(loop); // we"ll loop every 60th of a second to check
            analyser.getByteFrequencyData(data); // get current data
            if (data.some(v => v)) { // if there is data above the given db limit
                if (triggered) {
                    triggered = false;
                    stream.getTracks().forEach(function (track) {
                        track.stop();
                    });
                }
                silenceStart = time; // set it to now
            }
            if (!triggered && time - silenceStart > silenceDelay) {
                onSilence();
                triggered = true;
            }
        }
        loop();


        function onSilence() {
            $("#stopButton").removeClass("stopRecord");
            $("#recordButton").show();

            //Stop recording when detect silence
            $("#stopButton").click();
            $("#recordButton").removeClass("recording");
            $("#recordButton").show();
        }

        // ---- end onsilence ----


	}).catch(function(err) {
	  	//enable the record button if getUserMedia() fails
    	recordButton.disabled = false;
    	stopButton.disabled = true;
    	pauseButton.disabled = true
	});
}

function pauseRecording(){

	if (rec.recording){
		//pause
		rec.stop();
	}else{
		//resume
        rec.record();

	}
}

function stopRecording() {
    //disable the stop button, enable the record too allow for new recordings
    stopButton.disabled = true;
    recordButton.disabled = false;
    pauseButton.disabled = true;
    $("#stopButton").removeClass("stopRecord");
    $("#recordButton").show();

    //tell the recorder to stop the recording
    rec.stop();

    //stop microphone access
    gumStream.getAudioTracks()[0].stop();

    //create the wav blob and pass it on to createDownloadLink
    rec.exportWAV(createDownloadLink);
}

function createDownloadLink(blob) {
  sendAudioIntent(blob);
}


//Scroll prefilter
$(".materials").click(function() {
  $('html,body').animate({
      scrollTop: $(".trashlater").offset().top},
      'slow');
});


