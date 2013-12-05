
$(document).ready(function() {
	
	// Vote
	$(".chart-item:not(.delete)").live("click", function(e) {
		e.preventDefault();
		var post, chart, oldHtml;
		
		if ($(this).parents(".chart").hasClass('novote')) {
			return false;
		}
		
		if($(this).hasClass("like") || $(this).hasClass("dislike")) {
			// Likes
			var vote = "like";
			if($(this).hasClass("dislike"))
				vote = "dislike";
			post = $("input[name=post]").val();
			chart = $(this).parent();
	
			loader(chart);
			$.ajax({
				url : "ajax/",
				type : "post",
				data : "post=like&post_id=" + post + "&vote=" + vote,
				success : function(html) {
					// if chart is not found within html, report error
					if(html.indexOf("chart-item") === -1) {
						error(html);
						loader_remove(chart);
					} else {
						chart.html(html);
						checkbox();
					}
				}
			});
	
		} else {
			// Votes
			var item = $(this);
			chart = $(this).parent();
			post = chart.find("input[name=post]").val();
			var opt = item.find("input[name=opt]").val();
			var check = item.find("input[type=checkbox]");
			var limit = chart.find("input[name=limit]").val();
			oldHtml = chart.html();
	
			loader(chart);
			$.ajax({
				url : "ajax/",
				type : "post",
				data : "post=vote&post_id=" + post + "&limit=" + limit + "&opt_id=" + opt,
				success : function(html) {
					// if chart is not found within html, report error
					if(html.indexOf("chart-item") === -1) {
						error(html);
						loader_remove(chart);
					} else {
						chart.html(html);
						checkbox();
					}
				}
			});
		}
	});
	// Like, Dislike
	$(".post_actions .likepost a, .post_actions .dislikepost a").click(function(e) {
		e.preventDefault();
		
		var li = $(this).parent();
		var ul = $(this).parents("ul");
		var post = ul.find("input[name=post]").val();
		var vote = "dislike";
		if(li.hasClass('likepost'))
			vote = "like";
		
		$.ajax({
			url : "ajax/",
			type : "post",
			data : "post=like&nochart=1&post_id=" + post + "&vote=" + vote,
			success : function(html) {
				if(html === "SUCCESS") {
					if(!li.hasClass("active")) {
						ul.find("li.likepost, li.dislikepost").removeClass("active");
					}
					li.toggleClass("active");
				} else {
					error(html);
				}
			}
		});
	});
	// Favourite Post
	$("#toolslist a[href=#favoritePost], .post_actions .favoritepost a").click(function(e) {
		e.preventDefault();
		
		var method, post_id;
		var t = $(this);
		if ($(this).parents("#toolslist").length > 0) {
			method = 1;
			post_id = $(this).parents("ul").find("input[type=hidden]").val();
		} else {
			method = 2;
			post_id = $(document).find("input[name=post]").val();
		}
		
		
		$.ajax({
			url : "ajax/",
			type : "post",
			data : "post=favorite&post_id=" + post_id,
			success : function(html) {
				if (method === 1) {
					if(t.html() === "Add to Favourites") {
						t.html("Remove from Favourites");
					} else {
						t.html("Add to Favourites");
					}
				} else {
					t.parent().toggleClass("active");
					
					if(t.attr('title') === "Favourite Post") {
						t.attr('title', "Remove from Favourites");
					} else {
						t.attr('title', "Favourite Post");
					}
				}
			}
		});
	});
	// Option Labels Marquee
	$("div.chart-item").mouseenter(function() {
		var label = $(this).find(".label");
		var text = label.find("span");
	
		if(text.width() > label.width()) {
	
			var scrollLeft = label.width() - text.width();
			var speed = text.width() * 10;
	
			if(speed < 2000 && speed > 1000) {
				speed = 800;
			}
	
			text.stop();
			text.animate({
				left : scrollLeft + 'px'
			}, speed);
		}
	
	}).mouseleave(function() {
		var text = $(this).find(".label span");
		text.stop();
		text.animate({
			left : '0px'
		}, 1000);
	});
	
	// Adding options in chart
	$("#opt_add").submit(function(e) {
		e.preventDefault();
	
		var post = $(this).find("input[type=hidden]").val();
		var name = $(this).find("input[type=text]").val();
		
		$.ajax({
			url : "ajax/",
			type : "post",
			data : "option=add&post_id=" + post + "&name=" + name,
			success : function(html) {
				if(html === "SUCCESS") {
					window.location.reload();
				} else {
					error(html);
				}
			}
		});
	});
	// Textbox for add your own under chart
	$("#opt_add input, #tag_add input").focus(function() {
		if($(this).val() === "Add your own..")
			$(this).val("");
	}).blur(function() {
		if($(this).val() === "")
			$(this).val("Add your own..");
	});
	
	// Add Tag in Post View Page
	$("form#tag_add").submit(function(e) {
		e.preventDefault();
		
		var post = $(document).find("input[name=post]").val();
		var tag = $(this).find("input[type=text]").val();
		
		$.ajax({
			url: "ajax/",
			type: "post",
			data: "tag=add&post_id=" + post + "&name=" + tag,
			success: function(html) {
				if (html.search("li") >= 0) {
					$("#post-tags").append(html);
					$("#tag_add input[type=text]").val("");
				} else {
					error(html);
				}
			}
		});
	});
	// Delete Tag in Post View Page
	$("#post-tags .tagdel").click(function(e) {
		e.preventDefault();
		
		var post = $(document).find("input[name=post]").val();
		var tag = $(this).parent().find(".tagname").html();
		var parent = $(this).parent();
		
		$.ajax({
			url: "ajax/",
			type: "post",
			data: "tag=delete&post_id="+post+"&name=" + tag,
			success: function(html) {
				if (html === "SUCCESS") {
					parent.remove();
				} else {
					error(html);
				}
			}
		})
	});
	
	// Change chart item percent to value on mouseover
	$(".chart-item").live('mouseenter', function() {
		var val = $(this).find(".value");
		val.html(val.attr('value'));
	}).live('mouseleave', function() {
		var val = $(this).find(".value");
		val.html(val.attr('percent'));
	});
	
	// Chart delete
	$(".chart-item .delete").live('click', function(e) {
		var confirm = Confirm("Are you sure you wish to delete '" + $(this).parent().find(".label span").html() + "'?");
		var t = $(this);
		if (confirm) {
			$.post("ajax/", 
				{option: 'delete', option_id: $(this).parent().find("input[name=opt]").val(), 
					post_id: $(this).parents(".chart").find("input[name=post]").val()}, 
				function(d) {
					if (d == "SUCCESS") {	
						t.parent().fadeOut(function() {$(this).remove(); window.location.reload();});
					} else {
						error(d);
					}
				}
			);
		}
		return false;
	});
});
