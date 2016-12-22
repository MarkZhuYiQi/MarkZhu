<!--轮播图-->
<!--<div class="carousel slide" id="myCar" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#myCar" data-slide-to="0" class="active"></li>
        <li data-target="#myCar" data-slide-to="1"></li>
        <li data-target="#myCar" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="item active">
            <img src="{$img_path}/carousel3.jpg" alt="3">
            <div class="carousel-caption">
                <h3 class="itemTitle">Lamborghini LP700</h3>
                <p></p>
            </div>
        </div>
        <div class="item">
            <img src="{$img_path}/carousel2.jpg" alt="2">
            <div class="carousel-caption">
                <h3 class="itemTitle">Audi R8</h3>
                <p></p>
            </div>
        </div>
        <div class="item">
            <img src="{$img_path}/carousel1.jpg" alt="1">
            <div class="carousel-caption">
                <h3 class="itemTitle">CIVIC</h3>
                <p></p>
            </div>
        </div>
    </div>
    &lt;!&ndash;<a href="#myCar" class="carousel-control left" data-slide="prev"></a>&ndash;&gt;
    &lt;!&ndash;<a href="#myCar" class="carousel-control right" data-slide="next"></a>&ndash;&gt;
    <a href="#myCar" class="carousel-control left" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
    <a href="#myCar" class="carousel-control right" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
</div>-->

<div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <!--警告：没有登录的提示-->
                <!--<div class="alert alert-danger">
                    please <a href="javascript:;" class="alert-link" id="login">login</a> or <a href="#" class="alert-link">register</a> a new account to acquire all the functions!
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>-->
                <section class="newsList">
                    <h3 class="newsTitle text-center">centOS插入环境变量</h3>
                    <div class="lgroup text-center">
                        <button class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-thumbs-up"></span> 108</button></button>
                        <button class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-tags"></span> 108</button>
                        <button class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-eye-open"></span> <span> {$info.readcount}</span></button>
                    </div>

                    <a href="#">
                        <img src="#"  alt="" class="img-responsive center-block newsImg thumbnail">
                    </a>
                    <p class="newsSummary">
                        <a href="#">
                            centos初学，添加到环境变量后其中的内容就可以直接在centOS全局去调用，在这类记录一些方法用于给centOS添加环境变量，
                            适用于不同的系统环境。
                        </a>
                    </p>
                    <a class="btn btn-danger read" href="#">阅读全文</a>
                    <div class="date text-center">
                        <span class="month">7月</span><span class="day">29</span>
                    </div>
                    <div class="bookmark">
                        <img src="{$img_path}/bookmark.png" alt="">
                    </div>
                </section>
                <section class="newsList">
                    <h3 class="newsTitle text-center">{$info.title}</h3>
                    <div class="lgroup text-center">
                        <button class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-thumbs-up"></span> 108</button></button>
                        <button class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-tags"></span> 108</button>
                        <button class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-eye-open"></span> <span> {$info.readcount}</span></button>
                    </div>

                    <a href="#">
                        <img src="#"  alt="" class="img-responsive center-block newsImg thumbnail">
                    </a>
                    <p class="newsSummary"><a href="#">{$info.info}</a></p>
                    <a class="btn btn-danger read" href="#">阅读全文</a>
                    <div class="date text-center">
                        <span class="month">7月</span><span class="day">29</span>
                    </div>
                    <div class="bookmark">
                        <img src="{$img_path}/bookmark.png" alt="">
                    </div>
                </section>



                <div class="page text-center">
                    {if $showPage}
                    {$nav}
                    {/if}
                </div>
            </div>
            <div class="col-md-4 rightView">
                {include file="sidebar.html"}
            </div>
        </div>
    </div>
</div>
