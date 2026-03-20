<div id="ai-sidebar">
	<div class="ai-sidebar-header">
		<span class="ai-sidebar-close" id="ai-sidebar-close"><i class="icon-remove"></i></span>
		<span class="ai-sidebar-gear" id="ai-sidebar-gear"><i class="icon-cog"></i></span>
		<i class="icon-comment"></i> Synapse
	</div>

	<div id="synapse-view-unconfigured" style="display:none;">
		<div class="ai-sidebar-section">
			<h5><i class="icon-info-sign"></i> Not Configured</h5>
			<p>Synapse is not configured. Click the <i class="icon-cog"></i> gear icon above to enter your AI provider credentials.</p>
		</div>
	</div>

	<div id="synapse-view-chat" style="display:none;">
		<div id="synapse-messages"></div>
		<div id="synapse-input-area">
			<textarea id="synapse-input" rows="3" placeholder="Ask Synapse…"></textarea>
			<div id="synapse-input-actions">
				<button id="synapse-send" class="btn btn-primary btn-small">Send</button>
				<button id="synapse-clear" class="btn btn-small">Clear</button>
			</div>
		</div>
	</div>

	<div id="synapse-view-settings" style="display:none;">
		<form id="synapse-settings-form">
			<div class="synapse-field">
				<label for="synapse-provider">Provider</label>
				<select id="synapse-provider" name="provider">
					<option value="">— Select —</option>
					<option value="openai">OpenAI</option>
					<option value="anthropic">Anthropic</option>
					<option value="gemini">Google Gemini</option>
					<option value="custom">Custom / Self-hosted</option>
				</select>
			</div>
			<div class="synapse-field">
				<label for="synapse-apikey">API Key</label>
				<input type="password" id="synapse-apikey" name="apikey" placeholder="Paste your API key" autocomplete="off">
			</div>
			<div class="synapse-field synapse-custom-only" style="display:none;">
				<label for="synapse-model">Model</label>
				<input type="text" id="synapse-model" name="model" placeholder="e.g. gpt-4o">
			</div>
			<div class="synapse-field synapse-custom-only" style="display:none;">
				<label for="synapse-endpoint">Endpoint URL</label>
				<input type="text" id="synapse-endpoint" name="endpoint" placeholder="https://…/v1/chat/completions">
			</div>
			<div id="synapse-settings-actions">
				<button id="synapse-save" type="button" class="btn btn-primary btn-small">Save</button>
				<button id="synapse-cancel" type="button" class="btn btn-small">Cancel</button>
			</div>
		</form>
	</div>
</div>
