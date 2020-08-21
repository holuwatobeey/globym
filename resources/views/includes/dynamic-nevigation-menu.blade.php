<?php 
$requestUrl = Request::url();
	function buildNevigationMenu($menuData, $requestUrl = '') {
        
		$pageNevMarkup = '';

		foreach ($menuData as $page) {

	        $title  = $page['name'];
	        $pageID = $page['id'];

	        $path 		= ($requestUrl) == $page['link'] ? 'active' : '';
	        $targetType = ($page['target'] == '_blank') ? '' : 'lw-show-process-action';

	        if (!empty($page['children'])) {

	            // this section contain children's 
	            $pageNevMarkup .='<li class="'.$path.'"><a class="'.$targetType.'" target="'.$page['target'].'" href="'.$page['link'].'">'.$title.'</a><ul>';

	            $pageNevMarkup .= buildNevigationMenu($page['children'], $requestUrl);

	            $pageNevMarkup .= "</ul></li>";

	        } else {

	        	if (isLoggedIn()) {

	        		if ($page['id'] !== getSysLinkId('login') 
	        			and $page['id'] !==  getSysLinkId('register')) {

                        $pageNevMarkup .= '<li class="'.$path.'"><a class="'.$targetType.'" href="'.$page['link'].'" title="'.$title.'" target="'.$page['target'].'">'.$title.'</a></li>';

	        		}

	        	} else {

                    if ($page['id'] ===  getSysLinkId('register')
                        and getStoreSettings('register_new_user')) {

                        $pageNevMarkup .= '<li class="'.$path.'" ng-if="!publicCtrl.auth_info.authorized"><a id="lwRegisterBtn" class="'.$targetType.'" href="'.$page['link'].'" title="'.$title.'" target="'.$page['target'].'">'.$title.'</a></li>';

                    } elseif ($page['id'] !==  getSysLinkId('register') and $page['id'] !==  getSysLinkId('login')) {

                        $pageNevMarkup .= '<li class="'.$path.'"><a class="'.$targetType.'" href="'.$page['link'].'" title="'.$title.'" target="'.$page['target'].'">'.$title.'</a></li>';

                    } elseif ($page['id'] ===  getSysLinkId('login') and Route::currentRouteName() !=  'user.login') {

                        $pageNevMarkup .= '<li class="'.$path.'" ng-if="!publicCtrl.auth_info.authorized"><a id="lwLoginBtn" ng-click="publicCtrl.showloginDialog()" class="'.$targetType.'" href title="'.$title.'" target="'.$page['target'].'">'.$title.'</a></li>';

                    }  elseif ($page['id'] ===  getSysLinkId('login') and Route::currentRouteName() ===  'user.login') {

                        $pageNevMarkup .= '<li class="'.$path.'"><a id="lwLoginBtn" class="'.$targetType.'" title="'.$title.'" style="pointer-events: none;">'.$title.'</a></li>';
                    }       				
	        	}	        	
	        }
	    }

	    return  $pageNevMarkup;
	}
?>

<?= buildNevigationMenu($menuData['navMenuData'], $requestUrl) ?>

@push('appScripts')
<script type="text/javascript">
    $('#lwLoginBtn').click(function(event){
        event.preventDefault();
    });
</script>
@endpush