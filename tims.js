jQuery(function() {
    var textArea = jQuery('#edit-template');
    textArea.after('<div id="ace-editor" style="height: 40em;"></div>');
    textArea.hide();

    var editor = ace.edit('ace-editor');
    window.foobar = editor;
    editor.setTheme("ace/theme/chrome");
    editor.getSession().setMode("ace/mode/twig");
    editor.getSession().setValue(textArea.val());
    editor.getSession().on('change', function(e) {
        textArea.val(editor.getSession().getValue());
    })
});
