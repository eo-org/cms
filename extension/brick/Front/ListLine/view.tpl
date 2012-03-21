{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<div class='clear'></div>

<ul>
{% block rowset %}
{% for row in rowset %}
	<li>
		<a href='/article-{{row.id}}.shtml' title='{{row.title}}'>
			{{row.title}}
		</a>
		<div class='date'>{{row.created|date('Y-m-d')}}</div>
	</li>
{% endfor %}
{% endblock %}
</ul>

{{paginator|raw}}