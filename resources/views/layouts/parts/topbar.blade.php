<style>
    
.bravo_wrap .bravo_header {
    background: #1a2b48;
    height: 34px;
}

.bravo_wrap .bravo_topbar {
    padding: 15px 0;
    color: #333;
    padding-left: 10px;
    
}

.bravo_wrap .bravo_topbar .content .topbar-right {
    font-size: 12px;
    margin-left: 10px;
    padding: 20px;
}


.bravo_wrap .bravo_header .content .header-left .bravo-menu {
    padding-left: 90px;
    /* color: #fff; */
}

.bravo_wrap .bravo_header .content {
    background: #1a2b48;
    display: flex;
    align-items: center;
    height: 32px;
    font-size: 12px;
    font-weight: 300;
    text-align: center;
    margin-left: 222px;
    color: #fff;
}




.bravo_wrap .bravo_topbar{


    background-color: #fff;
}


.bravo_wrap .bravo_topbar {
    padding: 15px 0;
    color: #333;
    padding-left: 10px;
    /* margin-left: 10px; */
    height: 78px;
}



.bravo_wrap .bravo_topbar .content .socials {
    display: inline-block;
    /* border-right: 1px solid #374969; */
}


.bravo_wrap .bravo_topbar .content .topbar-items li a {
    color: #666;
    font-weight: 400;
    font-size: 12px;
    text-decoration: none;
}
.bravo_wrap .bravo_header .content .header-left .bravo-menu ul li a {
    padding: 35px 13px;
    display: inline-block;
    font-size: 12px;
    font-weight: 200;
    text-transform: uppercase;
    color: #fff;
    transition: all .3s;
}

.bravo_wrap .bravo_topbar .content .topbar-right {
    font-size: 12px;
    padding: 10px;
}
.bravo_wrap .bravo_header .content .header-left .bravo-menu ul li>.menu-dropdown li a {
    padding: 15px 0;
    display: block;
    font-size: 14px;
    color: #888;
}

.bravo_topbar .topbar-left .slogin{
    display:inline-block;
}
</style>

<div class="bravo_topbar">
    <div class="container">
        <div class="content">
            <div class="topbar-left">

                                <a href="{{url('/')}}" class="bravo-logo">
                    @if($logo_id = setting_item("logo_id"))
                        <?php $logo = get_file_url($logo_id,'full') ?>
                        <img src="{{$logo}}" width="135"  alt="{{setting_item("site_title")}}">
                    @endif
                    


                </a>

        
                 <h6  class="slogin">  Your Luxury DMC in Egypt </h6>
            </div>

            <div>
                


            </div>
            <div class="topbar-right">

                           <div>
                
    @if($social_share = setting_item("social_share"))
                    <?php $social_share = json_decode($social_share); ?>
                    <div class="st-list socials">
                        @foreach($social_share as $key=>$item)
                            <a href="{{$item->link}}" target="_blank">
                                <i class="{{$item->class_icon}}"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
                
                @if($admin_email = setting_item("email_from_address"))
                    <ul class="topbar-items">
                        <li class="hidden-xs hidden-sm"><a href="mailto:{{$admin_email}}" target="">{{$admin_email}}</a>
                        </li>
                    </ul>
                @endif
                  <ul class="topbar-items">
                    @if(!Auth::id())
                        <li class="login-item">
                            <a href="#login" data-toggle="modal" data-target="#login" class="login">{{__('Login')}}</a>
                        </li>
                        <li class="signup-item">
                            <a href="#register" data-toggle="modal" data-target="#register" class="signup">{{__('Sign Up')}}</a>
                        </li>
                    @else
                        <li class="login-item dropdown">
                            <a href="#" data-toggle="dropdown" class="login">{{__("Hi, :Name",['name'=>Auth::user()->getDisplayName()])}}
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu text-left">
                                <li><a href="{{url('/user/profile')}}"><i class="icon ion-md-construct"></i> {{__("My profile")}}</a></li>

                                @if(Auth::user()->hasPermissionTo('dashboard_access'))
                                    <li class="menu-hr"><a href="{{url('/admin')}}"><i class="icon ion-ios-ribbon"></i> {{__("Dashboard")}}</a></li>
                                @endif
                                <li class="menu-hr">
                                    <a  href="#" onclick="event.preventDefault(); document.getElementById('logout-form-topbar').submit();"><i class="fa fa-sign-out"></i> {{__('Logout')}}</a>
                                </li>
                            </ul>
                            <form id="logout-form-topbar" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    @endif
            </div>
              
                </ul>
            </div>
        </div>
    </div>
</div>
