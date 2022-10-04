<div class="card radius-5 border-top border-0 border-3 border-success">
    
    <div class="card-body">

        <h6 class="card-title mb-0">Latest TETFund News <small>(External)</small></h6>

        @if (isset($news_items))
        <div class="table-responsive">
            <div class="table-wrapper-scroll-y news-div-scrollbar">

                <table class="table no-margin">
                    <tbody>
                    @foreach ($news_items as $item)
                        <tr>
                            <td>                            
                                
                                <div class="col-lg-1">
                                    <img src="{!! $item->urlToImage !!}" width="70px" />
                                </div>

                                <div class="col-lg-10">
                                    <b>{!! $item->title !!}</b> <br/>
                                    <span style="font-size:100%;color:gray">
                                    {!! $item->description !!} &nbsp;
                                    <a href="{{ $item->url }}" target="parent">Read More </a>
                                    </span>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            
            </div>
        </div>
        @endif
    </div>

</div>