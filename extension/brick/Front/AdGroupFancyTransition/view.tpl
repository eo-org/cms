<div id='fancy-transition' data='{
	"width":"{{ width }}",
	"height":"{{ height }}",
	"navigation": true,
	"delay": {{ delay }}
}'>
	{% for row in rowset %}
	
	<img alt="{{ row.description }}" src="{{ row.filename|outputImage }}">
	<a href='{{ row.link }}'></a>
	{% endfor %}
</div>