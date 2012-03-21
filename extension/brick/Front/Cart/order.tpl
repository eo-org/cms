{% block header %}{% endblock %}
{% block rowset %}
<table>
	<thead>
		<tr>
			<th>产品名</th>
			<th>单价</th>
			<th>数量</th>
			<th>小计</th>
		</tr>
	</thead>
	<tbody>
{% for item in itemList %}
		<tr>
			<td class='label'>{{item.info.label}}</td>
			<td class='price'>{{item.price}}</td>
			<td class='qty'>{{item.qty}}</td>
			<td class='item-subtotal'>{{item.qty * item.price}}</td>
		</tr>
{% endfor %}
	</tbody>
	<tfoot>
		<tr>
			<td colspan='3'>运费</td>
			<td>{{shippingPrice}}</td>
		</tr>
		<tr>
			<td colspan='3'>总价</td>
			<td>{{subtotal}}</td>
		</tr>
	</tfoot>
</table>
{% endblock %}
{% block footer %}{% endblock %}