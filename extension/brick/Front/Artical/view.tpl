{% if row != 'none' %}
	{% if showTitle == 'y' %}
	<div class='title'>{{ row.title }}</div>
	{% endif %}
	
	{% if showDate == 'y' %}
	<div class='date'>发布日期：
		{% if dateFormat == 'md' %}
			{{ row.created|date("m-d") }}
		{% elseif dateFormat == 'ymd' %}
			{{ row.created|date("Y-m-d") }}
		{% elseif dateFormat == 'ymdh' %}
			{{ row.created }}
		{% endif %}
	</div>
	{% endif %}
	
	{% if showHits == 'y' %}
	<div class='hits'>浏览次数：{{ row.hits }}</div>
	{% endif %}
	
	<div class='intro'>{{ row.introtext }}</div>
	<div class='content'>
		{{ row.fulltext|raw }}
	</div>
{% else %}
	<div class='error'>对不起，你寻找的文章不存在！</div>
{% endif %}

{% if attachmentRowset != null%}
<ul>
	{% for row in attachmentRowset %}
	<li><a href='{{ row.filename|outputImage }}'>{{ row.filename }}</a></li>
	{% endfor %}
</ul>
{% endif %}