{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<div class='clear'></div>

<ul class='list'>
{% for row in rowset %}
	<li class='item-{{loop.index}}'>
		<a href='{% if row.alias == null %} /article-{{ row.id }}.shtml {% else %} {{ row.alias }} {% endif %}' title='{{ row.title }}'>{{ row.title }}</a>
	</li>
{% endfor %}
</ul>