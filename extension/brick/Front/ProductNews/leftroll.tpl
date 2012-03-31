{% block header %}{% endblock %}
{% block morelink %}<div class='more'><a href='/product-list-{{ groupId }}/page1.shtml'>MORE</a></div>{% endblock %}
<div class='title'></div>

<div class='image-group-slide' data='{
	"itemWidth":"{{ width }}",
	"height":"{{ height }}",
	"delay": "{{ delay }}",
	"numPerSlide": "{{ numPerSlide }}",
	"margin": "{{ margin }}"
}'>
	<div>
		<ul>
		{% for row in rowset %}
		<li>
		{% block row %}
			<div class="icon"><a href='/product-{{ row.id }}.shtml'><img src="{{row.introicon|outputImage}}" alt="{{row.title}}" /></a></div>
			<div class="title"><a href='/product-{{ row.id }}.shtml'>{{row.title}}</a></div>
			<div class="sku">型号：{{row.sku}}</div>
		{% endblock %}
		</li>
		{% endfor %} 
		</ul>
	</div>
</div>
{% block footer %}{% endblock %}