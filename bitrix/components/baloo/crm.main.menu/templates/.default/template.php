			<div class="container-fluid">
				<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #1099dc;">
					<a class="navbar-brand" href="<?=$arResult['portalLink']?>" title="Назад на портал"><i class="fa fa-step-backward"></i></a>
					<a class="navbar-brand" href="<?=$arResult['crmLink']?>">CRM ЕЦН</a>
					
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav mr-auto">
							<li class="nav-item <?=($arResult['position']['stream'])?"active":""?>">
								<a class="nav-link" href="<?=$arResult['streamLink']?>">Лента</a>
							</li>
							<li class="nav-item <?=($arResult['position']['leads'])?"active":""?>">
								<a class="nav-link" href="<?=$arResult['leadsLink']?>">Лиды</a>
							</li>
              <li class="nav-item <?=($arResult['position']['clients'])?"active":""?>">
								<a class="nav-link" href="<?=$arResult['clientsLink']?>">Клиенты</a>
							</li>
							<li class="nav-item <?=($arResult['position']['requests'])?"active":""?>">
								<a class="nav-link" href="<?=$arResult['requestsLink']?>">Заявки</a>
							</li>
              <li class="nav-item <?=($arResult['position']['mortgage'])?"active":""?>">
								<a class="nav-link" href="<?=$arResult['mortgageLink']?>">Ипотека</a>
							</li>
							<li class="nav-item <?=($arResult['position']['marketing'])?"active":""?>">
								<a class="nav-link" href="<?=$arResult['marketingLink']?>">Маркетинг</a>
							</li>
							<li class="nav-item <?=($arResult['position']['contracts'])?"active":""?>">
								<a class="nav-link" href="<?=$arResult['contractsLink']?>">Договоры</a>
							</li>
							<li class="nav-item <?=($arResult['position']['deals'])?"active":""?>">
								<a class="nav-link" href="<?=$arResult['dealsLink']?>">Сделки</a>
							</li>
							<li class="nav-item <?=($arResult['position']['realty'])?"active":""?>">
								<a class="nav-link" href="<?=$arResult['realtyLink']?>">База города</a>
							</li>
						</ul>
						<form class="form-inline my-2 my-lg-0">
							<input class="form-control mr-sm-2" type="text" placeholder="искать лид, заявку, клиента и пр." aria-label="Search">
							<button class="btn btn-success my-2 my-sm-0" type="submit">Искать</button>
						</form>
					</div>
				</nav>
			</div>