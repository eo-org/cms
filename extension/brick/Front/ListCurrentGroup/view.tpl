{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<div class='clear'></div>

<ul class='list'>
{% for row in rowset %}
	<li class='item-{{loop.index}}'>
		<a href='{% if row.link == null %}/article-{{ row.id }}.shtml{% else %}{{ row.link }}{% endif %}' title='{{ row.label }}'>{{ row.label }}</a>
	</li>
{% endfor %}
</ul>