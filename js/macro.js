/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * JavaScript: Execute Macro Page js macro.php
*
*/
var ezCMSjs = {

    numbStart: 1,
    numbShown: 20,
    numbPages: 0,

	init: function() {
		$("#top-bar li").removeClass('active');
		$("#top-bar > li:eq(4)").addClass('active');
		$('#macro-select a').click(function (e) {
			e.preventDefaults;
			var macro = $(this).text().trim();
			$('#macro-select li').removeClass('active');
			$(this).parent().addClass('active');
			$('#macro-select').data('macro',macro).prev().find('strong').text(macro);
			$('#editmacro').show().prop('href','macros.php?show='+macro);
			return false;
		});
		$('#frmfind').submit(function (e) {
			e.preventDefaults;
			ezCMSjs.getPages();
			return false;
		});
		$('#pager a').click(function (e) {
			e.preventDefaults;
			ezCMSjs.goToPage($(this));
			return false;
		});
		$('#currpage').change(function () {
			ezCMSjs.drawPage($(this).val());
		});
		$('#stopexe').click(function () {
			ezCMSjs.pageids = [];
			ezCMSjs.setMacroRunMode(false);
			return false;
		});
		$('#checkfull').click(ezCMSjs.toggleCheckedFull);
		$('#checkall').click(ezCMSjs.toggleChecked);
		$('#runmacro').click(ezCMSjs.runMacro);
	},

	runMacro: function () {
		if (!$('#macro-select').data('macro')) {
			alert('Please select the macro you want to execute.');
			$('#macro-select').parent().focus();
			return false;
		}
		ezCMSjs.pageids = ezCMSjs.results.filter(function(r){return(r.status==1)});
		if (!ezCMSjs.pageids.length) {
			alert('Please check the pages to execute the macro on.');
			$('#resultsTable tbody input[type="checkbox"]')[0].focus();
			return false;
		}
		ezCMSjs.setMacroRunMode(true);
		ezCMSjs.processNext(
			$('#macro-select').data('macro'), 
			$("#incsidebar").is(":checked") ? 1 : 0,
			$("#incsiderbar").is(":checked") ? 1 : 0);
	},

	setMacroRunMode: function(isRunning) {
		if (isRunning) {
			var total = ezCMSjs.pageids.length;
			$('#progressbox').show();
			$('#exenavbar, #pager').hide();
			$('#progressbox h4 i').text($('#macro-select').data('macro'));
			$('#progressbox h4 .runrow').text('1');
			$('#progressbox h4 .totrow').text(total);
			$('#progressbox .bar').width( ((1/total)*100)+'%');
		} else {
			$('#progressbox').hide();
			$('#exenavbar, #pager').show();
		}
	},

	processNext: function (macro, side1, side2) {
		if (!ezCMSjs.pageids.length) return false;
		var log = '', idx,
			page = ezCMSjs.pageids[0],
			pageID = page.id,
			postData = {'id':pageID,'macro':macro,'side1':side1,
				'side2':side2};
		$('#progressbox > i').text('Processing: ' + page.pagename + ' ' +page.url);
		idx =  ezCMSjs.results.findIndex(function(r) { return r.id===pageID; });
		// bring index row page up
		var tablepage = Math.ceil((idx+1)/ezCMSjs.numbShown);
		if (!tablepage) tablepage = 1;
		if (parseInt($('#currpage').val())!=tablepage) ezCMSjs.drawPage(tablepage);
		$('#row'+pageID).addClass('error');
		$.post( 'macro.php?execute', postData, function(data) {
			if (data.success) {
				ezCMSjs.pageids.shift();
				data.log.forEach(function(line) {
					log +='<span class="label label-'+line.label+'">'+line.msg+'</span> ';
				});
				ezCMSjs.setRowDone($('#row'+pageID), log);
				ezCMSjs.results[idx].status = 2;
				ezCMSjs.results[idx].log = log;
				if (ezCMSjs.pageids.length) {
					var doneCnt = parseInt($('#progressbox h4 .runrow').text())+1,
						todoCnt = parseInt($('#progressbox h4 .totrow').text());
					$('#progressbox h4 .runrow').text(doneCnt);
					$('#progressbox .bar').width(((doneCnt/todoCnt)*100)+'%');
					setTimeout( function() {
						ezCMSjs.processNext(macro, side1, side2);
					}, 500);
				} else ezCMSjs.setMacroRunMode(false);
			} else {
				alert('Error: '+ data.msg);
				ezCMSjs.setMacroRunMode(false);
			}
		}, 'json').fail( function() { 
			alert('Failed: The request failed.');
			ezCMSjs.setMacroRunMode(false);
		});
	},

	setRowDone: function (tr, log) {
		tr.removeClass('error').addClass('success');
		tr.find('input').after('<i class="icon-check"></i>').remove();
		tr.find('.rowlog').html(log);
	},

	getPages: function() {
		$('#frmfind input[type="submit"]').prop('disabled', true);
		$('#pager').hide();
		$('#resultsTable tbody').html(
			'<tr><td colspan="5">Working ... <img src="img/ajax-loader.gif"></td></tr>');
		$.post('macro.php?fetchall', $('#frmfind').serialize(), function(data) {
			if (data.success) {
				if (data.results.length) {
					// status 0-unchecked 1-checked 2-processed
					ezCMSjs.results = data.results.map(v => ({...v, status: 0, log: ''}));
					$('#runmacro').show();
					ezCMSjs.numbPages = Math.ceil(ezCMSjs.results.length/ezCMSjs.numbShown);
					ezCMSjs.drawPage(1);
					if (ezCMSjs.numbPages>1) {
						$('#numbpages').val(ezCMSjs.numbPages);
						$('#currpage').val('1').prop('max',ezCMSjs.numbPages);
						$('#pager').show();
					}
				} else {
					ezCMSjs.setNoResults();
					$('#runmacro').hide();
				}
			} else {
				alert('Error: '+ data.msg);
				ezCMSjs.setNoResults();
			}
			$('#frmfind input[type="submit"]').prop('disabled', false);
		}, 'json').fail( function() { 
			alert('Failed: The request failed.'); 
			ezCMSjs.setNoResults();
			$('#frmfind input[type="submit"]').prop('disabled', false);
		});
	},

	setNoResults: function () {
		$('#resultsTable tbody').html('<tr><td colspan="5"><span class="label label-important">'+
			'NO PAGES FOUND</span></td></tr>');
	},

	drawPage: function (pageNumb) {
		var numbStart = ((pageNumb-1) * ezCMSjs.numbShown);
        var numbEnd = numbStart + ezCMSjs.numbShown;
        if (numbEnd > ezCMSjs.results.length) numbEnd = ezCMSjs.results.length;
        $('#resultsTable tbody').empty();
		for(var i = numbStart; i < numbEnd; i++) {
			var rec = ezCMSjs.results[i], row, ico = 'remove', badge = 'important';
			row = '<td><input type="checkbox" data-id="'+rec.id+'"> '+(i+1)+'</td>';
			row += '<td><a target="_blank" href="pages.php?id='+rec.id+'">'+rec.pagename+'</a></td>';
			row += '<td><a target="_blank" href="'+rec.url+'">'+rec.url+'</a></td>';
			row += '<td class="rowlog"><i class="icon-question-sign"></i></td>';
			if (rec.published == '1') { ico = 'ok'; badge = 'success'; }
			row += '<td><span class="badge badge-'+badge+'"><i class="icon-'+ico+' icon-white"></i></span></td>';
			var tr = $('<tr></tr>').prop('id','row'+rec.id).data('idx',i)
				.html(row).appendTo('#resultsTable tbody');
			if (rec.status==2) ezCMSjs.setRowDone(tr, rec.log);
			else if(rec.status==1) tr.find('input').prop('checked', true);
			tr.find('input').click(function() {
				var idx = $(this).closest('tr').data('idx');
				if ($(this).is(":checked")) ezCMSjs.results[idx].status = 1;
				else ezCMSjs.results[idx].status = 0;
			});
		}
		$('#currpage').val(pageNumb);
	},

	goToPage: function(ele) {
		var newP, oldP = parseInt($('#currpage').val());
		if (ele.hasClass('next')) {
			newP = oldP+1;
			if (newP>ezCMSjs.numbPages) return false;
		} else if (ele.hasClass('prev')) {
			newP = oldP-1;
			if (newP<1) return false;
		} else if (ele.hasClass('first')) newP = 1;
		else if (ele.hasClass('last')) newP = ezCMSjs.numbPages;
		else return false;
		if (newP!==oldP) ezCMSjs.drawPage(newP);
	},	

	toggleChecked: function () {
		var check = false,
			status = 0,
			sel = $('#resultsTable tbody input[type="checkbox"]');
		if ($('#checkall').prop('checked')) {
			check = true;
			status = 1;
		}
		sel.prop('checked', check);
		sel.each(function() {
			var idx = $(this).closest('tr').data('idx');
			ezCMSjs.results[idx].status = status;
		});
	},

	toggleCheckedFull: function() {
		var status = 0;
		if ($('#checkfull').text()=='CHECK ALL') {
			$('#checkfull').text('UNCHECK ALL');
			status = 1;
		} else $('#checkfull').text('CHECK ALL');
		ezCMSjs.results.forEach(function(r) {
			if (r.status!=2) r.status = status
		});
		ezCMSjs.drawPage($('#currpage').val());
	},

}
ezCMSjs.init();