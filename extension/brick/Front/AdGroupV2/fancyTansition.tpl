<div id='fancytransition' data='{
	"width":"{{ width }}",
	"height":"{{ height }}",
	"navigation": true,
	"delay": {{ delay }}
}'>
	{% for row in rowset %}
	
	<img alt="{{ row.description }}" src="{{ row.image|outputImage }}">
	<a href='{{ row.url }}'></a>
	{% endfor %}
</div>