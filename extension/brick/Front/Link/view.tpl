{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
{{ head.render()|raw }}
<div class='clear'></div>
{% block footer %}{% endblock %}