{% block header %}{% endblock %}
{% block rowset %}
<table>
	<thead>
		<tr>
			<th>产品名</th>
			<th>单价</th>
			<th>数量</th>
			<th>小计</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
{% for item in itemList %}
		<tr>
			<td class='label'>{{item.info.label}}</td>
			<td class='price'>{{item.price}}</td>
			<td class='qty'><input class='item-qty' product-id='{{item.id}}' type='text' name='itemQty' value='{{item.qty}}' /></td>
			<td class='item-subtotal'>{{item.qty * item.price}}</td>
			<td class='action'><a class='item-delete-action' product-id='{{item.id}}' href='javascript:void(0);'>删除</a></td>
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
			<td id='cart-subtotal-displayer'>{{subtotal}}</td>
		</tr>
	</tfoot>
</table>
{% endblock %}

{% block orderlink%}
<div class='order-link'>
	<a href='/shop/order/'>去结算</a>
</div>
{% endblock %}
{% block footer %}{% endblock %}