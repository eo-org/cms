{% import "_loopitem.tpl" as item %}

{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='brick-name'>{{ brickName }}</div>
{% endif %}
<ul class='navi-draw' data='{"naviWidth":"{{naviWidth}}","naviMargin":"{{naviMargin}}"}'>
{% for node in bookIndex %}
	{{ item.loop(node, bookAlias) }}
{% endfor %}
</ul>
<div class='clear'></div>
{% block footer %}{% endblock %}