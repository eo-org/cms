{% macro loop(node) %}
{% spaceless %}
    <li>
    	<a href='{{ node.link }}'>{{ node.label }}</a>
    {% if node.children %}
        <ul>
        {% for childNode in node.children %}
            {{ _self.loop(childNode) }}
        {% endfor %}
        </ul>
    {% endif %}
    </li>
{% endspaceless %}
{% endmacro %} 