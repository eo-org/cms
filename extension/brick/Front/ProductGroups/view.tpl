{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<div class='clear'></div>

{{ head.render()|raw }}
{% block footer %}{% endblock %}