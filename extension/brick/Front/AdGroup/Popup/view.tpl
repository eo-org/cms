{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}

<ul class='brick-ad-group-popup'>
{% block rowset %}
{% for row in rowset %}
	<li>
	{% block row %}
		<a href='{{row.link}}' title='{{ row.label }}'>
			<img src='{{row.filename|outputImage}}' />
		</a>
		<div class='popup-item'>
			{{row.description}}
		</div>
	{% endblock %}
	</li>
{% endfor %}
{% endblock %}
</ul>

{% block footer %}{% endblock %}