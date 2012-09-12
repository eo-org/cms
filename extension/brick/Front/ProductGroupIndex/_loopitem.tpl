{% macro loop(node, currentGroupItemId, isRoot) %}
{% spaceless %}
	{% if currentGroupItemId == node.id %}
    <li class='selected {{node.className}}'>
    {% else %}
    <li class='{{node.className}}'>
    {% endif %}
    	<a href='{{ node|url("product-list") }}'>{{ node.label }}</a>
    {% if node.children %}
        <ul>
        {% for childNode in node.children %}
            {{ _self.loop(childNode, currentGroupItemId) }}
        {% endfor %}
        </ul>
    {% endif %}
    </li>
{% endspaceless %}
{% endmacro %} 