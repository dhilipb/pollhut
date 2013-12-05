/*
* my.js: Contains the client-side scripting
*/

// Click Outside
(function($, c, b) {
	$.map("click dblclick mousemove mousedown mouseup mouseover mouseout change select submit keydown keypress keyup".split(" "), function(d) {
		a(d)
	});
	a("focusin", "focus" + b);
	a("focusout", "blur" + b);
	$.addOutsideEvent = a;
	function a(g, e) {
		e = e || g + b;
		var d = $(), h = g + "." + e + "-special-event";
		$.event.special[e] = {
			setup : function() {
				d = d.add(this);
				if(d.length === 1) {
					$(c).bind(h, f)
				}
			},
			teardown : function() {
				d = d.not(this);
				if(d.length === 0) {
					$(c).unbind(h)
				}
			},
			add : function(i) {
				var j = i.handler;
				i.handler = function(l, k) {
					l.target = k;
					j.apply(this, arguments)
				}
			}
		};
		function f(i) {
			$(d).each(function() {
				var j = $(this);
				if(this !== i.target && !j.has(i.target).length) {
					j.triggerHandler(e, [i.target])
				}
			})
		}

	}

})(jQuery, document, "outside");

var wikiSearchAjax, wikiSearch;
$(document).ready(function() {
	$(".disabled input").attr("disabled", "true");
	checkPlaceholders();
	setTimeout(function() {
		checkPlaceholders();
	}, 1000);
	checkbox();

	$("input[type=radio], input[type=checkbox]").click(function() {
		checkbox();
	});

	$("#choices-max").keyup(function(e) {
		$("#choices-max-radio").val($(this).val());
	});
	// Text boxes
	$(".placeholder-text").live("focus", function() {
		$(this).children("label").hide();
	}).live("blur", function() {
		if($(this).children(".text-input").val() === "")
			$(this).children("label").show();
	}).live("click", function() {
		$(this).trigger("focus");
		$(this).children(".text-input").focus();
	});
	// Clear after element
	clearAfter();

	// Comments
	$("#comment-box form").submit(function(e) {
		if(!loggedin()) {
			e.preventDefault();
			error("You need to login to do that.");
		}
	});
	// Comment Likes
	$(".comment-info a[href=#like], .reply a[href=#like]").click(function(e) {
		e.preventDefault();
		var id = $(this).attr("data-id");
		var like = $(this);
		$.ajax({
			type : "post",
			url : "ajax/",
			data : "comments=like&comment_id=" + id,
			success : function(html) {
				if(html === "SUCCESS") {
					var likes = like.parent().find(".like");
					var current = parseInt(likes.html(), 10);
					if(like.html() == "Like") {
						like.html("Unlike");
						likes.html(current + 1);
					} else {
						like.html("Like");
						likes.html(current - 1);
					}
				} else {
					error(html);
				}
			},
			error : function(html) {
				error(html);
			}
		});
	});
	// Reply to a comment
	$(".replylink").click(function(e) {
		e.preventDefault();
		if(loggedin()) {
			$(".reply-box").hide();
			var id = $(this).attr("data-id");

			if($("#replybox-" + id).is(":visible")) {
				$("#replybox-" + id).hide();
			} else {
				$("#replybox-" + id).show();
			}
		} else {
			error("You need to login to do that.");
		}
	});
	// Sorting
	$(".sort").click(function() {
		$(".sort[rel=" + $(this).attr("rel") + "]").removeClass("strong");
		$(this).addClass("strong");
	});
	// Info box close
	$(".infobox a[href=#close]").click(function() {
		$(this).parents(".infobox").fadeOut();
	});
	// Radio buttons
	$(".radio li").click(function() {
		var name = $(this).find("input").attr("name");

		$(".radio input[name=" + name + "]").each(function() {
			if($(this).is(":checked")) {
				$(this).parent().addClass("checked");
			} else {
				$(this).parent().removeClass("checked");
			}
		});
	});
	// max in new post
	$("#max").blur(function() {
		if($(this).val() > 10) {
			error("Please enter a value less than or equal to 10");
			$(this).addClass("error");
		} else {
			$(this).removeClass("error");
		}
	});
	// Deletes a post
	$("a[data-delete]").click(function(e) {
		e.preventDefault();
		var confirm = Confirm("Are you sure you would like to delete the post?");
		if(confirm) {
			var post = $(this).attr("data-delete");
			window.location = "index.php?post=" + post + "&task=delete";
		}
	});
	// Searching
	$("#search-box form").submit(function(e) {
		e.preventDefault();
		if($("#txtsearch").val() === "search") {
			error("Please enter a search term.");
		} else {
			window.location.href = "/search/" + $("#txtsearch").val();
		}
	});
	$("#txtsearch, #lblsearch").focus(function() {
		if($("#lblsearch").is(":visible"))
			$("#lblsearch").hide();
	}).blur(function() {
		if($(this).val() === "")
			$("#lblsearch").show();
	});
	// Adding more options in new post
	$("a[href=#addoptions]").click(function(e) {
		e.preventDefault();
		var row = $("#newpost_options tr").last().clone();
		var cols = row.hide().find("td");

		for(var i = 0; i <= 1; i++) {
			var count = $("#newpost_options td").length + i + 1;
			var name = "post-opt-" + count;
			$(cols.find("label")[i]).attr("for", name).find(".opt-count").html(count);
			$(cols.find("input")[i]).attr({
				"id" : name,
				"name" : name
			});
			$(cols.find(".text-input")[i]).val('');
		}

		$("#newpost_options tbody").append(row);
		$("#newpost_options tr").last().fadeIn();
	});
	// Email share
	var email_widget = false;
	$(".email-share").click(function(e) {
		e.preventDefault();

		var widget = $("#email-widget");
		if(widget.is(":visible")) {
			widget.fadeOut();
			email_widget = false;
		} else {
			var left = $(this).position().left;
			widget.css("left", left - (widget.width() / 2)).fadeIn(function() {
				email_widget = true;
			});
		}
	});

	$('#email-widget').bind('clickoutside', function() {
		if(email_widget) {
			$(this).fadeOut("fast");
			email_widget = false;
		}
	});
	// Open Popup
	$(".modal").live('click', function(e) {
		e.preventDefault();
		modal($(this).attr("href"));
	});
	// Close popup
	$(".close-button").live('click', function(e) {
		e.preventDefault();
		$(".black_bg").remove();
		$(".popup").hide();
	});
	// Listing all the categories
	$("#cat-dropdown").click(function(e) {
		e.preventDefault();

		if($("#catlist").is(":visible")) {
			$(this).removeClass("active");
			$("#catlist").slideUp();
		} else {
			$(this).addClass("active");
			$("#catlist").slideDown();
		}
	});
	// Add wikipedia information to description
	$("a.addwiki").click(function(e) {
		e.preventDefault();
		$("#post-description").parent().children("label").hide();
		$('.addwiki').html("Adding..");
		
		$(".optbox").each(function() {
			var val = $(this).val();

			if(val !== "") {
				var msg = "Retrieving Information for " + val;
				$("#post-description").val($("#post-description").val() + "\n" + msg);

				$.ajax({
					url : "ajax/",
					type : "post",
					data : "wiki=1&title=" + val,
					success : function(html) {
						var desctext = $("#post-description").val();
						if(html == "") {
							msg = "\n" + msg;
						}
						desctext = desctext.replace(msg, html);
						$("#post-description").val(desctext);
						console.log(msg);
					}
				});
			}
		});
		$('.addwiki').html("Add Information from Wikipedia..");
	});
	// ie
	$(".miniposts > li:last-child, footer #footer-nav ul li:last-child, #itemlist-wrapper .chart-fw:last-child").css("border-bottom", "none");

	// error when not loggedin
	$(".notloggedin").click(function(e) {
		e.preventDefault();
		error("You need to login to do that.");
	});
	
	// Search wiki items
	$("#newpost_options .text-input").live('keyup', function(e) {
		if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40) {
			// NOT Enter, Up or Down

			if($(this).val() == "") {
				$("#opt_suggest").hide().html("");
			} else if(wikiSearch != $(this).val() && $(this).val() != "") {
				wikiSearch = $(this).val();
				$(this).addClass('load');
				var t = $(this);
				wikiSearchAjax = $.post("ajax/", {
					wiki : true,
					search : wikiSearch
				}, function(html) {
					$("#opt_suggest").html(html).show();
					t.removeClass('load');
				});
			}
		}
	}).live('keydown', function(e) {

		if((e.keyCode == 38 || e.keyCode == 40) && $(this).val() != "") {
			// Up / Down
			var up = e.keyCode == 38;
			e.preventDefault();
			$("#opt_suggest").show();

			if($('#opt_suggest li.no').length == 0) {
				if($("#opt_suggest li.active").length > 0) {
					if(up) {
						$("#opt_suggest li.active").removeClass("active").prev().addClass("active");
					} else {
						$("#opt_suggest li.active").removeClass("active").next().addClass("active");
					}
				} else {
					if(up) {
						$("#opt_suggest li:last-child").addClass("active");
					} else {
						$("#opt_suggest li:first-child").addClass("active");
					}
				}
				$(this).val($("#opt_suggest li.active").html());
			}
		} else if($("#opt_suggest li.active").length > 0 && e.keyCode == 13) {
			// Enter key
			e.preventDefault();
			$(this).val($("#opt_suggest li.active").html());
			$("#opt_suggest").html("").hide();
		}

	}).live('focus', function() {
		var pos = $(this).offset();
		var bodypos = $("#body").offset();

		$("#opt_suggest").css({
			left : pos.left - bodypos.left - 11,
			top : pos.top - bodypos.top + 22,
			width : $(this).parents("td").width() - 2
		}).attr("for", $(this).attr("id"));

		if($(this).val().length > 0) {
			$(this).trigger("keyup");
		}
	}).live('blur', function() {
		if (typeof wikiSearchAjax != "undefined") {
			wikiSearchAjax.abort();
		}
		$(this).removeClass('load');
		if($("#opt_suggest").is(":visible")) {
			$("#opt_suggest").html('').hide();
		}
	});
	// Click on an option
	$("#opt_suggest li").live("mousedown", function() {
		if(!$(this).hasClass('no')) {
			var id = $("#opt_suggest").attr("for");
			$("#opt_suggest").hide();
			$("label[for=" + id + "]").hide();
			$("#" + id).val($(this).html());
		}
	});
	// Feedback
	$("#feedback_link").mouseenter(function() {
		$(this).animate({
			left : 0
		}, 200);
	}).mouseleave(function() {
		$(this).animate({
			left : -10
		}, 200);
	});
	// Submit
	$("input[type=submit]").live("click", function() {
		$(this).val($(this).attr("data-submit"));
	});
	// Login Form
	$("#loginform").submit(function(e) {
		if($("input[name=username]") == "" || $("input[name=password]") == "") {
			error("Invalid username/password");
			return false;
		}
	});
	// Delete Option in Editpost
	$("#newpost_options .delete").click(function() {
		var confirm = Confirm("Are you sure you wish to delete '" + $(this).parent().find(".text-input").val() + "'?");
		var t = $(this);
		if(confirm) {
			$(this).css({
				'background-image' : 'url(images/loading.gif)'
			});
			$.post("ajax/", {
				option : 'delete',
				option_id : $(this).attr("o"),
				post_id : $(this).attr("p")
			}, function(d) {
				console.log(d);
				if(d == "SUCCESS") {
					error("Removed!");
					t.parent().fadeOut(function() {
						$(this).remove();
					});
				} else {
					error(d);
				}
			});
		}
		return false;
	});
	// Comment check
	$(".reply-box form, #comment-box").live('submit', function(e) {
		if($(this).find("textarea").val() == "") {
			e.preventDefault();
			error("Please enter a comment");
		}
	});
	// New Poll
	$(window).bind("beforeunload", function() {
		if($("#newpoll").length > 0 && $('textarea').val().length > 0) {
			return 'You have unsaved data. Do you really want to close?';
		}
	});
	$('#newpoll').submit(function() {
		$(window).unbind("beforeunload");
	});
	// Embed
	$('#embed input').click(function() {
		$(this).select();
	});
});
