
var buttonCommon = {
	exportOptions: {
		format: {
			body: function(data, column, row) {
				var div = document.createElement("div");
				div.innerHTML = data;
				var text = div.textContent || div.innerText || ""
				return text;
			}
		}
	}
};
class clog {
	console(message, type = "in") {
		if (type == "er") {
			console.log(
				"%c" + message,
				"color:yellow;color:#fff;font-weight:bold;padding:3px;background-color:#dc3545"
			);
		} else if (type == "wa") {
			console.log(
				"%c" + message,
				"color:yellow;color:#000;font-weight:bold;padding:3px;background-color:#ffc107"
			);
		} else if (type == "su") {
			console.log(
				"%c" + message,
				"color:yellow;color:#fff;font-weight:bold;padding:3px;background-color:#28a745"
			);
		} else {
			console.log(
				"%c" + message,
				"color:yellow;color:#fff;font-weight:bold;padding:3px;background-color:#000069"
			);
		}
	}

	info(message) {
		console.log(
			"%c" + message,
			"color:yellow;color:#fff;font-weight:bold;padding:3px;background-color:#000069"
		);
	}
	error(message) {
		console.log(
			"%c" + message,
			"color:yellow;color:#fff;font-weight:bold;padding:3px;background-color:#dc3545"
		);
	}
	warning(message) {
		console.log(
			"%c" + message,
			"color:yellow;color:#000;font-weight:bold;padding:3px;background-color:#ffc107"
		);
	}
	success(message) {
		console.log(
			"%c" + message,
			"color:yellow;color:#fff;font-weight:bold;padding:3px;background-color:#28a745"
		);
	}
}
var glog = new clog();
var noticeDelay = 4000;
function handleResponses(result, formsel) {
	$('button[type="submit"]').prop('disabled', false);
	$('button[type="button"]').prop('disabled', false);
	$("#" + formsel)
		.find(".error-input")
		.removeClass("error-input");
	$("#" + formsel + " .error-message").remove();
	var log = new clog();
	if (!result) {
		return false;
	}
	if (typeof result == "string") {
		try {
			result = JSON.parse(result);
		}
		catch(err) {
			$("#busy").hide();
			console.log('catch = ' + err.message);
		}
	}

	if (result.message && result.message == 'CSRF token mismatch.') {
		location.reload();
	}
	if (result.responseJSON.status == 200) {
		if (result.responseJSON.callback) {
			eval(result.responseJSON.callback);
			return false;
		}
		if (result.responseJSON.redirect) {
			log.info("res.redirect = " + result.responseJSON.redirect);
			location.href = result.responseJSON.redirect;
		} else {
			location.reload();
		}
		return false;
	}

	let t = JSON.parse(result.responseText);

	switch (result.status) {
		case 422:
			if (result.responseText) {
				jsonErrorStick(result.responseText, formsel);
			} else {
				log.warning("no result.responseText");
			}
			if (t.callback) {
				eval(t.callback);
				return false;
			}
			break;
		case 103:
			if (result.message) {
				alert(result.message);
			} else {
				log.warning("no result.message");
			}
			break;
		case 400:
			let res = result;
			if (typeof res == "string") {
				res = JSON.parse(res);
			}
			if (res.callback) {
				eval(res.callback);
				return false;
			}
			break;
		default:
			log.warning("No Case");
	}
}

function toUpperText(dom) {
	var start = dom.selectionStart;
	var end = dom.selectionEnd;
	dom.value = dom.value.toUpperCase();
	dom.setSelectionRange(start, end);
}

function jsonErrorStick(result, formsel) {
	if (typeof result == "string") {
		result = JSON.parse(result);
	}

	$("#" + formsel)
		.find(".error-input")
		.removeClass("error-input");
	$("#" + formsel + " .error-message").remove();
	if (result.errors) {
		var log = new clog();
		for (var k in result.errors) {
			let s = $("#" + formsel)
				.find("#" + k)
				.parent()
				.parent()
				.find(".select2-success");
			let p = $("#" + formsel)
				.find("#" + k)
				.parent()
				.parent()
				.find(".select2-primary");
			if (
				typeof s.html() == "undefined" ||
				typeof p.html() == "undefined"
			) {
				$("#" + formsel)
					.find('[name="' + k + '"]')
					.parent()
					.append(
						'<div class="error-message">' +
							result.errors[k] +
							"</div>"
					);
				$("#" + formsel)
					.find('[name="' + k + '"]')
					.addClass("error-input");
			} else {
				$(s).append(
					'<div class="error-message">' + result.errors[k] + "</div>"
				);
			}
		}
	}
}

$(document).ajaxStart(function () {
	if (typeof $(".select2-container--open").html() == "undefined") {
		$("#busy").show();
		$("button,#submit-button").prop("disabled", true);
	}
});

$(document).ajaxComplete(function (event, xhr, settings) {
	$("#busy").hide();
	$("button,#submit-button").prop("disabled", false);
});

$(document).ajaxError(function () {
	$("#busy").hide();
	$("button,#submit-button").prop("disabled", false);
});

$(document).ready(function () {

	$("body").on("keyup", 'form .upper-text', function () {
		toUpperText(this);
	}); // blur .error-input end

	$("body").on("blur", ".error-input", function () {
		$(this).parent().find(".error-message").remove();
		$(this).parent().find(".error-input").removeClass("error-input");
	}); // blur .error-input end

	$("body").on("click", ".doajax", function () {
		$("#busy").show();
		let log = new logs();
		var p = {};
		var m = $(this);
		var result = m.data("result");
		var data = m.data("data");
		var edata = m.data("edata");
		var aurl = m.data("url");
		var partial = m.data("partial");
		var before = m.data("before");
		var after = m.data("after");
		var replace = m.data("replace");
		var append = m.data("append");
		var prepend = m.data("prepend");
		var once = m.hasClass("once");
		var method = m.data("method");
		var reqtype = m.data("reqtype");
		p.uajax = "1";
		p._token = $('meta[name="csrf-token"]').attr("content");
		p.token = $('meta[name="csrf-token"]').attr("content");
		edata ? (p.edata = edata) : "";
		!result ? (result = "#handlers") : "";
		m.removeClass("doajax");
		if (reqtype == "json") {
			data
				? partial
					? (p.data = formData(data))
					: (p.data = formData(data))
				: "";
		} else {
			reqtype = "text/html";
			data
				? partial
					? (p.data = partialData(data))
					: (p.data = $(data).serialize())
				: "";
		}
		if (window.location.search) {
			p.queryStr = window.location.search;
			aurl = aurl + window.location.search;
		}

		// ajax post start
		$.ajax({
			url: aurl,
			// headers: { "x-reqtype": reqtype },
			type: method ? method : "POST",
			data: p,
			dataType: "html",
			success: function (data, status, xhr) {
				m.addClass("doajax");
				if(!data) {
					$("#busy").hide();
					return false;
				}
				var json = JSON.parse(data);
				json && json.js ? eval(json.js) : "";
				json && json.html ? (data = json.html) : "";
				// console.log('data='+data);
				// log.info('data = ' + data + ', json.html = ' + json.html+ ', json.js = ' + json.js);
				// if (xhr.status == 302) { data = JSON.parse(data); }
				// var datatype = (xhr.getResponseHeader('datatype'));
				// var resaction = xhr.getResponseHeader('action');
				// if (resaction && resaction != '') {
				// 	if (resaction == 'jsonBody') {
				// 		resaction = data.replace(/null$/, "");
				// 	}
				// 	// WILL DO JSON ACTION
				// }
				if (append) {
					$(result).append(data);
				} else if (prepend) {
					$(result).prepend(data);
				} else if (replace) {
					$(result).replaceWith(data);
				} else {
					if (!after && !before) {
						// log.info('result='+result);
						$(result).html(data);
					}
					// log.error('json.js='+json.js);
					json && json.js ? eval(json.js) : ""; // 18-01-2023
				}
				if (result == "#handlers") {
					$(result).html("");
				}
				$("#busy").show();
					after ? eval(after) : "";
			},
			beforeSend: function (xhr, settings) {
				$("#busy").show();
				before ? eval(before) : "";
			},
			complete: function (event, request) {
				if (once) {
					m.removeClass("doajax");
				} else {
					m.addClass("doajax");
				}
				$("#busy").hide();
				m.attr("disabled", false);
				m.find(".load-bar").remove();
			},
			error: function (xhr, response) {
				m.addClass("doajax");
				$("#busy").hide();
				if(xhr.responseText) {
					try {
						var json = JSON.parse(xhr.responseText);
						// new alert and other messages method starts below
						let message = json && json.message ? json.message : '';
						let title = json && json.title ? json.title : '';
						let redirect = json && json.redirect ? json.redirect : '';
						let error = json && json.error ? json.error : '';
						if(message && redirect) {
							if(error==422 || error=='422') {
								return errorAlert(title,message, redirect);
							} else {
								return successAlert(title,message, redirect);
							}
						}
						// new alert and other messages method ends above

						json && json.html ? (response = json.html) : "";
						json && json.js ? eval(json.js) : "";
						json && json.callback ? eval(json.callback) : "";
					}
					catch(err) {
						console.log('catch = ' + err.message);
					}
				}
				return false;
			},
		}); // ajax end
	}); // .doajax end

	$("body").on("submit", ".default-action-from", function (e) {
		e.preventDefault();
		let actionUrl = $(this).attr("action");
		let formId = $(this).attr("id");
		if (window.location.search) {
			actionUrl = actionUrl + window.location.search;
		}
		if (typeof actionUrl == "undefined") {
			alert("actionUrl is not defined");
			return false;
		} else if (typeof formId == "undefined") {
			alert("formId is not defined");
			return false;
		}
		$.ajax({
			type: "POST",
			url: actionUrl,
			data: $("#" + formId).serialize(),
			cache: false,
			beforeSend: function (xhr, settings) {
				$("#busy").show();
			},
			complete: function (event, request) {
				$("#busy").hide();
			},
			success: function (response) {
				$("#busy").hide();
				handleResponses(response, formId);
			},
			error: function (data) {
				$("#busy").hide();
				handleResponses(data, formId);
			},
		});

	});

	$("body").on("click", ".doajax-confirm", function () {
		let p = $(this).data("prompt");
		let btn = $(this);
		if (typeof(p)== "undefined") {
			p = "Are you sure want to proceed?";
		}
		Swal.fire({
			title: p,
			// showDenyButton: true,
			showCancelButton: true,
			confirmButtonText: 'Proceed',
			closeOnClickOutside: false,
 			closeOnEsc: false,
 			allowOutsideClick: false,
			// denyButtonText: `Don't save`,
		}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.isConfirmed) {
				// Swal.fire('Saved!', '', 'success')
				$(btn).addClass('doajax');
				$(btn).removeClass('doajax-confirm');
				$(btn).trigger('click');
			} else if (result.isDenied) {
				Swal.fire('Changes are not saved', '', 'info')
			}
		})
		return false;
		if(confirm(p)) {
			$(this).addClass('doajax');
			$(this).removeClass('doajax-confirm');
			$(this).trigger('click');
		} else {
			return false;
		}
	});

	$("body").on("click", ".ask-confirm", function () {
		let p = $(this).data("prompt");
		if (typeof p != "undefined") {
			return confirm(p);
		} else {
			return confirm("Are you sure want to proceed?");
		}
	}); // .ask-confirm end

	$.ajaxSetup({
		headers: {
			"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
		},
	});

	$("body").on("click", ".password-option", function () {
		let id = $(this).data('id');
		if(typeof(id)!='undefined') {
			if($(this).hasClass('fa-eye')) {
				$(this).removeClass('fa-eye').addClass('fa-eye-slash');
				$('#'+id).attr('type', 'text');
			} else if($(this).hasClass('fa-eye-slash')) {
				$(this).removeClass('fa-eye-slash').addClass('fa-eye');
				$('#'+id).attr('type', 'password');
			}
		}
	});

}); // $(document).ready end

class logs {
	console(message, type = "in") {
		if (type == "er") {
			console.log(
				"%c" + message,
				"color:yellow;color:#fff;font-weight:bold;padding:3px;background-color:#dc3545"
			);
		} else if (type == "wa") {
			console.log(
				"%c" + message,
				"color:yellow;color:#000;font-weight:bold;padding:3px;background-color:#ffc107"
			);
		} else if (type == "su") {
			console.log(
				"%c" + message,
				"color:yellow;color:#fff;font-weight:bold;padding:3px;background-color:#28a745"
			);
		} else {
			console.log(
				"%c" + message,
				"color:yellow;color:#fff;font-weight:bold;padding:3px;background-color:#000069"
			);
		}
	}

	info(message) {
		console.log(
			"%c" + message,
			"color:yellow;color:#fff;font-weight:bold;padding:3px;background-color:#000069"
		);
	}
	error(message) {
		console.log(
			"%c" + message,
			"color:yellow;color:#fff;font-weight:bold;padding:3px;background-color:#dc3545"
		);
	}
	warning(message) {
		console.log(
			"%c" + message,
			"color:yellow;color:#000;font-weight:bold;padding:3px;background-color:#ffc107"
		);
	}
	success(message) {
		console.log(
			"%c" + message,
			"color:yellow;color:#fff;font-weight:bold;padding:3px;background-color:#28a745"
		);
	}
}

function formData(form) {
	var unindexedArray = $(form).serializeArray();
	var indexedArray = {};
	$.map(unindexedArray, function (n, i) {
		indexedArray[n["name"]] = n["value"];
	});
	return JSON.stringify(indexedArray);
}

function partialData(params, mode = "normal") {
	if (mode == "normal") {
		paramText = $(params)
			.find("select, textarea, input, radio, checkbox")
			.serialize();
	} else if (mode == "json") {
		paramText = $(params)
			.find("select, textarea, input, radio, checkbox")
			.serializeArray();
	}
	return paramText;
}

function partialDataReset(params) {
	paramText = $(params)
		.find("select, textarea, input, radio, checkbox")
		.val("");
}

function showerror(message, titletxt = "Error") {
	PNotify.error({
		delay: noticeDelay,
		title: titletxt,
		text: message,
		width: "auto",
		right: 0,
		addClass: "pnotify-container-uv",
	});
	return null;
}

function showinfo(message, titletxt = "Info") {
	PNotify.info({
		delay: noticeDelay,
		title: titletxt,
		text: message,
		width: "auto",
		right: 0,
		addClass: "pnotify-container-uv",
	});
	return null;
}

function successAlert(message, alertTitle, redirect='') {
	Swal.fire({
		title:alertTitle,
		text:message,
		icon:'success',
		allowOutsideClick:false,
		allowEscapeKey:false,
	}).then(function(e) {
		if(redirect===true) {
			location.reload();
		} else {
			if (e.value && redirect) {
				console.log('e.value='+e.value);
				location.href = redirect;
			}
		}
	})
}

function errorAlert(message, alertTitle, redirect='') {
	Swal.fire({
		title:alertTitle,
		text:message,
		icon:'error',
		allowOutsideClick:false,
		allowEscapeKey:false,
	}).then(function(e) {
		if(redirect===true) {
			location.reload();
		} else {
			if (e.value && redirect) {
				console.log('e.value='+e.value);
				location.href = redirect;
			}
		}
	})
}

function showsuccess(message, titletxt = "Success") {
	// PNotify.success({
	// 	delay: noticeDelay,
	// 	title: titletxt,
	// 	text: message,
	// 	width: "auto",
	// 	right: 0,
	// 	addClass: "pnotify-container-uv",
	// });
	Swal.fire({
		title: titletxt,
		text: message,
		icon: 'success',
		// showCancelButton: true,
		// confirmButtonColor: '#3085d6',
		// cancelButtonColor: '#d33',
		// confirmButtonText: 'Yes, delete it!'
	})
	return null;
}

function shownotice(message, titletxt = "Note") {
	PNotify.notice({
		delay: noticeDelay,
		title: titletxt,
		text: message,
		width: "auto",
		right: 0,
		addClass: "pnotify-container-uv",
	});
	return null;
}

function copyToClipboard(ID_NAME) {
	var range = document.createRange();
	range.selectNode(document.getElementById(ID_NAME));
	window.getSelection().removeAllRanges(); // clear current selection
	window.getSelection().addRange(range); // to select text
	document.execCommand("copy");
	window.getSelection().removeAllRanges(); // to deselect
	showinfo("Text copied to buffer!");
}

function doSubmit(FROM_ID_NAME) {
	if (!FROM_ID_NAME) {
		console.log("FROM_ID_NAME is not set");
		return false;
	}
	let actionUrl = $("#" + FROM_ID_NAME).attr("action");
	let formId = $("#" + FROM_ID_NAME).attr("id");
	glog.success("actionUrl=" + actionUrl + ", formId=" + formId);

	if (window.location.search) {
		actionUrl = actionUrl + window.location.search;
	}
	glog.info("after actionUrl=" + actionUrl);
	if (typeof actionUrl == "undefined") {
		alert("actionUrl is not defined");
		return false;
	} else if (typeof formId == "undefined") {
		alert("formId is not defined");
		return false;
	}
	$.ajax({
		type: "POST",
		url: actionUrl,
		data: $("#" + formId).serialize(),
		cache: false,
		beforeSend: function (xhr, settings) {
			$("#busy").show();
		},
		complete: function (event, request) {
			$("#busy").hide();
		},
		success: function (response) {
			$("#busy").hide();
			handleResponses(response, formId);
		},
		error: function (data) {
			$("#busy").hide();
			handleResponses(data, formId);
		},
	});
	return false;
}

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function savePdf(selectorId, filename){

	var HTML_Width = $(selectorId).width();
	var HTML_Height = $(selectorId).height();
	var top_left_margin = 15;
	var PDF_Width = HTML_Width+(top_left_margin*2);
	var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
	var canvas_image_width = HTML_Width;
	var canvas_image_height = HTML_Height;

	var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;


	html2canvas($(selectorId)[0],{allowTaint:true}).then(function(canvas) {
		canvas.getContext('2d');

		console.log(canvas.height+"  "+canvas.width);


		var imgData = canvas.toDataURL("image/jpeg", 1.0);
		var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
		pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);


		for (var i = 1; i <= totalPDFPages; i++) {
			pdf.addPage(PDF_Width, PDF_Height);
			pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
		}

		pdf.save(filename+".pdf");
	});
};

function saveHtml2pdf(selectorId, filename) {
    var HTML_Width = $(selectorId).width();
    var HTML_Height = $(selectorId).height();
    var top_left_margin = 15;
    var PDF_Width = HTML_Width + (top_left_margin * 2);
    var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;

    var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

    html2canvas($(selectorId)[0]).then(function (canvas) {
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
        for (var i = 1; i <= totalPDFPages; i++) {
            pdf.addPage(PDF_Width, PDF_Height);
            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
        }
        pdf.save(filename+".pdf");
    });
}

function loadRegion(json, parent='') {
	// {"State":{"sta_id":33,"sta_name":"TAMIL NADU"},"District":{"dis_id":582,"dis_name":"Trichy"},"City":{"cit_name":"Trichy"}}
	if(!json) {
		return;
	}

	parent = (parent?(parent+' '):'');
	if(json && json.State && json.State.sta_id && json.State.sta_name) {
		$(parent+".states").val(json.State.sta_id).trigger('change.select2');
		$(parent+".district").val('').trigger('change.select2');
		$(parent+".city").val('').trigger('change.select2');
	} else {
		$(parent+".states").val(null).trigger('change.select2');
	}
	if(json && json.District && json.District.dis_id && json.District.dis_name) {
		$(parent+".district").html('<option value="'+json.District.dis_id+'">'+json.District.dis_name+'</option>');
		reSelect2District(parent);
	} else {
		$(parent+".district").html('<option value="'+null+'">-Select District-</option>');
		reSelect2District(parent);
	}
	if(json && json.City && json.City.cit_id && json.City.cit_name) {
		$(parent+".city").html('<option value="'+json.City.cit_id+'">'+json.City.cit_name+'</option>');
		reSelect2City(parent);
	} else {
		$(parent+".city").html('<option value="'+null+'">-Select City-</option>');
		reSelect2City(parent);
	}

}

function reSelect2District(parent='') {
	$(parent+'.district').select2({
		width: '100%',
		ajax: {
			url: '/check-dis',
			dataType: 'json',
			allowClear: true,
			data: function(params) {
				return {
					q: params.term,
					states: $(parent+'.states').val(),
				};
			},
			processResults: function(data) {
				console.log(data);
				return {
					results: data
				};
			},
			cache: true
		}
	});
}

function hideForm0(message='', title='') {
	if(message) { shownotice(message, title); }
	$(".settings-cols .close-form").trigger("click");
	$(".settings-cols .close-form").click();
}
function hideForm1(message='', title='') {
	if(message) { showsuccess(message, title); }
	$(".settings-cols .close-form").trigger("click");
	$(".settings-cols .close-form").click();
}

function reSelect2City(parent='') {
	$(parent+'.city').select2({
		width: '100%',
		ajax: {
			url: '/check-city',
			dataType: 'json',
			allowClear: true,
			data: function(params) {
				return {
					q: params.term,
					state: $(parent+'.states').val(),
				};
			},
			processResults: function(data) {
				console.log(data);
				return {
					results: data
				};
			},
			cache: true
		}
	});
}

function openModal(modal_id) {
	modalObj = $('#' + modal_id);
	if (!modalObj.hasClass('show')) {
		if ($('#' + modal_id + '-btn')) {
			$('#' + modal_id).modal({backdrop:'static', keyboard:false}, 'show');
		} else {
			modalObj.modal({backdrop:'static', keyboard:false}, 'show');
		}
	}
}


function closeModal(modal_id) {
	modalObj = $('#' + modal_id);
	if (modalObj.hasClass('show')) {
		modalObj.modal('hide');
	}
}
