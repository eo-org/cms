{% block header %}{% endblock %}
{% block row %}
	<div class='graphic'>
		<img src='{{ row.introicon|outputImage }}' />
	</div>
	<div class='info'>
		<div class='title'>{{ row.label }}</div>
		<div class='price'>{{ row.price }}</div>
		<div class='introtext'>{{ row.introtext }}</div>
	</div>
	
	<div class='buy-button'>
		<div class='add-to-cart' product-id='{{ row.id }}'>购买</div>
	</div>
	
	<div class='clear'></div>
	
	<div class='fulltext'>{{ row.fulltext|raw }}</div>
{% endblock %}
{% block footer %}{% endblock %}
