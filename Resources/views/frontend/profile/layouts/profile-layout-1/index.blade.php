@extends('isite::frontend.layouts.blank')

@section('content')
    <div class="page profile profile-layout-1">
        <div class="container py-5">
            <div class="row">
                <div class="col">
{{--                    {{dd('epaaa', $mainImage)}}--}}
                    <div class="card-profile">
                        <div class="card-profile-header">
                            <div class="card-top"></div>
                            <x-media::single-image imgClasses="profile-image" alt="Foto de perfil"
                                                   :src="$mainImage"/>
                        </div>
                        <div class="card-profile-info">
                            <div class="container">
                                <div class="row justify-content-center justify-content-md-between text-center text-md-left">
                                    <div class="col-12 col-md-auto">
                                        <div class="profile-name">{{$user->first_name. ' ' .$user->last_name}}</div>
                                        <div class="profile-text">{{$jobData['jobTitle']}}</div>
                                        <div class="profile-text">{{$jobData['jobRole']}}</div>
                                    </div>
                                    <div class="col-12 col-md-auto">
                                        <div class="profile-data">
                                            <i class="fa-solid fa-phone"></i>
                                            {{$jobData['jobMobile']}}
                                        </div>
                                        <div class="profile-data">
                                            <i class="fa-solid fa-envelope"></i>
                                            {{$jobData['jobEmail']}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-social-networks">
                        <div class="container">
                            <div class="row justify-content-around">
                                <div class="col-md-5">
                                    <div class="profile-social-item bg-white">
                                        <i class="fa-light fa-id-badge" aria-hidden="true"></i>
                                        <a href="{{url('/account/vcard/'.$user->id)}}" target="_blank">
                                            {{trans('iprofile::common.layouts.label_vcard_download')}}
                                        </a>
                                    </div>
                                </div>
                                @foreach($jobData['jobLinks'] as $link)
                                    <div class="col-md-5">
                                        <div class="profile-social-item bg-white">
                                            <i class="{{$link->linkIcon ?? 'fa fa-address-book'}}" aria-hidden="true"></i>
                                            <a href="{{$link->linkUrl ?? '/'}}" target="_blank">
                                                {{$link->linkLabel}}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach


                            </div>
                            <div class="row justify-content-center">

                                    <x-isite::social/>


                            </div>
                        </div>

                    </div>
                    <div class="profile-footer">
                        <x-isite::Copyright/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .profile-layout-1 {
            background: #F2F5FA;
            height: 100%;
        }

        .profile-layout-1 .card-profile {
            background-color: #ffffff;
            border-radius: 24px;
        }

        .profile-layout-1 .card-profile .card-profile-header {
            position: relative;
            width: 100%;
            margin: 0 auto;
        }

        .profile-layout-1 .card-profile .card-top {
            width: 100%;
            height: 254px;
            border-radius: 24px;
            background-color: var(--primary);
            box-shadow: inset 0 4px 12px rgba(0, 0, 0, 0.2); /* Sombra interna */
            background-image: repeating-linear-gradient(
                    45deg,
                    rgba(255, 255, 255, 0.1),
                    rgba(255, 255, 255, 0.1) 1px,
                    transparent 2px,
                    transparent 5px
            );
        }

        .profile-layout-1 .card-profile img {
            position: absolute;
            bottom: -78px;
            left: 35px;
            width: 200px !important;
            height: 200px;
            border-radius: 50%;
            border: 5px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            object-fit: cover;
        }


        .profile-layout-1 .card-profile-info {
            margin-top: 100px;
            padding: 25px;
        }

        .profile-layout-1 .card-profile-info .profile-name {
            font-size: 22px;
            font-weight: 500;
            line-height: 33px;
        }

        .profile-layout-1 .card-profile-info .profile-text {
            font-size: 16px;
            font-weight: 500;
            line-height: 24px;
            color: #515254B2;
        }

        .profile-layout-1 .card-profile-info .profile-data {
            font-size: 16px;
            font-weight: 300;
            line-height: 18px;
            color: #858587;
        }

        .profile-layout-1 .card-profile-info .profile-data:not(last-child) {
            margin-bottom: 15px;
        }

        @media (max-width: 767.98px) {
            .profile-layout-1 .card-profile .card-top {
                height: 154px;
            }

            .profile-layout-1 .card-profile img {
                left: 50%;
                bottom: -90px;
                width: 150px !important;
                height: 150px;
                transform: translateX(-50%);
            }
            .profile-layout-1 .card-profile-info .profile-name,
            .profile-layout-1 .card-profile-info .profile-text,
            .profile-layout-1 .card-profile-info .profile-data {
                margin-bottom: 5px !important;
            }
        }

        .profile-layout-1 .profile-social-networks {
            padding-top: 3rem;
        }

        .profile-layout-1 .profile-social-networks .profile-social-item {
            min-height: 65px;
            display: flex;
            align-items: center;
            border-radius: 24px;
            padding: 15px;
            background: #ffffff;
            box-shadow: 10px 10px 20px 0 #E5E5E5;
            margin-bottom: 20px;
            color: #000000;
            font-size: 14px;
            & a {
                color: #000000;
            }
            & i {
                font-size: 45px;
                padding-right: 15px;
                padding-left: 10px;
            }
        }

        .profile-layout-1 .profile-footer {
            font-size: 12px;
            font-weight: 400;
            line-height: 18px;
            text-align: center;
            color: #515254;
            margin-top: 3rem;
        }
    </style>
@stop