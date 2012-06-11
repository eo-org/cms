{% block header %}{% endblock  %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<div class='clear'></div>

<ul>
{% block rowset %}
{% for row in rowset %}
	<li>
		<a href='/article-{{row.id}}.shtml' title='{{row.label}}'>
			<img src='{{ row.introicon|outputImage }}' /> 
		</a>
		<a href='/article-{{row.id}}.shtml' title='{{row.label}}'>
			{{row.label}}
		</a>
	</li>
{% endfor %}
{% endblock %}
</ul>

{{paginator|raw}}