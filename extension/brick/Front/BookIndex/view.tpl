{% import "_loopitem.tpl" as item %}

{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}

{% for node in bookIndex %}
	{{ item.loop(node, bookAlias) }}
{% endfor %}

<div class='clear'></div>
{% block footer %}{% endblock %}