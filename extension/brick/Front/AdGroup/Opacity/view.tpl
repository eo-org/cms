{% block header %}{% endblock %}
<div class='ad-group-opacity' data='{
	"thumbRotate":"{{ thumbRotate }}",
	"delay":"{{ delay }}"
}'>
	<div class='graphic'>
	    <ul>
			{% for row in rowset %}
			<li>
				<a href='{{row.link}}'>
					<img src='{{ row.filename|outputImage }}'/>
				</a>
				<div class='description'>
					{{row.description}}
				</div>
			</li>
			{% endfor%}
	    </ul>
    </div>
    <div class='handle'>
	    <ul>
			{% for row in rowset %}
				<li>
					<a href='javascript:void(0);'>
						<img src='{{ row.filename|outputImage }}'/>
					</a>
				</li>
			{% endfor %}
		</ul>
	</div>
</div>
{% block footer %}{% endblock %}