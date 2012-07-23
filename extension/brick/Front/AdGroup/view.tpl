{% block header %}{% endblock %}
{% if showTitle == 'y' %}
	<div class='title'>{{ title }}</div>
{% endif %}
<ul>
{% block rowset %}
{% for row in rowset %}
	<li>
	{% block row %}
		<a href='{{row.link}}'>
			<img src='{{row.filename|outputImage}}' />
		</a>
		{% if showLabel == 'y' %}
		<a href='{{row.link}}'>{{ row.label }}</a>
		{% endif %}
	{% endblock %}
	</li>
{% endfor %}
{% endblock %}
</ul>

{% block footer %}{% endblock %}