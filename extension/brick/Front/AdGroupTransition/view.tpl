{% block header %}{% endblock %}
<div class="transition-frame" data='{"delay":"{{ delay }}"}'>
	<div class="featured">
		<ul class="slides">
		{% for row in rowset %}
			<li class="slide">
				<div class="slide-image">
					<img src='{{ row.filename|outputImage }}'>
					<div class='slide_overlay'> </diy>                                                                                                                                                                                                                                                           '></div>
				</div>
				<div class='introducebanner'></div>
			</li>
		{% endfor %}	
		</ul>
	</div>
	<span class='slide_shadow'></span>
	<div class='switcher'>
	{% for row in rowset %}
		<div class='item'>
			<div class='wrap'>
				<span class='image'>
					<img src='{{ row.filename|outputImage }}' width='83px' height='83px'>
					<span class='slide_small'></span>
				</span>
				<div class='hover'></div>
			</div>
		</div>
	{% endfor %}	
	</div>
</div>
{% block footer %}{% endblock %}
