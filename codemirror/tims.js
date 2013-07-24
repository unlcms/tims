/**
 * Adds link to toggle syntax highlighting.
 * This code from cpn (Code Per Node) module.
 */

(function ($) {

  Drupal.behaviors.timsCodeMirror = {

    attach: function(context, settings) {

      var $textarea = $('.form-item-template').find('textarea');
      $textarea.parents('.resizable-textarea').find('.grippie').hide();

      var editor = CodeMirror.fromTextArea($textarea.get(0), {
        mode: 'twig',
        lineNumbers: true,
        tabMode: 'shift',
        tabSize: 2
      });

      // Set the editor height to fill as much of the viewport as possible.
      // (offset is an arbitrary number selected using the Seven theme.)
      var offset = 510;
      editor.setSize(null, $(window).height() - offset);
      $(window).resize(function() {
        editor.setSize(null, $(window).height() - offset);
      });

    }

  };

})(jQuery);
