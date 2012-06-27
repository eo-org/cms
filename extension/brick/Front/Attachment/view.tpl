{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}

<ul class='attachment'>
	{% for a in attachment %}
	<li><a href='http://storage.aliyun.com/public-misc/{{ a.filepath }}' target='_blank'>{{ a.filename }}</a></li>
	{% endfor %}
</ul>

{% block footer %}{% endblock %}