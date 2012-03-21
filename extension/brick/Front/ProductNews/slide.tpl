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
	<div class='slider' style='position: relative; overflow: hidden; height: {{ height }}px'>
		<ul style='position: relative;'>
		{% for row in rowset %}
		<li style='display: block; float: left; width: {{ width }}px; overflow: hidden;'>
		{% block row %}
			<div class="icon"><a href='/product-{{ row.id }}.shtml'><img src="{{row.introicon|outputImage}}" alt="{{row.title}}" /></a></div>
			<div class="title"><a href='/product-{{ row.id }}.shtml'>{{row.title}}</a></div>
			<div class="sku">型号：{{row.sku}}</div>
		{% endblock %}
		</li>
		{% endfor %} 
		</ul>
	</div>
	
	{% if numPage > 1 %}
	<div class='handler'>
		<ul>
			{% for i in range(1, numPage) %}
			<li style='cursor:pointer;'>{{ i }}</li>
			{% endfor %}
		</ul>
	</div>
	{% endif %}
</div>
{% block footer %}{% endblock %}