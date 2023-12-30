<div class="pagination-wrapper">
    <div class="pagination">
        <a class="prev page-numbers" {{$users->currentPage()> 1? 'href='.$users->previousPageUrl():'disabled'}} >السابق</a>
        @if ( $users->currentPage()== 1)
            <span aria-current="page" class="page-numbers current">{{$users->currentPage()}}</span>
            @if ($users->lastPage() >1)
                <a class="page-numbers" href="{{$users->nextPageUrl()}}">{{$users->currentPage() + 1}}</a>
                @if ($users->lastPage() >2)
                    <a class="page-numbers" href="{{$users->url(3)}}">{{$users->currentPage() + 2}}</a>
                @endif
            @endif
        @endif
        @if ($users->currentPage() > 1  &&  $users->lastPage() > 2 && $users->currentPage()!=  $users->lastPage() )
            <a class="page-numbers" href="{{$users->previousPageUrl()}}">{{$users->currentPage() - 1}}</a>
            <span aria-current="page" class="page-numbers current">{{$users->currentPage()}}</span>
            <a class="page-numbers" href="{{$users->nextPageUrl()}}">{{$users->currentPage() + 1}}</a>
        @endif
        @if ($users->currentPage() ==  $users->lastPage())
            @if ($users->lastPage()>1)
                @if ($users->lastPage()>2)
                    <a class="page-numbers" href="{{$users->previousPageUrl()}}">{{$users->currentPage() - 2}}</a>
                @endif
                <a class="page-numbers" href="{{$users->previousPageUrl()}}">{{$users->currentPage() - 1}}</a>
            @endif
            <span aria-current="page" class="page-numbers current">{{$users->currentPage()}}</span>
        @endif
      @if ($users->lastPage()> 3)
      <span aria-current="page" class="page-numbers ">...</span>
      <a class="page-numbers" href="{{$users->url($users->lastPage())}}">{{$users->lastPage()}}</a>
      @endif
      <a class="prev page-numbers" {{$users->lastPage()> $users->currentPage()? 'href='.$users->nextPageUrl():'disabled'}} >التالى</a>
    </div>
  </div>