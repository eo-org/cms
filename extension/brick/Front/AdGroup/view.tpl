{% block header %}{% endblock %}
<ul>
{% block rowset %}
{% for row in rowset %}
	<li>
	{% block row %}
		<a href='{{row.link}}'>
			<img src='{{row.filename|outputImage}}' />
		</a>
		{% if showLabel == 'y' %}
		<a href='{{row.url}}'>{{ row.label }}</a>
		{% endif %}
		{% if showDescription == 'y' %}
		<span>{{ row.description }}</span>
		{% endif %}
	{% endblock %}
	</li>
{% endfor %}
{% endblock %}
</ul>

{% block footer %}{% endblock %}