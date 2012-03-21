{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
{% if msgArr.count > 0 %}
<ul class='msg'>
	{% for msg in msgArr %}
	<li>{{ msg }}</li>
	{% endfor %}
</ul>
{% endif %}
{{ form|raw }}
{% block footer %}{% endblock %}