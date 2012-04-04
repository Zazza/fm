<h2>Statistics</h2>

<h3>Total: {{ total.all_val }} {{ total.all_unit }}</h3>

<p style="margin: 10px 0">Users statistics:</p>

{% for key, val in users %}
<div class="well">

	<h4>{{ key }}</h4>
	
	<p style="margin: 10px 0 2px 0">{{ val.val }} {{ val.unit }} of {{ val.quota_val }} {{ val.quota_unit }}</p>
	
	<div style="border: 2px solid #888; height: 10px; width: 150px">
		<div style="overflow: hidden; text-align: left; width: {{ val.percent }}%">
		<img src="{{ registry.uri }}img/quota.png" style="position: relative; top: -5px" />
		</div>
	</div>

</div>
{% endfor %}