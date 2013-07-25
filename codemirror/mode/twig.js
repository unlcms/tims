(function() {
  CodeMirror.defineMode('twig', function(config, parserConfig) {
    var modes = {none: false, comment: 1, variable: 2, control: 3};
    var twigOverlay = {
      startState: function() {
        return {
          mode: modes.none
        };
      },
      token: function(stream, state) {
        // Look for opening brackets
        if (!state.mode) {
          state.firstWord = true;
          if (stream.match('{#')) {
            state.mode = modes.comment;
            return 'comment';
          }
          if (stream.match('{{')) {
            state.mode = modes.variable;
            return 'bracket';
          }
          if (stream.match('{%')) {
            state.mode = modes.control;
            return 'bracket';
          }

          stream.next();
          return null;
        }

        // Look for closing brackets
        if (state.mode == modes.comment) {
          if (stream.match("#}")) {
            state.mode = modes.none;
          } else {
            stream.next();
          }
          return 'comment';
        }
        if (state.mode == modes.control) {
          if (stream.match('%}')) {
            state.mode = modes.none;
            return 'bracket';
          }
        }
        if (state.mode == modes.variable) {
          if (stream.match('}}')) {
            state.mode = modes.none;
            return 'bracket';
          }
        }

        // Skip any spaces from this point
        if (stream.match(' ')) {
          return null;
        }

        // Tags
        if (stream.match(/(end)?(autoescape|block|do|embed|extends|filter|flush|for|from|if|import|include|macro|sandbox|set|spaceless|use|verbatim)\W/)) {
          stream.backUp(1);
          return 'tag';
        }

        // Filters
        if (stream.match(/(abs|batch|capitalize|convert_encoding|date|date_modify|default|escape|first|format|join|json_encode|keys|last|length|lower|merge|nl2br|number_format|raw|replace|reverse|slice|sort|split|striptags|title|trim|upper|url_encode)\W/)) {
          stream.backUp(1);
          return 'builtin';
        }

        // Functions
        if (stream.match(/(attribute|block|constant|cycle|date|dump|include|parent|random|range|template_from_string)\W/)) {
          stream.backUp(1);
          return 'builtin';
        }

        // Tests
        if (stream.match(/(constant|defined|divisibleby|empty|even|iterable|null|odd|sameas)\W/)) {
          stream.backUp(1);
          return 'atom';
        }

        // Operators
        if (stream.match(/(in|is|and|or|not|b-and|b-xor|b-or)\W/)) {
          stream.backUp(1);
          return 'operator';
        }

        // String Literals
        if (stream.match(/'(\\'|[^'])*'/) || stream.match(/"(\\"|[^"])*"/)) {
          return 'string';
        }

        // Variables
        if (state.mode && stream.match(/[a-zA-Z]+\w*\W/)) {
          stream.backUp(1);
          return 'variable';
        }

        // Number Literals
        if (state.mode && stream.match(/\d+(\.|\d)*/)) {
          return 'number';
        }

        // More operators
        if (stream.match(/[+-/%*()=!<>.|~:?\[\]]/)) {
          return 'operator';
        }

        stream.next();
        return null;
      }
    };
    return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || "text/html"), twigOverlay);
  });
})();