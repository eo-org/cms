{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<ul>
	<li><a href='/user/'>个人资料</a></li>
	<li><a href='/user/index/edit'>修改个人资料</a></li>
	<li><a href='/user/password'>修改密码</a></li>
	<li><a href='/user/index/logout'>退出后台</a></li>
</ul>
<div class='clear'></div>
{% block footer %}{% endblock %}