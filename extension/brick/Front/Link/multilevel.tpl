{% block header %}{% endblock %}
{% if displayBrickName %}
<div class='title'>{{ title }}</div>
{% endif %}
<div class='link-multilevel-container'>
	{{ head.render()|raw }}
</div>
<div class='clear'></div>

<script language="javascript">
	$(document).ready(function(){
	var containerUl = $('.link-multilevel-container > ul');
	var handles = containerUl.children();
	handles.each(function(i, handle) {
		$(handle).children('ul').css({'display':'none'});
		$(handle).children('ul').children('li').children('ul').css({'display':'none'});
		$(handle).children('a').click(function(){
			if($(this).siblings().css("display") == 'none'){
				$(this).siblings().css({'display':'block'});
				$(this).siblings().children('li').children('a').click(function(){
					if($(this).siblings().css("display") == 'none'){
						$(this).siblings().css({'display':'block'});
					}else{
						$(this).siblings().css({'display':'none'});
					}
				})
			}else{
				$(this).siblings().css({'display':'none'});
			}
		});
	});  
})
</script>