{% block header %}{% endblock %}
<div class='image-group-slide' data='{
	"itemWidth":"{{ width }}",
	"height":"{{ height }}",
	"delay": "{{ delay }}",
	"numPerSlide": "{{ numPerSlide }}",
	"margin": "{{ margin }}"
}'>
	<div class='slider' style='position: relative; overflow: hidden; height: {{ height }}px;'>
		<ul style='position: relative;'>
		{% for row in rowset %}
		<li style='display: block; float: left; width: {{ width }}px; overflow: hidden;'>
		{% block row %}
			<a href='{{ row.url }}'>
				<img src='{{ row.image|outputImage }}'/>
			</a>
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