{% macro list(node, bookName) %}
{% spaceless %}
    <li>
    {% if node.link %}
    	<a href='/{{bookName}}/{{ node.link }}.shtml'>{{ node.label }}</a>
    {% else %}
    	<a href='/{{bookName}}/{{ node.id }}.shtml'>{{ node.label }}</a>
    {% endif %}
    
    {% if node.children %}
        <ul>
        {% for childNode in node.children %}
            {{ _self.list(childNode, bookName) }}
        {% endfor %}
        </ul>
    {% endif %}
    </li>
{% endspaceless %}
{% endmacro %} 