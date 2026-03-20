(function($) {
	var CREDS_KEY = 'synapseCreds';
	var gearOpen = false;
	var GREETING = 'Hi! I\'m Synapse. How can I help you?';

	function loadCreds() {
		try {
			var raw = localStorage.getItem(CREDS_KEY);
			return raw ? JSON.parse(raw) : null;
		} catch(e) {
			return null;
		}
	}

	function saveCreds(obj) {
		localStorage.setItem(CREDS_KEY, JSON.stringify(obj));
	}

	function showView(name) {
		$('#synapse-view-unconfigured, #synapse-view-chat, #synapse-view-settings').hide();
		$('#synapse-view-' + name).show();
	}

	function initSidebar() {
		var creds = loadCreds();
		if (creds) {
			showView('chat');
			if ($('#synapse-messages').children().length === 0) {
				appendMsg('assistant', GREETING);
			}
		} else {
			showView('unconfigured');
		}
	}

	function setGearIcon(isRemove) {
		var $icon = $('#ai-sidebar-gear i');
		if (isRemove) {
			$icon.removeClass('icon-cog').addClass('icon-remove');
		} else {
			$icon.removeClass('icon-remove').addClass('icon-cog');
		}
	}

	function appendMsg(role, text) {
		var $div = $('<div></div>')
			.addClass('synapse-msg synapse-msg-' + role)
			.text(text);
		var $msgs = $('#synapse-messages');
		$msgs.append($div);
		$msgs.scrollTop($msgs[0].scrollHeight);
	}

	function resetChat() {
		$('#synapse-messages').empty();
		appendMsg('assistant', GREETING);
	}

	function updateCustomFields() {
		if ($('#synapse-provider').val() === 'custom') {
			$('.synapse-custom-only').show();
		} else {
			$('.synapse-custom-only').hide();
		}
	}

	// Sidebar open/close
	$('#btn-ai-sidebar').on('click', function() {
		var $sidebar = $('#ai-sidebar');
		var wasOpen = $sidebar.hasClass('open');
		$sidebar.toggleClass('open');
		if (!wasOpen) {
			initSidebar();
		}
		return false;
	});

	$('#ai-sidebar-close').on('click', function() {
		$('#ai-sidebar').removeClass('open');
		gearOpen = false;
		setGearIcon(false);
	});

	// Gear icon toggle
	$('#ai-sidebar-gear').on('click', function() {
		if (gearOpen) {
			gearOpen = false;
			setGearIcon(false);
			initSidebar();
		} else {
			gearOpen = true;
			setGearIcon(true);
			var creds = loadCreds() || {};
			$('#synapse-provider').val(creds.provider || '');
			$('#synapse-apikey').val(creds.apikey || '');
			$('#synapse-model').val(creds.model || '');
			$('#synapse-endpoint').val(creds.endpoint || '');
			updateCustomFields();
			showView('settings');
		}
	});

	$('#synapse-provider').on('change', updateCustomFields);

	// Settings: Save
	$('#synapse-save').on('click', function() {
		var provider = $('#synapse-provider').val();
		var apikey   = $('#synapse-apikey').val().trim();
		if (!provider) {
			alert('Please select a provider.');
			return;
		}
		if (!apikey) {
			alert('Please enter your API key.');
			return;
		}
		var creds = { provider: provider, apikey: apikey };
		if (provider === 'custom') {
			creds.model    = $('#synapse-model').val().trim();
			creds.endpoint = $('#synapse-endpoint').val().trim();
		}
		saveCreds(creds);
		gearOpen = false;
		setGearIcon(false);
		resetChat();
		showView('chat');
	});

	// Settings: Cancel
	$('#synapse-cancel').on('click', function() {
		gearOpen = false;
		setGearIcon(false);
		initSidebar();
	});

	// Chat: Send
	function sendMessage() {
		var text = $('#synapse-input').val().trim();
		if (!text) return;
		$('#synapse-input').val('');
		appendMsg('user', text);
		setTimeout(function() {
			appendMsg('assistant', '(Placeholder response – AI integration coming soon.)');
		}, 400);
	}

	$('#synapse-send').on('click', sendMessage);

	$('#synapse-input').on('keydown', function(e) {
		if (e.ctrlKey && e.which === 13) {
			sendMessage();
		}
	});

	// Chat: Clear
	$('#synapse-clear').on('click', function() {
		resetChat();
	});

})(jQuery);
