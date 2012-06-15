{% macro list(node) %}
{% spaceless %}
    <li>
    	<a href='{{ node.url }}'>{{ node.label }}</a>
    {% if node.children %}
        <ul>
        {% for childNode in node.children %}
            {{ _self.list(childNode) }}
        {% endfor %}
        </ul>
    {% endif %}
    </li>
{% endspaceless %}
{% endmacro %} 