<input $AttributesHTML />
<% if $Preview %>
	<div style="padding-top: 15px">
		<h4>$VideoTitle</h4>
		$Preview
		<h6 style="padding-top: 10px"><em><%t Axllent\\FormFields\\Forms\\VideoLinkField.TemplateError 'If you cannot see your video above then the URL is possibly incorrect.' %></em></h6>
	</div>
<% end_if %>
