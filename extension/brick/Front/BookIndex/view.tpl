{% import "_loopitem.tpl" as item %}

{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<ul>
{% for node in bookIndex %}
	{{ item.loop(node, bookAlias) }}
{% endfor %}
</ul>
<div class='clear'></div>
{% block footer %}{% endblock %}