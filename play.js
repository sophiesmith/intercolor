var moves = 0;
jQuery.fn.swap = function(b){ 
    // method from: http://blog.pengoworks.com/index.cfm/2008/9/24/A-quick-and-dirty-swap-method-for-jQuery
    b = jQuery(b)[0]; 
    var a = this[0]; 
    var t = a.parentNode.insertBefore(document.createTextNode(''), a); 
    b.parentNode.insertBefore(a, b); 
    t.parentNode.insertBefore(b, t); 
    t.parentNode.removeChild(t); 
    moves++;
    return this; 
};

function shuffle() {
    var colors = $(".movable");
    $('.movable').each(function(i, obj) {
        var target = Math.floor(Math.random() * colors.length -1) + 1;
        var target2 = Math.floor(Math.random() * colors.length -1) +1;
        colors.eq(target).before(colors.eq(target2));
    });
}

$(document).ready(function() {
    shuffle();
});

$(function() {
    $('#reshuffle').click(function() {
        shuffle();
    });
});

$(function() {
    $('#restart').click(function() {
        location.reload();
    });
});

$(function() {
    $('#submit').click(function() {
        
        var curr = $("#c1");
        var i = 1;
        while (i < 9) {
            i++;
            var id = "c"+i;
            if (curr.next().attr('id') != id) {
                $("#result").html("not quite. try again!");
                break;
            }
            curr = curr.next();
        }
        if (i == 9) {
            $("#result").html("you won with "+moves+" moves!");
            $("#game").fadeOut(500);
            $("#buttons").fadeOut(500);
            $("#scores").fadeIn(1500);
            $("#moves").val(moves);
        } 
    });
});

$(function() {
    $('.movable').each(function(i, obj) {
        $(this).draggable({ revert: true, helper: function() {
            return $(this);
        } });
    });
    
});

$(function() {
$( ".movable" ).droppable({
    accept: ".movable",
    classes: {
        "ui-droppable-hover": "hover-square"
    },
    deactivate: function(event, ui) {
        $('.movable').each(function(i, obj) {
            var color = this.style.backgroundColor;
            this.style = '';
            this.style.backgroundColor = color;
        });
    },
    drop: function( event, ui ) {

        var draggable = ui.draggable, droppable = $(this),
            dragPos = draggable.position(), dropPos = droppable.position();
        
        draggable.css({
            left: dropPos.left+'px',
            top: dropPos.top+'px',
        });

        droppable.css({
            left: dragPos.left+'px',
            top: dragPos.top+'px',
        });
        draggable.swap(droppable);

        
    },
});
});
