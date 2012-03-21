<div class="title">
	<a class='{% if group.id == currentId %}selected{% endif %}' href="/list-{{ group.id }}/page1.shtml">{{ group.label }}</a>
</div>
{% if group.hasChildren() %}
<ul>
{% for subgroup in group.getChildren() %}
	<li class='{% if subgroup.id == currentId %}selected{% endif %}'>
		<a class='{% if subgroup.id == currentId %}selected{% endif %}' href="/list-{{ subgroup.id }}/page1.shtml">{{ subgroup.label }}</a>
	</li>
{% endfor %}
</ul>
{% endif %}