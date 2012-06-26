{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<div class='link-multilevel-container'>
	{{ head.render()|raw }}
</div>
<div class='clear'></div>