{% block header %}{% endblock %}
<div class='image-group-slide' data='{
	"showauto":"{{ showAuto }}",
	"delay":"{{ delay }}"
}'>
    <div class='imageroll'>
		<div class='bigimage'>
			{% for row in rowset %}
			<div style='position: absolute'>
				<a href='/welcome.shtml'>
						<img src='{{ row.filename|outputImage }}'/>
				</a>
			</div>
			{% endfor%}
		</div>
    </div>
    <div class="alternate">
		<div id="leftalternate"></div>
		<div class='alternateBarea'>
			<div class='alternatearea'>
				<ul>
					{% for row in rowset %}
					<li style='float: left;'>
						<a href='javascript:void(0);'>
							<img src='{{ row.filename|outputImage }}'/>
						</a>
						<div class="alternatecontent">
							{{ row.description }} 
						</div>
					</li>
					{% endfor %}
				</ul>
			</div>
		</div>
		<div id="rightalternate"></div>
	</div>
 	
	
</div>
{% block footer %}{% endblock %}