{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}

<ul>
{% block rowset %}
{% for row in rowset %}
	{% block row %}
    <li class='item-{{loop.index}}'>
    	<a class='introicon' href='/product-{{ row.id }}.shtml' title='{{ row.label }}' target='_blank'>
			<img src='{{ row.introicon|outputImage }}' />
		</a>
    	
    	<a class='label' href='/product-{{ row.id }}.shtml' title='{{ row.label }}' target='_blank'>{{ row.label }}</a>
    	
    	<div class='price'>
    	价格：{{ row.price }}
    	</div>
    </li>
    {% endblock %}
{% endfor %}
{% endblock %}
</ul>

{{ paginator|raw }}

{% block footer %}{% endblock %}