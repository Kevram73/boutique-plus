<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>BOUTIQUE+</title>
		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="JSOFT Admin - Responsive HTML5 Template">
		<meta name="author" content="JSOFT.net">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->


		<!-- Vendor CSS -->
		<link rel="stylesheet" href="{{ asset('octopus/assets/vendor/bootstrap/css/bootstrap.css')}}" />
		<link rel="stylesheet" href="{{ asset('octopus/assets/vendor/font-awesome/css/font-awesome.css')}}" />
		<link rel="stylesheet" href="{{ asset('octopus/assets/vendor/magnific-popup/magnific-popup.css')}}" />
        <link rel="stylesheet" href="{{ asset('octopus/assets/vendor/select2/select2.css')}}" />



        <!-- Specific Page Vendor CSS -->



        <!-- Theme CSS -->
		<link rel="stylesheet" href="{{ asset('octopus/assets/stylesheets/theme.css')}}" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="{{ asset('octopus/assets/stylesheets/skins/default.css')}}" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="{{ asset('octopus/assets/stylesheets/theme-custom.css')}}">

		<!-- Head Libs -->
		<script src="{{ asset('octopus/assets/vendor/modernizr/modernizr.js')}}"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/>

        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>

        @yield('css')
	</head>
	<body>

		<section class="body">
            <!-- start: header -->
            <header class="header">
                <div class="logo-container">
                    <a class="logo">
                        <img src="image/IMG-2014.PNG" height="60" alt="BOUTIQUE+" />
                    </a>
                    <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                        <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                    </div>
                </div>

                <!-- start: search & user box -->
                <a class="btn btn-primary"><i class="fa fa-institution "></i>  <strong class="amount">{{Auth::user()->boutique->nom}}</strong></a>
                <span class="separator"></span>


                <div class="header-right">
                    <a class="btn btn-danger" role="menuitem" tabindex="-1" href="{{ route('logout') }}"  onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> {{ __('msg.logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a class="btn btn-primary" role="menuitem" tabindex="-1" href="#" data-lock-screen="true"><i class="fa fa-lock"></i>{{ __('msg.locking') }}</a>
                    @role('ADMINISTRATEUR')
                    <a class="btn btn-default" role="menuitem" tabindex="-1" href="/sets" ><i class="fa fa-cog"></i></a>
                    @endrole
                    @yield('layoutCaisse')

                    <span class="separator"></span>

                     <div id="userbox" class="userbox">
                          <a  data-toggle="dropdown">
                                <figure class="profile-picture">
                                    <img src="/octopus/assets/images/login.jpg" alt="Joseph Doe" class="img-circle" data-lock-picture="/octopus/assets/images/login.jpg" />
                                </figure>
                                 <div class="profile-info" data-lock-name="{{ Auth::user()->nom }} - {{ Auth::user()->prenom }}" data-lock-email=" {{ Auth::user()->email }}">
                                     <span class="name">{{ Auth::user()->nom }} - {{ Auth::user()->prenom }}</span>
                                     @role('SUPER ADMINISTRATEUR')
                                    <span class="text-danger ">SUPER ADMINISTRATEUR </span>
                                     @endrole
                                     @role('ADMINISTRATEUR')
                                    <span class="text-danger ">ADMINISTRATEUR </span>
                                     @endrole
                                     @role('CAISSIER')
                                    <span class="text-danger ">CAISSIER </span>
                                     @endrole
                                     @role('MAGASINIER')
                                    <span class="text-danger ">MAGASINIER </span>
                                     @endrole
                                </div>
                            </a>
                     </div>
                </div>
                <!-- end: search & user box -->
            </header>
      <!-- end: header -->
			@yield('contenu')

			<div class="inner-wrapper">

				<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">
					<div class="sidebar-header">
						<div class="sidebar-title">
							MENU
						</div>
						<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
							<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
						</div>
					</div>

					<div class="nano">
						<div class="nano-content">
							<nav id="menu" class="nav-main" role="navigation">
								<ul class="nav nav-main">
                                    @role('ADMINISTRATEUR')
									<li class="nav-active">
										<a href="{{route('bord')}}">
											<i class="fa  fa-dashboard (alias)" aria-hidden="true"></i>
											<span><TABLE>{{ Str::upper(__('msg.dashboard')) }}</TABLE></span>
										</a>
									</li>
									 @if (Auth::user()->boutique->is_central == 0)
                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa   fa-shopping-cart" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.thesales')) }}</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <li>
                                                <a href="{{route('ventes')}}">
                                                    {{ Str::upper(__('msg.sales')) }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('depenses')}}">
                                                    <i class="fa  fa-money" aria-hidden="true"></i>
                                                    <span>DEPENSES</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/listeglobal">
                                                    <i class="fa  fa-money" aria-hidden="true"></i>
                                                    <span>CAISSES</span>
                                                </a>
                                            </li>
                                            <!-- <li>-->
                                            <!--    <a href="{{route('prestations')}}">-->
                                            <!--        SERVICES-->
                                            <!--    </a>-->
                                            <!--</li> -->
                                            @if (Auth::user()->boutique->settings->where('tag', 'livraison_vente')->first() && Auth::user()->boutique->settings->where('tag', 'livraison_vente')->first()->pivot->is_active)
                                            <li>
                                                <a href="{{route('livraisons2')}}">
                                                    {{ Str::upper(__('msg.delivery')) }}
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </li>
                                    @endif



                                    @if ((Auth::user()->boutique->settings->where('tag', 'reglement')->first() && Auth::user()->boutique->settings->where('tag', 'reglement')->first()->pivot->is_active) || (Auth::user()->boutique->settings->where('tag', 'reglement_achat')->first() && Auth::user()->boutique->settings->where('tag', 'reglement_achat')->first()->pivot->is_active))
                                    @if (Auth::user()->boutique->is_central == 0)
                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa   fa-money" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.reglements')) }}</span>
                                        </a>
                                        <ul class="nav nav-children">

                                            @if (Auth::user()->boutique->settings->where('tag', 'reglement')->first() && Auth::user()->boutique->settings->where('tag', 'reglement')->first()->pivot->is_active)
                                            <li>
                                                <a href="{{route('reglementlist')}}">
                                                    {{ Str::upper(__('msg.sales')) }}
                                                </a>
                                            </li>
                                            @if (Auth::user()->boutique->is_central == 1)
                                            <li>
                                                <a href="{{route('reglementachatlist')}}">
                                                    FOURNISSEURS
                                                </a>
                                            </li>
                                            @endif

                                            @endif

                                        </ul>
                                    </li>
                                    @else
                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa   fa-money" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.reglements')) }}</span>
                                        </a>
                                        <ul class="nav nav-children">

                                            @if (Auth::user()->boutique->settings->where('tag', 'reglement')->first() && Auth::user()->boutique->settings->where('tag', 'reglement')->first()->pivot->is_active)
                                            @if (Auth::user()->boutique->is_central == 0)
                                            <li>
                                                <a href="{{route('reglementlist')}}">
                                                    {{ Str::upper(__('msg.sales')) }}
                                                </a>
                                            </li>
                                            @endif
                                            <li>
                                                <a href="{{route('reglementachatlist')}}">
                                                    FOURNISSEURS
                                                </a>
                                            </li>


                                            @endif

                                        </ul>
                                    </li>
                                    @endif


                                    @endif
                                    @if (Auth::user()->boutique->is_stock == 0)
                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa  fa-cubes" aria-hidden="true"></i>
                                            <span>STOCK</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <li>
                                                <a href="{{route('categories')}}">
                                                    CATEGORIES / {{ Str::upper(__('msg.families'))  }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('modeles')}}">
                                                    PRODUITS
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('transferts')}}">
                                                    TRANSFERTS
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('transferts')}}">
                                                    LIVRAISONS
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    @endif
                                    @if (Auth::user()->boutique->is_central == 1)

                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa   fa-truck" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.procurement')) }}</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <li>
                                                <a href="{{route('provisions')}}">
                                                    {{ Str::upper(__('msg.orders')) }}
                                                </a>
                                            </li>
                                            @if (Auth::user()->boutique->settings->where('tag', 'livraison_commande')->first() && Auth::user()->boutique->settings->where('tag', 'livraison_commande')->first()->pivot->is_active)
                                            <li>
                                                <a href="{{route('livraisons')}}">
                                                    {{ Str::upper(__('msg.delivery')) }}
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </li>
                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa  fa-bank" aria-hidden="true"></i>
                                            <span>BANQUE</span>
                                        </a>

                                          <ul class="nav nav-children">

                                            <li>
                                                <a href="{{route('banques')}}">

                                                    <i class="fa  fa-money" aria-hidden="true"></i>
                                                    <span>BANQUES</span>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{route('comptes')}}">
                                                    <i class="fa  fa-money" aria-hidden="true"></i>
                                                    <span>COMPTES</span>

                                                </a>
                                            </li>
                                            <li >
                                                <a href="/allversements">
                                                    <i class="fa  fa-money" aria-hidden="true"></i>
                                                    <span>VERSEMENT</span>

                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    @if (Auth::user()->boutique->is_central == 1)
                                    <li>
                                        <a href="{{route('reglementlistallshoop')}}">
                                            <i class="fa  fa-money" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.creances'))  }}</span>
                                        </a>
                                    </li>
                                    @endif

                                    @endif
                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa  fa-book" aria-hidden="true"></i>
                                            <span>RESULTAT</span>
                                        </a>
                                        <ul class="nav nav-children">


                                            <li >
                                                <a href="/situationsBoutiques">
                                                    <i class="fa  fa-bank" aria-hidden="true"></i>
                                                    <span>SITUATION BOUTIQUES</span>
                                                </a>
                                            </li>


                                            <li>
                                                <a href="{{route('resultat')}}">
                                                    <i class="fa  fa-book" aria-hidden="true"></i>
                                                    <span>{{ Str::upper(__('msg.results'))  }}</span>
                                                </a>
                                            </li>ro
                                        </ul>
                                    </li>



                                    @if (Auth::user()->boutique->settings->where('tag', 'inventaire')->first() && Auth::user()->boutique->settings->where('tag', 'inventaire')->first()->pivot->is_active)
                                    @if (Auth::user()->boutique->is_central == 0)
                                    <li>
                                        <a href="{{route('inventaire')}}">
                                            <i class="fa  fa-list-alt" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.inventory'))  }}</span>
                                        </a>
                                    </li>
                                    @endif
                                    @endif
                                    @if (Auth::user()->boutique->is_central == 0)
                                    <li>
                                        <a href="{{route('clients')}}">
                                            <i class="fa  fa-user"></i>
                                            <span>CLIENTS</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if (Auth::user()->boutique->is_central == 1)
                                    <li>
                                        <a href="{{route('fournisseurs')}}">
                                            <i class="fa  fa-user"></i>
                                            <span>{{ Str::upper(__('msg.providers')) }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('decharges')}}">
                                            <i class="fa  fa-file"></i>
                                            <span>DECHARGES</span>
                                        </a>
                                    </li>
                                    @endif





                                    @if (Auth::user()->boutique->settings->where('tag', 'charge')->first() && Auth::user()->boutique->settings->where('tag', 'charge')->first()->pivot->is_active)
                                    @if (Auth::user()->boutique->is_central == 0)
                                    <li>
                                       <a href="{{route('charges')}}">
                                          <i class="fa  fa-briefcase" aria-hidden="true"></i>
                                          <span>CHARGES</span>
                                       </a>
                                    </li>
                                    @endif

                                    @endif
                                    @if (Auth::user()->boutique->settings->where('tag', 'immobilisation')->first() && Auth::user()->boutique->settings->where('tag', 'immobilisation')->first()->pivot->is_active)
                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa fa-institution (alias)" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.assets'))  }}</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <li>
                                                <a href="{{route('amortissements')}}">
                                                    {{ Str::upper(__('msg.depreciation'))  }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('immobilisations')}}">
                                                    ARTICLES
                                                </a>
                                            </li>
                                        </ul>

                                    </li>
                                    @endif
                                    <li>
                                        <a href="{{route('historiques')}}">
                                            <i class="fa  fa-user" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.history'))  }}</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{route('utilisateurs')}}">
                                            <i class="fa fa-users"></i>
                                            <span>EMPLOYES</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('compte')}}">
                                            <i class="fa  fa-user" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.account'))  }}</span>
                                        </a>
                                    </li>
                                    @endrole
                                    @role('VENDEUR')
									<li class="nav-active">
										<a href="{{route('bord')}}">
											<i class="fa  fa-dashboard (alias)" aria-hidden="true"></i>
											<span><TABLE>{{ Str::upper(__('msg.dashboard')) }}</TABLE></span>
										</a>
									</li>
                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa   fa-shopping-cart" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.thesales')) }}</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <li>
                                                <a href="{{route('ventes')}}">
                                                    {{ Str::upper(__('msg.sales')) }}
                                                </a>
                                            </li>
                                            <!-- <li>
                                                <a href="{{route('prestations')}}">
                                                    SERVICES
                                                </a>
                                            </li> -->
                                            @if (Auth::user()->boutique->settings->where('tag', 'livraison_vente')->first() && Auth::user()->boutique->settings->where('tag', 'livraison_vente')->first()->pivot->is_active)
                                            <li>
                                                <a href="{{route('livraisons2')}}">
                                                    {{ Str::upper(__('msg.delivery')) }}
                                                </a>
                                            </li>
                                            @endif
                                            @if (Auth::user()->boutique->settings->where('tag', 'reglement')->first() && Auth::user()->boutique->settings->where('tag', 'reglement')->first()->pivot->is_active)
                                            <li>
                                                <a href="{{route('reglementlist')}}">
                                                    REGLEMENTS
                                                </a>
                                            </li>
                                            @endif



                                        </ul>
                                    </li>

                                    <li>
                                        <a href="{{route('clients')}}">
                                            <i class="fa  fa-user"></i>
                                            <span>CLIENTS</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('compte')}}">
                                            <i class="fa  fa-user" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.account'))  }}</span>
                                        </a>
                                    </li>
                                    @endrole
                                    @role('CAISSIER')
                                    <li class="nav-active">
										<a href="{{route('bord')}}">
											<i class="fa  fa-dashboard (alias)" aria-hidden="true"></i>
											<span><TABLE>{{ Str::upper(__('msg.dashboard')) }}</TABLE></span>
										</a>
									</li>
                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa   fa-shopping-cart" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.thesales')) }}</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <li>
                                                <a href="{{route('ventes')}}">
                                                    {{ Str::upper(__('msg.sales')) }}
                                                </a>
                                            </li>
                                            <!-- <li>
                                                <a href="{{route('prestations')}}">
                                                    SERVICES
                                                </a>
                                            </li> -->
                                            @if (Auth::user()->boutique->settings->where('tag', 'livraison_vente')->first()
&& Auth::user()->boutique->settings->where('tag', 'livraison_vente')->first()->pivot->is_active)
                                            <li>
                                                <a href="{{route('livraisons2')}}">
                                                    {{ Str::upper(__('msg.delivery')) }}
                                                </a>
                                            </li>
                                            @endif
                                            @if (Auth::user()->boutique->settings->where('tag', 'reglement')->first() && Auth::user()->boutique->settings->where('tag', 'reglement')->first()->pivot->is_active)
                                            <li>
                                                <a href="{{route('reglementlist')}}">
                                                    REGLEMENTS
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </li>

                                    <li>
                                        <a href="{{route('clients')}}">
                                            <i class="fa  fa-user"></i>
                                            <span>CLIENTS</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('compte')}}">
                                            <i class="fa  fa-user" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.account'))  }}</span>
                                        </a>
                                    </li>
                                    @endrole
                                    @role('MAGASINIER')
                                    <li class="nav-active">
										<a href="{{route('bord')}}">
											<i class="fa  fa-dashboard (alias)" aria-hidden="true"></i>
											<span><TABLE>{{ Str::upper(__('msg.dashboard')) }}</TABLE></span>
										</a>
									</li>
                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa   fa-shopping-cart" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.thesales')) }}</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <li>
                                                <a href="{{route('ventes')}}">
                                                    {{ Str::upper(__('msg.sales')) }}
                                                </a>
                                            </li>
                                            <!-- <li>
                                                <a href="{{route('prestations')}}">
                                                    SERVICES
                                                </a>
                                            </li> -->
                                            @if (Auth::user()->boutique->settings->where('tag', 'livraison_vente')->first() && Auth::user()->boutique->settings->where('tag', 'livraison_vente')->first()->pivot->is_active)
                                            <li>
                                                <a href="{{route('livraisons2')}}">
                                                    {{ Str::upper(__('msg.delivery')) }}
                                                </a>
                                            </li>
                                            @endif
                                            @if (Auth::user()->boutique->settings->where('tag', 'reglement')->first() && Auth::user()->boutique->settings->where('tag', 'reglement')->first()->pivot->is_active)
                                            <li>
                                                <a href="{{route('reglementlist')}}">
                                                    REGLEMENTS
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </li>

                                    <li>
                                        <a href="{{route('clients')}}">
                                            <i class="fa  fa-user"></i>
                                            <span>CLIENTS</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('compte')}}">
                                            <i class="fa  fa-user" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.account'))  }}</span>
                                        </a>
                                    </li>
                                    @endrole
                                    @role('SUPER ADMINISTRATEUR')
                                    <li class="nav-active">
                                        <a href="{{route('adminbord')}}">
                                            <i class="fa  fa-dashboard (alias)" aria-hidden="true"></i>
                                            <span><TABLE>{{ Str::upper(__('msg.dashboard'))  }}</TABLE></span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{route('boutiques')}}">
                                            <i class="fa  fa-institution" aria-hidden="true"></i>
                                            <span>BOUTIQUES</span>
                                        </a>
                                    </li>
                                     <li>
                                                <a href="{{route('resultatsuper')}}">
                                                    <i class="fa  fa-book" aria-hidden="true"></i>
                                                    <span>{{ Str::upper(__('msg.results'))  }}</span>
                                                </a>
                                            </li>
                                    <li>
                                        <a href="{{route('utilisateurs')}}">
                                            <i class="fa fa-users"></i>
                                            <span>USERS</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/caisses">
                                            <i class="fa  fa-money" aria-hidden="true"></i>
                                            <span>CAISSES</span>
                                        </a>
                                    </li>
                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa fa-institution" aria-hidden="true"></i>
                                            <span>INVENTAIRE</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <li>
                                                <a href="{{route('inventairesuper')}}">
                                                    PAR BOUTIQUE
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('inventaireglobal')}}">
                                                    GLOBAL
                                                </a>
                                            </li>
                                        </ul>
                                    </li>


                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa   fa-truck" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.history'))  }}</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <li>
                                                <a href="{{route('adminhistoriqueachat')}}">
                                                    DES ACHATS
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('adminhistoriquevente')}}">
                                                    DES VENTES
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('adminhistoriquedivers')}}">
                                                    DES DIVERS
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="{{route('compte')}}">
                                            <i class="fa  fa-user" aria-hidden="true"></i>
                                            <span>{{ Str::upper(__('msg.account'))  }}</span>
                                        </a>
                                    </li>

                                    @endrole
								</ul>
							</nav>
						</div>
					</div>
				</aside>
			</div>
		</section>


		<!-- Vendor -->
		<script src="{{ asset('octopus/assets/vendor/jquery/jquery.js')}}"></script>
		<script src="{{ asset('octopus/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js')}}"></script>
		<script src="{{ asset('octopus/assets/vendor/bootstrap/js/bootstrap.js')}}"></script>
		<script src="{{ asset('octopus/assets/vendor/nanoscroller/nanoscroller.js')}}"></script>
		<script src="{{ asset('octopus/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
		<script src="{{ asset('octopus/assets/vendor/magnific-popup/magnific-popup.js')}}"></script>
		<script src="{{ asset('octopus/assets/vendor/jquery-placeholder/jquery.placeholder.js')}}"></script>

        <script src="/vendor/numeral/numeral.min.js"></script>
        <script>
        // switch between locales
        numeral.locale('fr');
        </script>

		<!-- Specific Page Vendor -->

        <script src="{{ asset('octopus/assets/vendor/select2/select2.js')}}"></script>



        <!-- Theme Base, Components and Settings -->
		<script src="{{ asset('octopus/assets/javascripts/theme.js')}}"></script>

		<!-- Theme Custom -->
		<script src="{{ asset('octopus/assets/javascripts/theme.custom.js')}}"></script>

		<!-- Theme Initialization Files -->
		<script src="{{ asset('octopus/assets/javascripts/theme.init.js')}}"></script>
        @yield('js')

		<!-- Examples -->

@include('sweetalert::alert')
    </body>
</html>
