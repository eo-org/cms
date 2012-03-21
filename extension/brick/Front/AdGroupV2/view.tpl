{% block header %}{% endblock %}
<ul>
{% block rowset %}
{% for row in rowset %}
	<li>
	{% block row %}
		<a href='{{row.url}}'>
			<img src='{{row.image|outputImage}}' />
		</a>
		{% if showLabel == 'y' %}
		<a href='{{row.url}}'>{{ row.name }}</a>
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