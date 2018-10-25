<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <!--        <div class="user-panel">-->
        <!--            <div class="pull-left image">-->
        <!--                <img src="-->
        <? //= $directoryAsset ?><!--/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>-->
        <!--            </div>-->
        <!--            <div class="pull-left info">-->
        <!--                <p>Alexander Pierce</p>-->
        <!---->
        <!--                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
        <!--            </div>-->
        <!--        </div>-->

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= backend\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => 'Menu', 'options' => ['class' => 'header']],
                    ['label' => 'Docker', 'icon' => 'fab fa-docker', 'url' => ['/docker'],
                        'items' => [
                            ['label' => 'Containers', 'icon' => 'fas fa-toolbox', 'url' => ['/docker/containers']],
                            ['label' => 'Images', 'icon' => 'fab fa-docker', 'url' => ['/docker/images']],
                            ['label' => 'Volumes', 'icon' => 'fas fa-database', 'url' => ['/docker/volumes']],
                        ]
                    ],
                    ['label' => 'Database', 'icon' => 'fas fa-database', 'url' => ['/database']],
                    ['label' => 'Gii', 'icon' => 'fas fa-code', 'url' => ['/gii']],
                    ['label' => 'Apps category', 'icon' => 'fas fa-list', 'url' => ['/category']],
                    ['label' => 'Apps', 'icon' => 'fas fa-desktop', 'url' => ['/apps']],
                    ['label' => 'Users', 'icon' => 'fas fa-users', 'url' => ['/user/admin/index']],
                ],
            ]
        ) ?>

    </section>

</aside>
