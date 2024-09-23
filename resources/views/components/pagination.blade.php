<?php
for ($i = 1; $i <= $paginator->lastPage(); $i++) {
    if(!empty($sort_by)) {
        if($i > 1) {
            if(!empty($route_type)) {
                $url[$i] = route('listings_rt_order_page', ['sort_by' => $sort_by, 'route_type'=> $route_type, 'page' => $i]);
            }
            else {
                $url[$i] = route('listings_order_page', ['sort_by' => $sort_by, 'page'=> $i]);
            }
        }
        else {
            if(!empty($route_type)) {
                $url[$i] = route('listings_rt_order', ['sort_by' => $sort_by, 'route_type'=> $route_type]);
            }
            else {
                $url[$i] = route('listings_order', ['sort_by' => $sort_by]);
            }
        }
    }
    else {
        if($i > 1) {
            if(!empty($route_type)) {
                $url[$i] = route('listings_rt_page', ['route_type' => $route_type, 'page' => $i]);
            }
            else {
                $url[$i] = route('listings_page', ['page'=> $i]);
            }
        }
        else {
            if(!empty($route_type)) {
                $url[$i] = route('listings_rt', ['route_type'=> $route_type]);
            }
            else {
                $url[$i] = route('listings');
            }
        }
    }

    $url[$paginator->currentPage()] = '#';
}
?>
<ul class="pagination">
    <li class="page-item{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ $url[1] ?? '#' }}">{{ Lang::get('misc.previous') }}</a>
    </li>
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <li class="page-item{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
            <a class="page-link" href="{{ $url[$i] }}">{{ $i }}</a>
        </li>
    @endfor
    <li class="page-item{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ $url[$paginator->currentPage()+1] ?? '#' }}">{{ Lang::get('misc.next') }}</a>
    </li>
</ul>