var Drupal = Drupal || {};
(function ($, Drupal) {
  Drupal.behaviors.mir = {
    attach: function (context, settings) {
      function play(audiofile) {
        $('#jquery_jplayer_1').jPlayer('setMedia', {
          title: 'Audio loaded !!',
          mp3: audiofile
        });
        $('#jquery_jplayer_1').jPlayer('play');
      }

      $('.field-name-field-audio a, a.play').on('click', function (e) {
        e.preventDefault();
        play($(this).attr('href'));
      });
      // jplayer:
      $(document).ready(function () {
        $('#jquery_jplayer_1').jPlayer({
          ready: function () {
            $(this).jPlayer('setMedia', {
              title: 'Bubble',
              mp3: 'https://archive.org/download/testmp3testfile/mpthreetest.mp3'
            });
          },
          cssSelectorAncestor: '#jp_container_1',
          swfPath: '/js',
          supplied: 'mp3',
          useStateClassSkin: true,
          autoBlur: false,
          smoothPlayBar: true,
          keyEnabled: true,
          remainingDuration: true,
          toggleDuration: true
        });
      });
    }
  };
})(jQuery, Drupal);
