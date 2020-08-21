<nav class="navbar navbar-expand-lg navbar-dark lw-manage-navbar fixed-top  ">
    <a class="lw-show-process-action navbar-brand lw-manager-header-logo" href="<?=  route('home.page')  ?>"><?= __tr('__storeName__ Admin', [
        '__storeName__' => __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') )
    ]) ?> </a>
	<ul class="navbar-nav mr-auto">
		<li class="nav-item active">
			<a class="text-light nav-link" target="_blank" href="<?=  asset('/')  ?>" title="<?= __tr('Go to Public Home') ?>"> <strong><i class="fa fa-external-link"></i></strong></a>
		</li>
	</ul>

    <button class="navbar-toggler lw-navbar-toggle" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">

        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" ui-sref="dashboard" title="<?= __tr('Dashboard') ?>"><i class="fa fa-dashboard"></i> <?= __tr('Dashboard') ?></a>
            </li>

            <!-- Manage Locale Menu Block -->
            <li class="nav-item dropdown active">
                @if(config('locale.show_locale_menu') and getStoreSettings('show_language_menu'))
                    <a class="nav-link dropdown-toggle" href id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span><?php $availableLocale = config('locale.available');  echo array_key_exists(CURRENT_LOCALE, $availableLocale) ? $availableLocale[CURRENT_LOCALE] : $availableLocale[ env("LC_DEFAULT_LANG", "en_US") ]; ?></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        @foreach($availableLocale as $locale => $localeName)
                        <?php if($locale == CURRENT_LOCALE) /* continue; */ ?>
                            <a href="<?= route('locale.change', [$locale]) .'?redirectTo='.base64_encode(Request::fullUrl());  ?>" class="dropdown-item lw-locale-change-action" title="<?= $localeName ?>"><?=  $localeName  ?></a>
                        @endforeach
                    </div>
                @endif
            </li>
            <!-- Manage Locale Menu Block -->

            <!-- Manage User Profile Menu Block -->
            <li class="nav-item dropdown active">
                <a class="nav-link dropdown-toggle" href id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> <span ng-bind="manageCtrl.auth_info.profile.full_name"></span></a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" ui-sref-active="active-nav" href="<?=  route('cart.order.list')  ?>" class="dropdown-item" title="<?= __tr('My Orders') ?>"> <?= __tr('My Orders') ?></a>

                    @if(getStoreSettings('enable_wishlist'))
                    <a class="dropdown-item" href="<?=  route('product.my_wishlist.list')  ?>" class="dropdown-item" title="<?= __tr('My Wish List') ?>"><?= __tr('My Wish List') ?></a>
                    @endif

                    @if(getStoreSettings('enable_rating'))
                    <a class="dropdown-item" href="<?=  route('product.ratings.read.view')  ?>" class="dropdown-item" title="<?= __tr('My Ratings') ?>"><?= __tr('My Ratings') ?></a>
                    @endif

                    <a class="dropdown-item" href="<?=  route('user.address.list')  ?>" title="<?= __tr('My Address') ?>"> <?= __tr('My Address') ?></a>

                    <hr>
                    
                    <a class="dropdown-item" ui-sref="profile" class="dropdown-item" title="<?=  __tr('Profile')  ?>"><?=  __tr('Profile')  ?></a>

                    <a class="dropdown-item" ui-sref="changePassword" class="dropdown-item" title="<?=  __tr('Change Password')  ?>"><?=  __tr('Change Password')  ?></a>

                    <a class="dropdown-item" ui-sref="changeEmail" title="<?= __tr('Change Email') ?>"><?= __tr('Change Email') ?></a>

                    <a class="dropdown-item" href="<?=  route('user.logout')  ?>" class="dropdown-item" title="<?= __tr('Logout') ?>"><?= __tr('Logout') ?> <i class="fa fa-sign-out"></i></a>
                </div>
            </li>
            <!-- Manage User Profile Menu Block -->
        </ul>

    </div>
</nav>
