{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<div class='link-dropdown-container'>
	{{ head.render()|raw }}
</div>
<div class='clear'></div>