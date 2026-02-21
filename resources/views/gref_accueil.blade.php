@extends('greffier.layout.app')

@section('content')

<main class="main users chart-page" id="skip-target">
	<div class="container">
		<h2 class="main-title">Tableau de bord</h2>
		<div class="row stat-cards">
			<div class="col-md-6 col-xl-3">
				<article class="stat-cards-item">
					<div class="stat-cards-icon primary">
						<i data-feather="folder" aria-hidden="true"></i>
					</div>
					<div class="stat-cards-info">

						<p class="stat-cards-info__num">1478 286</p>
						<p class="stat-cards-info__title"> Total dossiers</p>
						<!-- <p class="stat-cards-info__progress">
                            <span class="stat-cards-info__profit success">
                                <i data-feather="trending-up" aria-hidden="true"></i>4.07%
                            </span>
                            Last month
                        </p> -->
					</div>
				</article>
			</div>
			<div class="col-md-6 col-xl-3">
				<article class="stat-cards-item">
					<div class="stat-cards-icon warning">
						<i data-feather="clock" aria-hidden="true"></i>
					</div>
					<div class="stat-cards-info">
						<p class="stat-cards-info__num">1478 </p>
						<p class="stat-cards-info__title">Total audiences</p>
						<!-- <p class="stat-cards-info__progress">
                            <span class="stat-cards-info__profit success">
                                <i data-feather="trending-up" aria-hidden="true"></i>0.24%
                            </span>
                            Last month
                        </p> -->
					</div>
				</article>
			</div>
			<div class="col-md-6 col-xl-3">
				<article class="stat-cards-item">
					<div class="stat-cards-icon purple">
						<i data-feather="check-circle" aria-hidden="true"></i>
					</div>
					<div class="stat-cards-info">
						<p class="stat-cards-info__num">1478</p>
						<p class="stat-cards-info__title">Total decisions</p>
						<!-- <p class="stat-cards-info__progress">
                            <span class="stat-cards-info__profit danger">
                                <i data-feather="trending-down" aria-hidden="true"></i>1.64%
                            </span>
                            Last month
                        </p> -->
					</div>
				</article>
			</div>
			<div class="col-md-6 col-xl-3">
				<article class="stat-cards-item">
					<div class="stat-cards-icon success">
						<i data-feather="archive" aria-hidden="true"></i>
					</div>
					<div class="stat-cards-info">
						<p class="stat-cards-info__num">1478 286</p>
						<p class="stat-cards-info__title">Dossiers archivés</p>
						<!-- <p class="stat-cards-info__progress">
                            <span class="stat-cards-info__profit warning">
                                <i data-feather="trending-up" aria-hidden="true"></i>0.00%
                            </span>
                            Last month
                        </p> -->
					</div>
				</article>
			</div>

		</div>

		<div class="row">
			<div class="col-lg-9">

				<div class="users-table table-wrapper">
					<table class="posts-table">
						<thead>
							<tr class="users-table-info">
								<th>
								Numero dossier
								</th>
								<th>Status</th>
								<th>Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<label class="users-table__checkbox">
										<input type="checkbox" class="check">
										<div class="categories-table-img">
											<picture>
												<source srcset="./img/categories/01.webp" type="image/webp"><img src="./img/categories/01.jpg" alt="category">
											</picture>
										</div>
									</label>
								</td>
								
								
								<td><span class="badge-pending">Pending</span></td>
								<td>17.04.2021</td>
								<td>
									<span class="p-relative">
										<button class="dropdown-btn transparent-btn" type="button" title="More info">
											<div class="sr-only">More info</div>
											<i data-feather="more-horizontal" aria-hidden="true"></i>
										</button>
										<ul class="users-item-dropdown dropdown">
											<li><a href="##">Edit</a></li>
											<li><a href="##">Quick edit</a></li>
											<li><a href="##">Trash</a></li>
										</ul>
									</span>
								</td>
							</tr>
						
						</tbody>
					</table>
				</div>
				<div class="chart">
					<canvas id="myChart" aria-label="Site statistics" role="img"></canvas>
				</div>
			</div>
			<div class="col-lg-3">
				<article class="customers-wrapper">
					<canvas id="customersChart" aria-label="Customers statistics" role="img"></canvas>

				</article>
				<article class="white-block">
					<div class="top-cat-title">
						<h3>Statistiques</h3>
					</div>
					<ul class="top-cat-list">
						<li>
							<a href="##">
								<div class="top-cat-list__title">
									Dossiers <span>890</span>
								</div>
								<div class="top-cat-list__subtitle">
									Dossiers traités <span class="purple">472</span>
								</div>
								<div class="top-cat-list__subtitle">
									Dossiers non traités <span class="purple">472</span>
								</div>
							</a>
						</li>
						<!-- <li>
							<a href="##">
								<div class="top-cat-list__title">
									Utilisateurs <span>8009</span>
								</div>
								<div class="top-cat-list__subtitle">
									Utilisateurs actifs <span class="blue">472</span>
								</div>
								<div class="top-cat-list__subtitle">
									Utilisateurs inactifs <span class="red">3537</span>
								</div>
							</a>
						</li> -->
						<li>
							<a href="##">
								<div class="top-cat-list__title">
									Audiences <span>895</span>
								</div>
								<div class="top-cat-list__subtitle">
									Audiences actives <span class="danger">472</span>
								</div>
							</a>
						</li>
						<li>
							<a href="##">
								<div class="top-cat-list__title">
									Decisions <span>875</span>
								</div>
								<div class="top-cat-list__subtitle">
									Decisions rendues <span class="success">472</span>
								</div>
							</a>
						</li>
					</ul>
				</article>
			</div>
		</div>
	</div>




	
</main>

@endsection