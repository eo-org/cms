{% block header %}{% endblock %}
<div id="slidedeck_frame" class="skin-slidedeck">
	<dl class="slidedeck">
		{% for row in rowset %}
			{% block row %}
				<dt>{{row.title}}</dt>
				<dd><img src='{{ row.filename}}'/></dd>
			{% endblock %}
		{% endfor %} 
	</dl>
<script type="text/javascript">
	$('.slidedeck').slidedeck();
</script>
</div>
{% block footer %}{% endblock %}
