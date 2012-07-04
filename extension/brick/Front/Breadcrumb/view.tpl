{% block header %}{% endblock %}
<ul>
	<li class='info'>
		您的当前位置：
	</li>
	<li class='home'><a href='/'>首页</a></li>
	{% for step in trailArr %}
		{% if step.link == ""%}
	<li> &gt;&gt; <a href='{{step.id|url(urlType)}}'>{{step.label}}</a></li>
		{% else %}
	<li> &gt;&gt; <a href='{{step.link}}'>{{step.label}}</a></li>
		{% endif %}
	{% endfor %}
	
	{% if tailMark != null %}
	<li> &gt;&gt; <span>{{tailMark}}</span></li>
	{% endif %}
</ul>
{% block footer %}{% endblock %}