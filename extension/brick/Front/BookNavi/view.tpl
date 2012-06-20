{% import "item.tpl" as item %}

{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}

{% for node in pageLinks %}
	{{ item.list(node, bookName) }}
{% endfor %}

<div class='clear'></div>
{% block footer %}{% endblock %}