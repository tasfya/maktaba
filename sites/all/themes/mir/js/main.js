var Drupal = Drupal || {};
(function ($,Drupal) {
  Drupal.behaviors.mir = {
    attach: function (context, settings){
			function play(audiofile){
				var audio_player = document.getElementById('audio-player');
				audio_player.removeAttribute("src");
			 	audio_player.src = audiofile;
			 	audio_player.play();
			}

	    $('.field-name-field-audio a').on('click', function(e){
	    	e.preventDefault();
	    	play($(this).attr('href'));
	    });

    }
  }
})(jQuery,Drupal);
