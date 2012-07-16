{% block header %}{% endblock %}
<div class='image-group-slide' data='{
	"itemWidth":"{{ width }}",
	"height":"{{ height }}",
	"delay": "{{ delay }}",
	"numPerSlide": "{{ numPerSlide }}",
	"margin": "{{ margin }}",
	"numSwitching":"{{ numSwitching }}"
}'>

	<div class="alternate">
		<div id="leftalternate"></div>
		<div style=' position: absolute;'>
			<ul>
				{% for row in rowset %}
				<li style='float: left; width: {{ width }}px; height: {{ height }}px;'>
				{% block row %}
					<a href='{{ row.link }}'>
						<img src='{{ row.filename|outputImage }}'/>
					</a>
				{% endblock %}
				</li>
				{% endfor %}
			</ul>
		</div>
		<div id="rightalternate"></div>
	</div>
</div>
{% block footer %}{% endblock %}