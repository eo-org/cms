{% block header %}{% endblock %}
<div class='search-result'>
	{% if type == 'product' %}
	<ul class='product'>
	{% else %}
	<ul class='article'>
	{% endif %}
	
	{% for row in rowset %}
		{% if type == 'product' %}
		{% block productRow %}
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
	    {% else %}
	    {% block articleRow %}
	    <li class='item-{{loop.index}}'>
	    	<a class='label' href='/article-{{ row.id }}.shtml' title='{{ row.label }}' target='_blank'>{{ row.label }}</a>
	    </li>
	    {% endblock %}
	    {% endif %}
	{% endfor %}
	</ul>
</div>

{{ paginator|raw }}

{% block footerer %}{% endblock %}