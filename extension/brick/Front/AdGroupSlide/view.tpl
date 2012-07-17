{% block header %}{% endblock %}
<div class='image-group-slide' data='{
	"itemWidth":"{{ width }}",
	"delay": "{{ delay }}"
}'>
	<div class='slider' style='position: relative; overflow: hidden;'>
		<ul style='position: relative;'>
		{% for row in rowset %}
		<li style='display: block; float: left; width: {{ width }}px; overflow: hidden;'>
		{% block row %}
			<a href='{{ row.link }}'>
				<img src='{{ row.filename|outputImage }}'/>
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