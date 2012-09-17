{% import "_loopitem.tpl" as item %}

{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<ul class='navi-popup'>
{% for node in naviDoc.naviIndex %}
	{{ item.loop(node) }}
{% endfor %}
</ul>
<div class='clear'></div>
{% block footer %}{% endblock %}