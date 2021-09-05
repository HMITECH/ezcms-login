/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * JavaScript: Find and Replace Page js find.php
*
*/
var ezCMSjs = {

    numbStart: 1,
    numbShown: 20,
    numbPages: 0,

	init: function() {
		$("#top-bar li").removeClass('active');
		$("#top-bar > li:eq(2)").addClass('active');
		$('#frmfind').submit(function (e) {
			e.preventDefaults;
			ezCMSjs.findPages();
			return false;
		});
		$('#resultsTable').on("click", ".replaceOnelnk", function(e) {
			e.preventDefaults;
			ezCMSjs.replaceOne($(this), false);
			return false;
		});
		$('#resultsTable').on("click", "#checkall", ezCMSjs.toggleChecked);
		$('#findinDD a').click(function (e) {
			e.preventDefaults;
			ezCMSjs.findInChange($(this));
			return false;
		}).eq(0).click();
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
		$('#replace').click(ezCMSjs.runReplace);
	},

	runReplace: function () {
		ezCMSjs.pageids = ezCMSjs.results.filter(function(r){return(r.status==1)});
		if (!ezCMSjs.pageids.length) {
			alert('Please check the rows to replace in');
			$('#resultsTable tbody input[type="checkbox"]')[0].focus();
			return false;
		}
		ezCMSjs.setReplaceRunMode(true);
		ezCMSjs.processNext();
	},

	processNext: function () {
		if (!ezCMSjs.pageids.length) return false;
		var tr, idx, rec = ezCMSjs.pageids[0];
		$('#progressbox > i').text('Processing: '+rec.name);
		if ($('#findinTxt').val() == 'page') {
			tr = '#row'+rec.id+'-'+rec.block;
			idx =  ezCMSjs.results.findIndex(function(r) { 
				return ((r.id===rec.id)&&(r.block===rec.block)); 
			});
		} else {
			tr = '#row'+rec.name.split('.').join('-');
			idx =  ezCMSjs.results.findIndex(function(r) { 
				return r.name===rec.name; 
			});
		}
		var tablepage = Math.ceil((idx+1)/ezCMSjs.numbShown);
		if (!tablepage) tablepage = 1;
		if (parseInt($('#currpage').val())!=tablepage) ezCMSjs.drawPage(tablepage);
		ezCMSjs.replaceOne($(tr).find('.replaceOnelnk'), true);
	},

	setReplaceRunMode: function(isRunning) {
		if (isRunning) {
			var total = ezCMSjs.pageids.length;
			$('#progressbox').show();
			$('#exenavbar, #pager, #replace').hide();
			$('#progressbox h4 i').text($('#macro-select').data('macro'));
			$('#progressbox h4 .runrow').text('1');
			$('#progressbox h4 .totrow').text(total);
			$('#progressbox .bar').width( ((1/total)*100)+'%');
		} else {
			$('#progressbox').hide();
			$('#exenavbar, #pager, #replace').show();
		}
	},

	findInChange: function (ele) {
		$('#findinlbl').html('WHERE : ' + ele.html());
		$('#findinTxt').val(ele.data('loc'));
		$('#findinDD li').removeClass('active');
		ele.parent().addClass('active');
		if (ele.data('loc') == 'page')
			$('#filterDD').parent().parent().show();
		else 
			$('#filterDD').parent().parent().hide();
	},

	replaceOne: function (ele, isJob) {
		var params =  '&file='+ele.data('lnk');
		if ($('#findinTxt').val() == 'page') 
			params =  '&id='+ele.data('id')+'&block='+ele.data('block');
		var tr = ele.closest('tr');
		tr.addClass('error');
		ele.hide();
		$.post( 'find.php?replaceone'+params, $('#frmfind').serialize(), function(data) {
			ele.show();
			if (data.success) {
				ezCMSjs.results[tr.data('idx')].status = 2;
				ezCMSjs.setRowDone(tr);
				if (isJob) {
					ezCMSjs.pageids.shift();
					if (ezCMSjs.pageids.length) {
						var doneCnt = parseInt($('#progressbox h4 .runrow').text())+1,
							todoCnt = parseInt($('#progressbox h4 .totrow').text());
						$('#progressbox h4 .runrow').text(doneCnt);
						$('#progressbox .bar').width(((doneCnt/todoCnt)*100)+'%');
						setTimeout( function() {
							ezCMSjs.processNext();
						}, 500);
					} else ezCMSjs.setReplaceRunMode(false);
				}
			} else {
				alert('Error: '+ data.msg);
				if (isJob) ezCMSjs.setReplaceRunMode(false);
			}
		}, 'json').fail( function() {
			alert('Failed: The request failed.');
			if (isJob) ezCMSjs.setReplaceRunMode(false);
			ele.show();
		});
	},

	setRowDone: function (tr) {
		tr.removeClass('error').addClass('success');
		tr.find('input').after('<i class="icon-check"></i>').remove();
		ele = tr.find('.replaceOnelnk');
		ele.after('<label class="label label-success">REPLACED</label>');
		ele.remove();
	},

	findPages: function () {
		$('#frmfind input[type="submit"]').prop('disabled', true);
		$('#pager').hide();
		$('#resultsTable tbody').html(
			'<tr><td colspan="4">Working ... <img src="img/ajax-loader.gif"></td></tr>');
		$.post( 'find.php?fetchall', $('#frmfind').serialize(), function(data) {
			if (data.success) {
				ezCMSjs.setResultHeader();
				if (data.results.length) {
					// status 0-unchecked 1-checked 2-processed
					ezCMSjs.results = data.results.map(v => ({...v, status: 0}));
					$('#replace').show();
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

	setResultHeader: function () {
		var findinTxt = $('#findinTxt').val(),
			headerRow = '<th><input id="checkall" type="checkbox"> NO</th><th>NAME</th>';
		if (findinTxt != 'page') headerRow += '<th>ACTION</th>';
		else headerRow += '<th>PUBLISHED</th><th>BLOCK</th><th>ACTION</th>';
		$('#resultsTable tbody').empty();
		$('#resultsTable thead tr').empty().html(headerRow);
	},

	setNoResults: function () {
		$('#resultsTable tbody').html('<tr><td colspan="5"><span class="label label-important">'+
			'NO RESULTS FOUND</span></td></tr>');
	},

	drawPage: function (pageNumb) {
		var findinTxt = $('#findinTxt').val();
		var numbStart = ((pageNumb-1) * ezCMSjs.numbShown);
        var numbEnd = numbStart + ezCMSjs.numbShown;
        if (numbEnd > ezCMSjs.results.length) numbEnd = ezCMSjs.results.length;
        $('#resultsTable tbody').empty();
		for(var i = numbStart; i < numbEnd; i++) {
			var rec = ezCMSjs.results[i], row, ico = 'remove', badge = 'important';
			row = '<td><input type="checkbox" data-id="'+rec.id+'"> '+(i+1)+'</td>';
			row += '<td>'+rec.name+'</td>';
			if (findinTxt=='page') row += ezCMSjs.getPageRowHTML(rec);
			else row += ezCMSjs.getFileRowHTML(rec, findinTxt);
			var idsufix = rec.id+'-'+rec.block;
			if (findinTxt!='page') idsufix = rec.name.split('.').join('-');
			var tr = $('<tr></tr>').prop('id','row'+idsufix).data('idx',i)
				.html(row).appendTo('#resultsTable tbody');
			if (rec.status==2) ezCMSjs.setRowDone(tr);
			else if(rec.status==1) tr.find('input').prop('checked', true);
			tr.find('input').click(function() {
				var idx = $(this).closest('tr').data('idx');
				if ($(this).is(":checked")) ezCMSjs.results[idx].status = 1;
				else ezCMSjs.results[idx].status = 0;
			});
		}
		$('#currpage').val(pageNumb);
	},

	getPageRowHTML: function (rec) {
		var pagehash = '';
		var ico = 'remove', badge = 'important';
		if (rec.published == '1') { ico = 'ok'; badge = 'success'; }
		var html = '<td><span class="badge badge-'+badge+
			'"><i class="icon-'+ico+' icon-white"></i></span></td>';
		blockCap = rec.block;
		if (rec.block == 'maincontent' ) {
			pagehash = '#content';
			blockCap = 'Content';
		} else if (rec.block == 'headercontent' ) {
			pagehash = '#header';
			blockCap = 'Header';
		} else if (rec.block == 'footercontent' ) {
			pagehash = '#footers';
			blockCap = 'Footer';
		} else if (rec.block == 'head' ) {
			pagehash = '#head';
			blockCap = 'Page Head';
		} else if (rec.block == 'sidecontent' ) {
			pagehash = '#sidebar';
			blockCap = 'Aside #1';
		} else if (rec.block == 'sidercontent') {
			pagehash = '#siderbar';
			blockCap = 'Aside #2';
		}
		return html+'<td class="'+rec.block+'">'+blockCap+'</td><td><a target="_blank" href="'+rec.url+'">VIEW</a> | '+
			'<a target="_blank" href="pages.php?id='+rec.id+pagehash+'">EDIT</a>' +
			' | <a href="#" data-id="'+rec.id+'" data-block="'+rec.block+
			'" class="replaceOnelnk">REPLACE</a></td>';
	},

	getFileRowHTML: function (rec, findIn) {
		var lnk;
		if (findIn=='php') {
			lnk = 'layouts.php?show='+rec.name;
			if (rec.name == 'layout.php' ) lnk = 'layouts.php';
		} else if (findIn=='css') {
			lnk = 'styles.php?show='+rec.name;
			if (rec.inroot == 1 ) lnk = 'styles.php';
		} else if (findIn=='js') {
			lnk = 'scripts.php?show='+rec.name;
			if (rec.inroot == 1 ) lnk = 'scripts.php';
		} else if (findIn=='inc') {
			lnk = 'includes.php?show='+rec.name;
		}
		return '<td><a target="_blank" href="'+lnk+'">EDIT</a> '+
			'<a href="#" class="replaceOnelnk" data-lnk="'+
			rec.name+'">| REPLACE</a></td>';	
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