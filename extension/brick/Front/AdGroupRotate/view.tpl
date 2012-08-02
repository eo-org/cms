{% block header %}{% endblock %}
<div class='image-group-rotateimage' data='{"delay": "{{ delay }}"}'>
 	<div class='rotateimage' style='z-index:-1'>
		<div class='bigimage'>
			{% for row in rowset %}
			<div style='position: absolute'>
				<a href='{{ row.url }}'>
						<img src='{{ row.filename|outputImage }}'/>
					</a>
			</div>
			{% endfor%}
		</div>
	</div>
</div>
{% block footer %}{% endblock %}