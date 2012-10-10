{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}

<ul class='download'>
	{% for d in download %}
	<li><a target='_blank' href='{{d.urlname|outputImage}}' target='_blank'>{{ d.filename }}</a></li>
	{% endfor %}
</ul>

{% block footer %}{% endblock %}