<main class="main users chart-page" id="skip-target">
    <div class="container">
        <h2 class="main-title">Tableau de bord</h2>
        <p class="text-center">Ravi(e) de te retrouver {{ Auth::user()->name }}, bienvenu(e) sur le tableau de bord du système de gestion des dossiers judiciaires. </p><br>

        <div class="row stat-cards">
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                    <div class="stat-cards-icon primary">
                        <i data-feather="fas fa-people" aria-hidden="true"></i>
                    </div>
                    <div class="stat-cards-info">
                        <p class="stat-cards-info__num">1</p>
                        <p class="stat-cards-info__title">Utilisateurs</p>

                    </div>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                    <div class="stat-cards-icon warning">
                        <i data-feather="file" aria-hidden="true"></i>
                    </div>
                    <div class="stat-cards-info">
                        <p class="stat-cards-info__num">1478</p>
                        <p class="stat-cards-info__title">Total dossiers</p>
                    </div>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                    <div class="stat-cards-icon purple">
                        <i data-feather="file" aria-hidden="true"></i>
                    </div>
                    <div class="stat-cards-info">
                        <p class="stat-cards-info__num">147</p>
                        <p class="stat-cards-info__title">Total audiences</p>

                    </div>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                    <div class="stat-cards-icon success">
                        <i data-feather="feather" aria-hidden="true"></i>
                    </div>
                    <div class="stat-cards-info">
                        <p class="stat-cards-info__num">146</p>
                        <p class="stat-cards-info__title">Total décisions</p>

                    </div>
                </article>
            </div>
        </div>
        <hr>
        <div class="row">
           
            <div class="col-lg-3">
                <article class="white-block">
                    <div class="top-cat-title">
                        <h3>Statistiques</h3>
                    </div>
                    <ul class="top-cat-list">
                        <li>
                            <a href="##">
                                <div class="top-cat-list__title">
                                    Total utilisateurs <span>893</span>
                                </div>
                                <div class="top-cat-list__subtitle text-dark">
                                    Actif <span class="purple">72</span>
                                    Inactif <span class="danger">12</span>

                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="##">
                                <div class="top-cat-list__title">
                                    Total des dossiers <span>893</span>
                                </div>
                                <div class="top-cat-list__subtitle">
                                    Dossier archivé <span class="blue">72</span>
                                    Dossier non archivé <span class="green">821</span>

                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="##">
                                <div class="top-cat-list__title">
                                    Total audience et decision <span>82</span>
                                </div>
                                <div class="top-cat-list__subtitle">
                                    Instructions <span class="danger">72</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </article>
                <article class="customers-wrapper">
                    <canvas id="customersChart" aria-label="Customers statistics" role="img">
                    </canvas>
                </article>
            </div>
             <div class="col-lg-9">
                <div class="white-block">
                    <div class="users-table table-wrapper">
                       <h4 class="text-center">Aperçu des dossiers récents</h4><br> 
                       <hr>
                        <table class="posts-table">
                            <thead>
                                <tr class="users-table-info">
                                    <th>
                                        Dossier
                                    </th>
                                    <th>Numero dossier</th>
                                    <th>Partie</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>