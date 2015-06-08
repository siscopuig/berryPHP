$(function() {
    // call function
	$.get('dashboard/xhrGetListings', function(o) {
		// console.log(o[0].text);
		for (var i = 0; i < o.length; i++)
		{
			$('#listInserts').append('<div>' + o[i].text + '<a class="del" rel="'+o[i].id+'" href="#">delete</a></div>');
		}
	}, 'json');

	$('#randomInsert').submit(function() {
		var url  = $(this).attr('action');
		var data = $(this).serialize();
		// console.log($data);
		$.post(url, data, function(o) {
			$('#listInserts').append('<div>' + o.text + '<a class="del" rel="'+ o.id +'" href="#">delete</a></div>');
		}, 'json');
        // clear value from input field
        $("#randomInsert")[0].reset();
		return false;
	});
});

$(document).on("click", ".del", function() {

            var id = $(this).attr('rel');
            var delete_item = $(this);
			// alert(id);
    $.post("dashboard/xhrDeleteListing", {"id": id}, function (o) {
        delete_item.parent().remove();
            });
         });