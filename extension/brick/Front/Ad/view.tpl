<a href='{{row.url}}'>
	<img src='{{row.image|outputImage}}' />
</a>
{% if showLabel == 'show' %}
<a href='{{row.url}}'>{{ row.name }}</a>
{% endif %}

{% if showDescription == 'show' %}
<span>{{ row.description }}</span>
{% endif %}