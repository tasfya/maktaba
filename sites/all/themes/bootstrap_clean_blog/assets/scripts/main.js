(function($){
	function play(audiofile){
		var audio_player = document.getElementById('audio-player');
		audio_player.removeAttribute("src");
	 	audio_player.src = audiofile;
	 	audio_player.play();
	}
})(jQuery)
