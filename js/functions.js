/*
 * Contains functions for the website
 */ 
function checkbox() {
	$("input[type=radio], input[type=checkbox]").each(function() {
		if ($(this).is(":checked")) {
			$(this).parent().addClass("checked");
		} else {
			$(this).parent().removeClass("checked");
		}
	});
}

function checkPlaceholders() {
    // goes through each text input and checks the placeholder if its in the right place
    $(".placeholder-text").each(function () {        
        var input = $(this).children(".text-input");
        var line_height = input.outerHeight();

        if (input.val() !== "") {
            $(this).children("label").hide();
        }
        if ($(this).find(".info").length > 0) {
        	$(this).css("margin-bottom", "25px");
        }
    });
}

function modal(url) {
	$.ajax({
		url: url,
		cache: false,
		success: function(html) {
			$("body").prepend(html);
			$(".popup")
				.css("margin-top", -($(".popup").outerHeight()/2))
				.css("margin-left", -($(".popup").outerWidth()/2))
				.prepend('<div class="close-button"></div>');
			clearAfter();
		}
	});
}

function Confirm(text) {
    return confirm(text);
}

function redbg(elem) {
    $(elem).css("background", "#A12A2A");
    $(elem).css("color", "white");
    var lfor = $(elem).attr("id");
    $("label[for="+lfor+"]").css("color", "white");
}
function greenbg(elem) {
    $(elem).css("background", "#94de6b");
    $(elem).css("color", "black");
}
function showPopout() {
    var popout = $("#popout");
    var height = popout.outerHeight();
    var width = popout.outerWidth();
    
    $("#dimscreen").fadeIn();
    
    popout.css("margin-top", (-1)*height/2);
    popout.css("margin-left", (-1)*width/2);
    popout.prepend("<img src=\"images/close.png\" id=\"close-popout\" alt=\"Close Window\" />");
    popout.fadeIn();
    
    checkPlaceholders();
}

// updates a given chart
function updateChart(elem, speed) {
    speed = typeof(speed) === 'undefined' ? 500 : speed;
    var highest = elem.find("input[name=highest]").val();
    
    elem.find(".chart-item").removeClass("leading");
    
    elem.children(".chart-item").each(function() {
        var vote = parseInt($(this).find(".vote").html(), 10);
        var colour = $(this).find(".colour");
        var width = vote/highest*100;
        
        if (vote >= highest) {
            if (highest !== 0)
                $(this).addClass("leading");
            colour.animate({width: '100%'}, speed);
        } else {
            if (width === 0)
                colour.animate({width: 0}, speed);
            else 
                colour.animate({width: width+'%'}, speed);
        }
    });
}

// Creates an activity indicator
function loader(elem) {
	elem.prepend("<div class=\"loader\">Voting..</div>");
	elem.find(".loader").fadeIn("fast");
}
function loader_remove(elem) {
	elem.find(".loader").fadeOut("fast");
}

//
// jquery.escape
//
(function() {
escape_re = /[#;&,\.\+\*~':"!\^\$\[\]\(\)=>|\/\\]/;
jQuery.escape = function jQuery$escape(s) {
  var left = s.split(escape_re, 1)[0];
  if (left === s) return s;
  return left + '\\' + 
    s.substr(left.length, 1) + 
    jQuery.escape(s.substr(left.length+1));
};
})();

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

// Displays an error message
function error(message) {
    var e = $("#error");
    
    e.html(message);
    e.animate({top: 0});
    var error_timeout = setTimeout(function() {e.animate({top: '-' + e.outerHeight() + 'px'});}, 4000);
    
    // halts the removing of error if mouse is over element 
    e.mouseenter(function() {
        clearTimeout(error_timeout);
    }).mouseleave(function() {
        error_timeout = setTimeout(function() {e.animate({top: '-' + e.outerHeight() + 'px'});}, 1000);    
    });
}

// Checks whether user has loggedin or not
function loggedin() {
	var ret;
    $.ajax ({
        url: "ajax/",
        data: "loggedin=1",
        type: "post",
        async: false,
        success: function(html) {
            ret = (html === "TRUE");
        }
    });
    return ret;
}

// Gets the cursor location of an element
function getCaret(el) { 
  if (el.selectionStart) { 
    return el.selectionStart; 
  } else if (document.selection) { 
    el.focus(); 

    var r = document.selection.createRange(); 
    if (r == null) { 
      return 0; 
    } 

    var re = el.createTextRange(), 
        rc = re.duplicate(); 
    re.moveToBookmark(r.getBookmark()); 
    rc.setEndPoint('EndToStart', re); 

    return rc.text.length; 
  }  
  return 0; 
}


function fixNewpostOptions(elem) {
	var count = parseInt(elem.find(".opt-count").html(), 10) + 2;
	elem.find(".opt-count").html(count);
	elem.find("label").attr("for", "newpost-opt" + count);

	var input = elem.find("input");
	input.attr("name", "opt" + count);
	input.attr("id", "newpost-opt" + count);

}

function clearAfter() {
	$("ul.radio, .clear-after").each(function() {
		$('<div class="clear"></div>').insertAfter($(this));
	});
}
