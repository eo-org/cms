{% block header %}{% endblock %}
<div class='image-group-slide' data='{
	"itemWidth":"{{ width }}",
	"height":"{{ height }}",
	"delay": "{{ delay }}",
	"numPerSlide": "{{ numPerSlide }}",
	"margin": "{{ margin }}",
	"numSwitching":"{{ numSwitching }}"
}'>
 	<div class='rotateimage' style='z-index:-1'>
		<div class='bigimage'>
			{% for row in rowset %}
			<div style='width: {{ width }}px; height: {{ height }}px; position: absolute'>
				<a href='{{ row.url }}'>
						<img src='{{ row.image|outputImage }}'/>
					</a>
			</div>
			{% endfor%}
		</div>
	</div>
</div>
{% block footer %}{% endblock %}