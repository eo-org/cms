{% block header %}{% endblock %}
<div class='search-result'>
	<ul>
	{% for row in rowset %}
		{% block row %}
		{% if type == 'product' %}
	    <li class='item-{{loop.index}}'>
	    	<a class='introicon' href='/product-{{ row.id }}.shtml' title='{{ row.label }}' target='_blank'>
				<img src='{{ row.introicon|outputImage }}' />
			</a>
	    	
	    	<a class='label' href='/product-{{ row.id }}.shtml' title='{{ row.label }}' target='_blank'>{{ row.label }}</a>
	    	
	    	<div class='price'>
	    	价格：{{ row.price }}
	    	</div>
	    </li>
	    {% else %}
	    <li class='item-{{loop.index}}'>
	    	<a class='label' href='/article-{{ row.id }}.shtml' title='{{ row.label }}' target='_blank'>{{ row.label }}</a>
	    </li>
	    {% endif %}
	    {% endblock %}
	{% endfor %}
	</ul>
</div>
{% block footerer %}{% endblock %}