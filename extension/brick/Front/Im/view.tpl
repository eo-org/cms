{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<ul class='float-im'>
{% for qq in qqArr %}
	<li>
		<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={{ qq }}&site=qq&menu=yes">
			<img border="0" src="http://wpa.qq.com/pa?p=2:{{ qq }}:42" alt="点击这里给我发消息" title="点击这里给我发消息">
		</a>
	</li>
{% endfor %}
</ul>
{% block footer %}{% endblock %}

{% if display == 'float' %}
<script type='text/javascript'>
	var floatDiv = $('.float-im');
//	var closer = $("<div class='close-float'><a href='#'>关闭</a></div>");
//	closer.prepend(floatDiv);
//	closer.click(function(e) {
//		e.preventDefault();
//		floatDiv.remove();
//		return false;
//	});
	
	floatDiv.appendTo('body');
	floatDiv.css({'position':'absolute', 'top':'175px'});
	var rightPos = $('body').width() - floatDiv.width() - 20;
	rightPos+= 'px';
	floatDiv.css({'left':rightPos});
	
	$(window).scroll(function() {
		var topPos = $(window).scrollTop() + 75;
		floatDiv.animate(
			{top: topPos+"px" },
			{queue: false, duration: 350}
		);
	});
	$(window).resize(function() {
		var rightPos = $('body').width() - floatDiv.width() - 20;
		rightPos+= 'px';
		floatDiv.css({'left':rightPos});
	});
</script>
{% endif %}
