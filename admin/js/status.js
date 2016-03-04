// $(document).ready(function(){
	$(".table-responsive").on('click', '.recrawl', change_content);
// });

function change_content(){
		var contentPanelId = $(this).attr("id");
		var contentPanelAction = $(this).attr("action");
	    // alert(contentPanelId);
	    $.get("recrawl.php?id="+contentPanelId+"&action="+contentPanelAction, function(data, status){
	    	$( "#status-table table" ).replaceWith( data );
	        //alert("Data: " + data + "\nStatus: " + status);
	    });
}

// function doClick () {
// 	alert("click done");
// }