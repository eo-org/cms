{% block header %}{% endblock %}
<div id="slidedeck_frame" class="skin-slidedeck">
	<dl class="slidedeck">
		{% for row in rowset %}
			{% block row %}
				<dt>{{ row.label }}</dt>
				<dd><img src='{{ row.filename|outputImage }}'/></dd>
			{% endblock %}
		{% endfor %} 
	</dl>
</div>
<script type="text/javascript">
	$('.slidedeck').slidedeck();
</script>
{% block footer %}{% endblock %}
