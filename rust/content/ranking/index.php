<section class="text-center">
	<div class="container">
		<div class="row">
			<div class="col-lg-9">
				<div class="row">
					<div class="col-sm-3">
						<div class="headline"><h3>Menu</h3></div>
						<ul id="menuRanking" class="list-unstyled">
							<li><button class="btn-block btn-primary btn margin-bottom-5" data-link="search">Search</button></li>
							<li><button class="btn-block btn-primary btn margin-bottom-5" data-link="topPlayers">Top Player</button></li>
							<li><button class="btn-block btn-primary btn margin-bottom-5" data-link="topGuilds">Top Guild</button></li>

						</ul>
					</div>
					
					<div class="col-sm-9">
						<div id="resultArea" class="active" style="display: block;">
							<div id="idSearch" style="display: block;">
								<div class="headline"><h3>Search</h3></div>
								<div class="clearfix"></div>
								<div class="row">
									<div class="col-sm-4 input-prepend margin-bottom-20">
										<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle col-md-12">
											<span id="idSelectValue">Charactername</span>
											<span class="caret"></span>
										</button>
										<ul id="idSelectButton" class="dropdown-menu">
											<li><a data-type="player">Charactername</a></li>
											<li><a data-type="guild">Guildname</a></li>
										</ul>
									</div>
									<div class="col-sm-4 input-prepend margin-bottom-20">
										<input type="text" value="" id="searchInput" placeholder="Search...." class="form-control search-query">
									</div>
									<div class="col-sm-4">
										<button onclick="doAjaxSearch();" class="btn btn-primary col-xs-12">Search</button>
									</div>
								</div>
							</div>
							<div id="result">
							
							<script>
							jQuery.get("./content/ranking/search.php",function(data)
							{
								jQuery('#result').html(data);
							});
							</script>
							
							</div>
						</div>
						<div class="ajaxLoader hidden"></div>
					</div>
				
			</div>

			<script type="text/javascript">
			var xhr,fastSearch;
			var searchType="Player";
			var saveRequest='./content/ranking/search.php';

			jQuery('#searchInput').keydown(function()
			{
				if(xhr&&xhr.readystate!=4)
					xhr.abort();
				
				window.clearTimeout(fastSearch);
				fastSearch=window.setTimeout(function(){doAjaxSearch();},500);
			});
				
			jQuery('button.btn.search').click(function(){doAjaxSearch();});

			jQuery('#searchType li').click(function()
			{
				searchType=jQuery(this).data('type');
				jQuery('#searchType li').removeClass('active');
				jQuery(this).addClass('active');
			});
				
			function doAjaxSearch()
			{
				sValue=jQuery('#searchInput').val();
				
				if(sValue.length<2)
					return;
				
				ajaxReload();
				jQuery('.ajaxLoader').removeClass('hidden');
				jQuery('#result').empty();
				saveRequest="./content/ranking/search.php?searchtype="+searchType+"&value="+sValue;
				
				xhr=jQuery.ajax({
					url:saveRequest,
					dataType:"html",
					success:function(data){
						jQuery('#result').html(data);
						jQuery('.ajaxLoader').addClass('hidden');
					},
					error:function(e){
						jQuery('#result').html('Bad Request');
						jQuery('.ajaxLoader').addClass('hidden');
					}
				});
			}

			jQuery(document).ready(function(){
				jQuery('#idSelectButton a').click(function(){
					searchType=jQuery(this).data('type');
					jQuery('#idSelectValue').text(jQuery(this).text());});
					
					jQuery('#menuRanking .btn').click(function(){
						sLink=jQuery(this).data('link');
						jQuery('#resultArea').hide('blind',function(){
						if(sLink=='search')
						{
							sLink=searchType;
							jQuery('#idSearch').css('display','block')
						}
						else
							jQuery('#idSearch').css('display','none')
						
						jQuery('.greenButton').removeClass('active');
						jQuery(this).addClass('active');
						ajaxReload();
						jQuery('.ajaxLoader').removeClass('hidden');
						jQuery('#result').empty().show('blind');
						saveRequest="./content/ranking/search.php?searchtype="+sLink+'&searchValue';
						
						xhr=jQuery.ajax({
							url:saveRequest,
							dataType:"html",
							success:function(data){
								jQuery('#result').html(data);
								jQuery('.ajaxLoader').addClass('hidden');
								jQuery('#resultArea').show('blind');
							},
							error:function(e){
								jQuery('#result').html('Bad Request');
								jQuery('.ajaxLoader').addClass('hidden');
								jQuery('#resultArea').show('blind');
							}
						});
					});
				});
			});
			</script>

			</div>
		</div>
	</div>
</section>
