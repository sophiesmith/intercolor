$(function() {  
$('#save_color').click(function(){
	 $('.palette_color').each(function(i, obj) {
        if (!$(this).hasClass("full")) {
        	var color = $('#color').val();
        	$(this).css('background', color);
        	$(this).addClass("full");
        	$(this).find("input").val(color);
        	return false;
        }
    });
    
  });
});

$(function() {  
	$('.remove').click(function(){
		$(this).parent().removeClass("full");
    	$(this).parent().css("background", "");
    	$(this).next().val("");
 	});
});

$(function () {
    $(document).on('mouseenter', '.palette_color', function () {
        $(this).find(":button").show();
    }).on('mouseleave', '.palette_color', function () {
        $(this).find(":button").hide();
    });
});

$(function () {
	$("#palette_form").submit(function(e) {
		if ( $.trim( $('#palette_name').val() ) == '' ) {
		    alert('Please name your palette!');
		    e.preventDefault(e);
		}
		else if ($("#c1").val() == "" || $("#c2").val() == "" || $("#c3").val() == ""
			|| $("#c4").val() == "" || $("#c5").val() == "" || $("#c6").val() == "") {
			alert("Please choose 6 colors!");
			e.preventDefault(e);
		}
	});
});

