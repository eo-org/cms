{% block header %}{% endblock %}
<ul>
	<li class='info'>
		您的当前位置：
	</li>
	
	{% for step in trailArr %}
		{% if step.getId() == 0 %}
	<li class='home'><a href='/'>首页</a></li>
		{% else %}
	<li> &gt;&gt; <a href='{{step.getHref()}}'>{{step.getData('label')}}</a></li>
		{% endif %}
	{% endfor %}
	{% if tailMark != null %}
	<li> &gt;&gt; <span>{{tailMark}}</span></li>
	{% endif %}
</ul>
{% block footer %}{% endblock %}