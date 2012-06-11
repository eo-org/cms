{% block header %}{% endblock %}
<div class='brick-login'>
	{% if login == true %}
	欢迎登陆！
	<a class='index' href='/user/'>我的帐户</a>
	<a class='logout' href='/user/logout/'>退出</a>
	{% elseif linksOnly == false %}
	<form action="/user/login/" method="post" enctype="application/x-www-form-urlencoded">
			<dl class="login-form">
				<dt id="email-label">
					<label class="required" for="email">注册邮箱：</label>
				</dt>
				<dd id="email-element">
					<input id="email" type="text" value="" name="email">
				</dd>
				<dt id="password-label">
					<label class="required" for="password">密码：</label>
				</dt>
				<dd id="password-element">
					<input id="password" type="password" value="" name="password">
				</dd>
				<dt id="submit-label">&nbsp;</dt>
					<dd id="submit-element">
					<input id="submit" type="submit" value="登录" name="submit">
				</dd>
				<dd><a href="/user/register/">注册新帐户</a></dd>
				<dd><a class='password-reminder' href='/user/password-reminder/'>忘记密码</a></dd>
			</dl>
	</form>
	{% else %}
	<a class='login' href='/user/login/'>登录</a>
	<a class='register' href='/user/register/'>注册</a>
	<a class='password-reminder' href='/user/password-reminder/'>忘记密码</a>
	{% endif %}
</div>
{% block footer %}{% endblock %}