<div>
@if(!empty($breadCrumb))

<!-- breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light">
        @if($breadCrumb['title'] != 'Home')
        <li class="breadcrumb-item"><a href="<?=  asset('/')  ?>" title="<?=  __tr('Home')  ?>"><?=  __tr('Home')  ?></a></li>
        @if(!empty($breadCrumb['parents']))

            @foreach($breadCrumb['parents'] as $parent)
                
                <li class="breadcrumb-item">
                    @if(isset($parent['target']) and !__isEmpty($parent['target']))

                        <a href="<?=  $parent['url']  ?>" target="<?=  $parent['target']  ?>" title="<?=  __trd($parent['name'])  ?>"><?=  __trd($parent['name'])  ?></a>
                    @else

                       <a href="<?=  $parent['url']  ?>" title="<?=  __trd($parent['name'])  ?>"><?=  __trd($parent['name'])  ?></a>
                       
                    @endif
                </li>
            @endforeach
        @endif
    @endif
        <li class="breadcrumb-item active" title="<?=  __trd($breadCrumb['title'])  ?>"><?=  __trd($breadCrumb['title'])  ?></li>
    </ol>
</nav>
<!-- /breadcrumb -->
@endif
</div>