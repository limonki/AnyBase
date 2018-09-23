function openFileSearch(bttn, elem)
{
	elem.trigger("click");
	elem.change(function() {
		var str = elem.val();
		var res = str.split("\\");
		$("#"+bttn.attr('id')+" span").text(res[res.length-1]);
	});
}
