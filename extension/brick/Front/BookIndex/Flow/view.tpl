{% import "_loopitem.tpl" as item %}

{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='brick-name'>{{ brickName }}</div>
{% endif %}
<ul class='navi-flow-data' data='{"style":"{{ style }}"}'>
{% for node in bookIndex %}
	{{ item.loop(node, bookAlias) }}
{% endfor %}
</ul>
<div class='clear'></div>
{% block footer %}{% endblock %}