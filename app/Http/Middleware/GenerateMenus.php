<?php

namespace App\Http\Middleware;

use App\Trait\Menu;

class GenerateMenus
{
    use Menu;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle()
    {
        return \Menu::make('menu', function ($menu) {
            if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('demo_admin')) {
                $this->staticMenu($menu, ['title' =>  __('sidebar.main'), 'order' => 0]);
                $this->mainRoute($menu, [
                    'icon' => 'ph ph-squares-four',
                    'title' => __('sidebar.dashboard'),
                    'route' => 'backend.home',
                    'active' => ['app', 'app/dashboard'],
                    'order' => 0,
                ]);
            }

            $permissionsToCheck = ['view_setting', 'view_location','view_city','view_state','view_country','view_genres','view_movies','view_tvcategory','view_tvchannel','view_actor','view_director', 'view_tvshows', 'view_seasons',
        'view_episodes', 'view_plans','view_planlimitation', 'view_subscriptions','view_taxes', 'view_banners', 'view_media', 'view_video','view_livetv','view_castcrew', 'view_world', 'view_subscription', 'view_tvshow' ];
            collect($permissionsToCheck)->contains(fn ($permission) => auth()->user()->can($permission));

            $this->mainRoute($menu, [
                'icon' => 'ph ph-mask-happy',
                'route' => 'backend.genres.index',
                'title' => __('sidebar.genres'),
                'active' => ['app/genres'],
                'permission' => ['view_genres'],
                'order' => 0,
            ]);

            $this->mainRoute($menu, [
                'icon' => 'ph ph-film-strip',
                'route' => 'backend.filemanagers.index',
                'title' => __('sidebar.media'),
                'active' => ['app/filemanagers'],
                'permission' => ['view_media'],
                'order' => 0,
            ]);

            $this->mainRoute($menu, [
                'icon' => 'ph ph-film-strip',
                'route' => 'backend.movies.index',
                'title' => __('sidebar.movies'),
                'active' => ['app/movies'],
                'permission' => ['view_movies'],
                'order' => 0,
            ]);

            $tv_show = $this->parentMenu($menu, [
                'icon' => 'ph ph-television-simple',
                'title' => __('sidebar.tv_show'),
                'nickname' => 'tv_show',
                'permission' => ['view_tvshow'],
                'order' => 0,
            ]);

            $this->childMain($tv_show, [
                'title' => __('sidebar.tv_show'),
                'route' => 'backend.tvshows.index',
                'active' => ['app/tvshows'],
                'shortTitle' => 's',
                'order' => 0,
                'permission' => ['view_tvshows'],
                'icon' => 'ph ph-monitor-play',
            ]);

            $this->childMain($tv_show, [
                'title' => __('sidebar.seasons'),
                'route' => 'backend.seasons.index',
                'active' => ['app/seasons'],
                'shortTitle' => 's',
                'order' => 0,
                'permission' => ['view_seasons'],
                'icon' => 'ph ph-television',
            ]);

            $this->childMain($tv_show, [
                'title' => __('sidebar.episodes'),
                'route' => 'backend.episodes.index',
                'active' => ['app/episodes'],
                'shortTitle' => 's',
                'order' => 0,
                'permission' => ['view_episodes'],
                'icon' => 'ph ph-cards-three',
            ]);



            $this->mainRoute($menu, [
                'icon' => 'ph ph-video',
                'route' => 'backend.videos.index',
                'title' => __('sidebar.videos'),
                'active' => ['app/videos'],
                'permission' => ['view_videos'],
                'order' => 0,
            ]);






            // $show = $this->parentMenu($menu, [
            //     'icon' => 'ph ph-map-pin-line',
            //     'title' => __('sidebar.tv_shows'),
            //     'nickname' => 'tv_shows',
            //     'order' => 0,
            // ]);

            // $this->childMain($show, [
            //     'title' => __('sidebar.show'),
            //      'route' => 'backend.show.index',
            //      'active' => ['app/show'],
            //     'shortTitle' => 's',
            //     'order' => 0,
            //     'icon' => 'ph ph-map-pin-area',
            // ]);

            // $this->childMain($show, [
            //     'title' => __('sidebar.seasons'),
            //     'route' => 'backend.season.index',
            //     'active' => ['app/season'],
            //     'shortTitle' => 's',
            //     'order' => 0,
            //     'icon' => 'ph ph-map-pin-area',
            // ]);

            // $this->childMain($show, [
            //     'title' => __('sidebar.episodes'),
            //     'route' => 'backend.episode.index',
            //     'active' => '[app/episode]',
            //     'shortTitle' => 's',
            //     'order' => 0,
            //     'icon' => 'ph ph-map-pin-area',
            // ]);


            $live_tv = $this->parentMenu($menu, [
                'icon' => 'ph ph-screencast',
                'title' => __('sidebar.live_tv'),
                'nickname' => 'live_tv',
                'permission' => ['view_livetv'],
                'order' => 0,
            ]);

            $this->childMain($live_tv, [
                'title' => __('sidebar.tv_category'),
                'route' => 'backend.tv-category.index',
                'active' => ['app/tv-category'],
                'shortTitle' => 's',
                'order' => 0,
                'permission' => ['view_tvcategory'],
                'icon' => 'ph ph-circles-three-plus',
            ]);

            $this->childMain($live_tv, [
                'title' => __('sidebar.tv_channel'),
                'route' => 'backend.tv-channel.index',
                'active' => ['app/tv-channel'],
                'shortTitle' => 's',
                'order' => 0,
                'permission' => ['view_tvchannel'],
                'icon' => 'ph ph-polygon',
            ]);

            $cast = $this->parentMenu($menu, [
                'icon' => 'ph ph-users',
                'title' => __('sidebar.cast'),
                'nickname' => 'cast',
                'permission' => ['view_castcrew'],
                'order' => 0,
            ]);

            $this->childMain($cast, [
                'title' => __('sidebar.actors'),
                'route' => ['backend.castcrew.index','type' => 'actor'],
                'active' => ['app/castcrew/actor'],
                'shortTitle' => 's',
                'order' => 0,
                'permission' => 'view_actor',
                'icon' => 'ph ph-user-focus',
            ]);

            $this->childMain($cast, [
                'title' => __('sidebar.directors'),
                'route' => ['backend.castcrew.index', 'type' => 'director'],
                'active' => ['app/castcrew/director'],
                'shortTitle' => 's',
                'order' => 0,
                'permission' => 'view_director',
                'icon' => 'ph ph-user-circle-gear',
            ]);

            // $this->mainRoute($menu, [
            //     'icon' => 'ph ph-devices',
            //     'title' => __('sidebar.subscription'),
            //     'route' => 'backend.subscriptions.index',
            //     'active' => ['app/permission-role'],
            //     'order' => 0,
            // ]);


            $show = $this->parentMenu($menu, [
                'icon' =>  'ph ph-currency-circle-dollar',
                'title' => __('sidebar.subscription'),
                'nickname' => 'subscription',
                'permission' => 'view_subscription',
                'order' => 0,
            ]);

            // $this->childMain($show, [
            //     'title' => __('sidebar.subscribe_user'),
            //      'route' => 'backend.show.index',
            //      'active' => ['app/show'],
            //     'shortTitle' => 's',
            //     'order' => 0,
            //     'icon' => 'ph ph-map-pin-area',
            // ]);

            $this->childMain($show, [
                'title' => __('sidebar.plan'),
                 'route' => 'backend.plans.index',
                 'active' => ['app/plans'],
                'shortTitle' => 'p',
                'permission' => ['view_plans'],
                'order' => 0,
                'icon' => 'ph ph-map-pin-area',
            ]);


            $this->childMain($show, [
                'title' => __('sidebar.plan_limits'),
                'route' => 'backend.planlimitation.index',
                'active' => ['app/planlimitation'],
                'shortTitle' => 's',
                'order' => 0,
                'permission' => ['view_planlimitation'],
                'icon' => 'ph ph-map-pin-area',
            ]);


            $this->childMain($show, [
                'title' => __('sidebar.subscriptions'),
                'route' => 'backend.subscriptions.index',
                'active' => ['app/subscription'],
                'shortTitle' => 's',
                'order' => 0,
                'permission' => ['view_subscriptions'],
                'icon' => 'ph ph-map-pin-area',
            ]);
            $this->childMain($show, [
                'title' => __('sidebar.plan_expire'),
                'route' => ['backend.users.index','type'=>'soon-to-expire'],
                'shortTitle' => 'se',
                'active' => ['app/users?type=soon-to-expire'],
                'icon' => 'fa-solid fa-user',
                'order' => 0,
            ]);


            $world = $this->parentMenu($menu, [
                'icon' =>  'ph ph-devices',
                'title' => __('sidebar.world'),
                'nickname' => 'world',
                'permission' => ['view_world'],
                'order' => 0,
            ]);
            $this->childMain($world, [
                'title' => __('sidebar.city'),
                 'route' => 'backend.city.index',
                 'active' => ['app/city'],
                'shortTitle' => 'p',
                'order' => 0,
                'permission' => ['view_city'],
                'icon' => 'ph ph-map-pin-area',
            ]);
            $this->childMain($world, [
                'title' => __('sidebar.state'),
                 'route' => 'backend.state.index',
                 'active' => ['app/state'],
                'shortTitle' => 'p',
                'order' => 0,
                'permission' => ['view_state'],
                'icon' => 'ph ph-map-pin-area',
            ]);

            $this->childMain($world, [
                'title' => __('sidebar.country'),
                'route' => 'backend.country.index',
                'active' => ['app/country'],
                'shortTitle' => 's',
                'order' => 0,
                'permission' => ['view_country'],
                'icon' => 'ph ph-map-pin-area',
            ]);

            $this->mainRoute($menu, [
                'icon' => 'ph ph-user',
                'title' => __('sidebar.user'),
                'route' => 'backend.users.index',
                'active' => ['app/permission-role'],
                'order' => 0,
            ]);

            // $this->mainRoute($menu, [
            //     'icon' => 'ph ph-devices',
            //     'title' => __('sidebar.trancation'),
            //     'route' => 'backend.coupons.index',
            //     'active' => ['app/permission-role'],
            //     'order' => 0,
            // ]);

            // $this->mainRoute($menu, [
            //     'icon' => 'ph ph-devices',
            //     'title' => __('sidebar.coupon'),
            //     'route' => 'backend.coupons.index',
            //     'active' => ['app/coupons'],
            //     'order' => 0,
            // ]);

            // $this->mainRoute($menu, [
            //     'icon' => 'fa-light fa-percent',
            //     'title' => __('sidebar.currency'),
            //     'route' => 'backend.currencies.index',
            //     'active' => ['app/currencies'],
            //     'order' => 0,
            // ]);
            // $this->mainRoute($menu, [
            //     'icon' => 'fa-light fa-percent',
            //     'title' => __('sidebar.coupon'),
            //     'route' => 'backend.coupons.index',
            //     'active' => ['app/coupons'],
            //     'order' => 0,
            // ]);
            $this->mainRoute($menu, [
                'icon' => 'fa-light fa-percent',
                'title' => __('sidebar.tax'),
                'route' => 'backend.taxes.index',
                'active' => ['app/taxes'],
                'permission' =>['view_taxes'],
                'order' => 0,
            ]);

            $this->mainRoute($menu, [
                'icon' => 'fa-light fa-clipboard-list',
                'title' => __('sidebar.page'),
                'route' => 'backend.pages.index',
                'active' => ['app/pages'],
                'order' => 0,
            ]);

            $this->mainRoute($menu, [
                'icon' => 'fa-light fa-flag',
                'title' => __('sidebar.app_banner'),
                'route' => 'backend.banners.index',
                'active' => ['app/banners'],
                'permission' =>['view_banners'],
                'order' => 0,
            ]);


            $this->mainRoute($menu, [
                'icon' => 'fa-brands fa-intercom',
                'title' => __('sidebar.cont'),
                'route' => 'backend.constants.index',
                'active' => ['app/constants'],
                'order' => 0,
            ]);
            
            $mobile_setting = $this->parentMenu($menu, [
                'icon' => 'ph ph-map-pin-line',
                'route' => '',
                'title' => __('sidebar.mobile_setting'),
                'nickname' => 'mobile_setting',
                'order' => 0,
            ]);
            $this->childMain($mobile_setting, [
                'icon' => 'ph ph-gear',
                'title' => __('sidebar.dashboard_setting'),
                'route' => 'backend.mobile-setting.index',
                'active' => 'app/mobile-etting',
                'permission' => ['view_setting'],
                'order' => 0,
            ]);
            //mm
            $this->childMain($mobile_setting, [
                'icon' => 'ph ph-gear-six',
                'title' => __('sidebar.App_configuration'),
                'route' => 'backend.AppConfig.index',
                'active' => 'app/appconfig',
                'order' => 0,
            ]);


            $system = $this->parentMenu($menu, [
                'icon' => 'ph ph-airplay',
                'route' => '',
                'title' => __('sidebar.system'),
                'nickname' => 'system',
                'order' => 0,
            ]);

            $notification = $this->parentMenu($menu, [
                'icon' => 'ph ph-bell',
                'title' => __('notification.title'),
                'nickname' => 'notifications',
                'permission' => ['view_notification'],
                'order' => 0,
            ]);

            $this->childMain($notification, [
                'icon' => 'ph ph-list-bullets',
                'title' => __('sidebar.notification_list'),
                'route' => 'backend.notifications.index',
                'shortTitle' => 'Li',
                'active' => 'app/notifications',
                'permission' => ['view_notification'],
                'order' => 0,
            ]);
                   $this->childMain($notification, [
                    'icon' => 'ph ph-layout',
                    'title' => __('notification.template'),
                    'route' => 'backend.notification-templates.index',
                    'shortTitle' => 'TE',
                    'permission' => ['view_notification_template'],
                    'order' => 0,
                ]);

            // $this->childMain($system, [
            //     'title' => __('sidebar.app_banner'),
            //     'route' => 'backend.coupons.index',
            //     'active' => 'app/city',
            //     'shortTitle' => 'AB',
            //     'order' => 0,
            //     'icon' => 'ph ph-map-pin-area',
            // ]);
            // $this->childMain($system, [
            //     'title' => __('sidebar.page'),
            //     'route' => 'backend.coupons.index',
            //     'active' => 'app/city',
            //     'shortTitle' => 'SP',
            //     'order' => 0,
            //     'icon' => 'ph ph-map-pin-area',
            // ]);

           
            
            $this->childMain($system, [
                'icon' => 'ph ph-gear-six',
                'title' => __('sidebar.settings'),
                'route' => 'backend.settings.general',
                'active' => 'app/setting/general-setting',
                // 'permission' => ['view_setting'],
                'order' => 0,
            ]);

            $this->childMain($system, [
                'icon' => 'ph ph-faders',
                'title' => __('sidebar.access_control'),
                'route' => 'backend.permission-role.list',
                'active' => ['app/permission-role'],
                'order' => 10,
            ]);




            // Access Permission Check
            $menu->filter(function ($item) {
                if ($item->data('permission')) {
                    if (auth()->check()) {
                        if (\Auth::getDefaultDriver() == 'admin') {
                            return true;
                        }
                        if (auth()->user()->hasAnyPermission($item->data('permission'), \Auth::getDefaultDriver())) {
                            return true;
                        }
                    }

                    return false;
                } else {
                    return true;
                }
            });
            // Set Active Menu
            $menu->filter(function ($item) {
                if ($item->activematches) {
                    $activematches = (is_string($item->activematches)) ? [$item->activematches] : $item->activematches;
                    foreach ($activematches as $pattern) {
                        if (request()->is($pattern)) {
                            $item->active();
                            $item->link->active();
                            if ($item->hasParent()) {
                                $item->parent()->active();
                            }
                        }
                    }
                }

                return true;
            });
        })->sortBy('order');
    }
}
