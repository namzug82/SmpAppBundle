var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substringRegex;
    matches = [];
    substrRegex = new RegExp(q, 'i');

    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });

    cb(matches);
  };
};

$(document).ready(function(){

    var url = "http://" + window.location.hostname + "/materials/array/";
    var data = $.getJSON( url, function( json ) {
        var materials = json.materials;
        $('#materialSearch').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'materials',
            source: substringMatcher(materials)
        }
        );
    });
});
