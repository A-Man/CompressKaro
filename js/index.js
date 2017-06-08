$(document).ready(function() {

	$("#dg1").click(function() {
		$("#fileForm").hide();
		$("#csvForm").hide();
		$("#urlForm").show();
	});

	$("#dg2").click(function() {
		$("#urlForm").hide();
		$("#csvForm").hide();
		$("#fileForm").show();
	});

	$("#dg3").click(function() {
		$("#fileForm").hide();
		$("#csvForm").show();
		$("#urlForm").hide();
	});

	$("#submit1").click(function(e) {
		var url = "ajaxserver/compressViaURL.php";
		var formData = $("#urlList").val();
		$("#wait-message").show();
		$.ajax({
			type: "POST",
			url: url,
			data: {'data': formData},
			dataType: "JSON",
			success: function(data) {
				$("#wait-message").hide();
				var table = $("#results").find('tbody');
				table.html('');
				for(i = 0; i < data.length; i++) {
					if(data[i]["type"] == "success") {
						table.append("<tr>" +
							"<td>" + (i+1) + ".</td>" +
							"<td class='tl'>" + data[i]['src'] + "</td>" +
							"<td>Success</td>" + 
							"<td><a class='btn btn-success' href='" + data[i]['dest'] + "' download='" + data[i]['dest'] + "'>Download</a></td>" +
							"</tr>");
					}
					else {
						table.append("<tr>" +
							"<td>" + (i+1) + ".</td>" +
							"<td class='tl'>" + data[i]['src'] + "</td>" +
							"<td>Error</td>" + 
							"<td class='red'>" + data[i]['error'] + "</td>" +
							"</tr>");
					}
				}
			},
			error: function(data) {
				$("#wait-message").hide();
				alert('Failed to connect to compressor API correctly. Check internet connection.');
			}
		});

		e.preventDefault();
	});

	$("#submit2").click(function(e) {
		e.preventDefault();
		var url = "ajaxserver/compressViaFile.php";
		var form = $("#fileForm").get(0);
		var formData = new FormData(form);
		$("#wait-message").show();
		$.ajax({
			type: "POST",
			url: url,
			data: formData, 
			cache: false,
        	contentType: false,
        	processData: false,
			success: function(data) {
				$("#wait-message").hide();
				var table = $("#results").find('tbody');
				table.html('');
				data = JSON.parse(data);
				if(data["type"] == "success") {
					table.append("<tr>" +
						"<td>1.</td>" +
						"<td class='tl'>" + data['file_name'] + "</td>" +
						"<td>Success</td>" + 
						"<td><a class='btn btn-success' href='" + data['dest'] + "' download='" + data['dest'] + "'>Download</a></td>" +
						"</tr>");
				}
				else {
					table.append("<tr>" +
						"<td>1.</td>" +
						"<td class='tl'>" + data['file_name'] + "</td>" +
						"<td>Error</td>" + 
						"<td class='red'>" + data['error'] + "</td>" +
						"</tr>");
				}				
			},
			error: function(data) {
				$("#wait-message").hide();
				alert('Failed to connect to compressor API correctly. Check internet connection.');
			}
		});
		
	});

	$("#submit3").click(function(e) {
		e.preventDefault();
		var form = $("#csvForm").get(0);
		var formData = new FormData(form);
		// set url based on file extension
		var fileName = $("#uploadFile").val();
		fileName = fileName.split('.'); 
		var extension = fileName[fileName.length - 1];
		var url = extension == "csv" ?  "ajaxserver/compressViaCSV.php" : "ajaxserver/compressViaExcel.php";

		$("#wait-message").show();
		$.ajax({
			type: "POST",
			url: url,
			data: formData, 
			cache: false,
        	contentType: false,
        	processData: false,
			success: function(data) {
				data = JSON.parse(data);
				$("#wait-message").hide();
				var table = $("#results").find('tbody');
				table.html('');
				if(extension == "csv") {
					for(i = 0; i < data.length; i++) {
						if(data[i]["type"] == "success") {
							table.append("<tr>" +
								"<td>" + (i+1) + ".</td>" +
								"<td class='tl'>" + data[i]['src'] + "</td>" +
								"<td>Success</td>" + 
								"<td><a class='btn btn-success' href='" + data[i]['dest'] + "' download='" + data[i]['dest'] + "'>Download</a></td>" +
								"</tr>");
						}
						else {
							table.append("<tr>" +
								"<td>" + (i+1) + ".</td>" +
								"<td class='tl'>" + data[i]['src'] + "</td>" +
								"<td>Error</td>" + 
								"<td class='red'>" + data[i]['error'] + "</td>" +
								"</tr>");
						}
					}
				}
				else {
					for(sheet in data) {
						if (data.hasOwnProperty(sheet)) {
							for(i = 0; i < data[sheet].length; i++) {
								if(data[sheet][i]["type"] == "success") {
									table.append("<tr>" +
										"<td>" + (i+1) + ".</td>" +
										"<td class='tl'>" + data[sheet][i]['src'] + "</td>" +
										"<td>Success</td>" + 
										"<td><a class='btn btn-success' href='" + data[sheet][i]['dest'] + "' download='" + data[sheet][i]['dest'] + "'>Download</a></td>" +
										"</tr>");
								}
								else {
									table.append("<tr>" +
										"<td>" + (i+1) + ".</td>" +
										"<td class='tl'>" + data[sheet][i]['src'] + "</td>" +
										"<td>Error</td>" + 
										"<td class='red'>" + data[sheet][i]['error'] + "</td>" +
										"</tr>");
								}
							}
					    }
					}
				}
			},
			error: function(data) {
				$("#wait-message").hide();
				alert('Failed to connect to compressor API correctly. Check internet connection.');
			}
		});

	});

});