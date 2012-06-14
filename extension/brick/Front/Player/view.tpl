{% block header %}{% endblock %}
<div class='player-box' data='{"fileurl":"{{fileurl}}","showplayer":"{{showplayer}}"}'>
<script type="text/javascript"	src="<?=Class_Server::libUrl()?>/front/script/player.js"></script>

{% block header %}{% endblock %}
<div class='player-box' data='{"fileurl":"{{fileurl}}"}'>
	<div id="jquery_jplayer_1" class="jp-jplayer"></div>
		<div id="jp_container_1">
			<div class="jp-gui">
				<ul>
					<li><a class="jp-play" href="javascript:void(0);"></a></li>
					<li><a class="jp-pause" href="javascript:void(0);"></a></li>
					<li><a class="jp-mute" href="javascript:void(0);"></a></li>
				</ul>
				<div style="clear:both;"></div>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
				<ul>
					<li><a class="jp-repeat-off" href="javascript:void(0);"></a></li>
				</ul>
		</div>
	</div>
</div>
{% block footer %}{% endblock %}