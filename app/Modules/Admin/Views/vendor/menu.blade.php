<div id="nav-col">
    <section id="col-left" class="col-left-nano">
        <div id="col-left-inner" class="col-left-nano-content">
            <div id="user-left-box" class="clearfix hidden-sm hidden-xs">
                <img alt="" src="{!! Html::avatar() !!}">
                <div class="user-box">
                    <span class="name">
                        <br>
                        {!! Html::getStaff()->name !!}
                    </span>
                    <span class="status">
                        <i class="fa fa-circle"></i> Online
                    </span>
                </div>
            </div>
            {!! Html::getMenu() !!}
        </div>
    </section>
</div>