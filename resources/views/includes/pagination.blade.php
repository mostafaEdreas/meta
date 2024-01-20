<div class="pagination-wrapper">
    <div class="pagination">
        <a class="prev page-numbers" {{$paginator->currentPage()> 1? 'href='.$paginator->previousPageUrl():'disabled'}} >السابق</a>
        @if ( $paginator->currentPage()== 1)
            <span aria-current="page" class="page-numbers current">{{$paginator->currentPage()}}</span>
            @if ($paginator->lastPage() >1)
                <a class="page-numbers" href="{{$paginator->nextPageUrl()}}">{{$paginator->currentPage() + 1}}</a>
                @if ($paginator->lastPage() >2)
                    <a class="page-numbers" href="{{$paginator->url(3)}}">{{$paginator->currentPage() + 2}}</a>
                @endif
            @endif
        @endif
        @if ($paginator->currentPage() > 1  &&  $paginator->lastPage() > 2 && $paginator->currentPage()!=  $paginator->lastPage() )
            <a class="page-numbers" href="{{$paginator->previousPageUrl()}}">{{$paginator->currentPage() - 1}}</a>
            <span aria-current="page" class="page-numbers current">{{$paginator->currentPage()}}</span>
            <a class="page-numbers" href="{{$paginator->nextPageUrl()}}">{{$paginator->currentPage() + 1}}</a>
        @endif
        @if ($paginator->currentPage() ==  $paginator->lastPage())
            @if ($paginator->lastPage()>1)
                @if ($paginator->lastPage()>2)
                    <a class="page-numbers" href="{{$paginator->previousPageUrl()}}">{{$paginator->currentPage() - 2}}</a>
                @endif
                <a class="page-numbers" href="{{$paginator->previousPageUrl()}}">{{$paginator->currentPage() - 1}}</a>
            @endif
            <span aria-current="page" class="page-numbers current">{{$paginator->currentPage()}}</span>
        @endif
      @if ($paginator->lastPage()> 3)
      <span aria-current="page" class="page-numbers ">...</span>
      <a class="page-numbers" href="{{$paginator->url($paginator->lastPage())}}">{{$paginator->lastPage()}}</a>
      @endif
      <a class="prev page-numbers" {{$paginator->lastPage()> $paginator->currentPage()? 'href='.$paginator->nextPageUrl():'disabled'}} >التالى</a>
    </div>
  </div>