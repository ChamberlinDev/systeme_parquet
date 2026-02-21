@extends('greffier.layout.app')
@section('content')


<div class="container">

    <h2 class="main-title">Toute les dossiers</h2>
    <hr>
    <div class="row stat-cards">
        <div class="col-md-6 col-xl-3">
            <article class="stat-cards-item">
                <div class="stat-cards-icon primary">
                    <i data-feather="folder" aria-hidden="true"></i>
                </div>
                <div class="stat-cards-info">

                    <p class="stat-cards-info__num">1478 286</p>
                    <p class="stat-cards-info__title"> Dossiers</p>
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
                    <p class="stat-cards-info__title">Dossiers en cours</p>
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
                    <p class="stat-cards-info__title">Dossiers Traités</p>
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
    <br>

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

                        <button class="form-btn primary-default-btn">
                          <a href="##" class="form-btn warning-default-btn">Voir</a>
                            <!-- <li><a href="##" class="form-btn warning-default-btn">Modifier</a></li>
                            <li><a href="##" class="form-btn danger-default-btn">Supprimer</a></li> -->
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>
        <div class="col-lg-3 text-end mb-3">
            <button type="submit" class="form-btn primary-default-btn">
                Ajouter un dossier
            </button>
        </div>
    </div>


    @endsection