<x-app-layout>

    <form class="text-center" action="{{ route('design.update.biolink', compact('currentTenant')) }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        <div class="row justify-center mb-5 mx-1 mx-md-3 pt-4 pb-4">
            <div class="col-12 col-md-9 card">
                {{-- Media object --}}
                <div class="pl-4 pr-4 pt-2 pb-2 card m-3">
                    <!-- Easy and useful color picker -->
                    <div class="align-content-between color-wrapper d-flex w-100">
                        <span class="fw-bold fs-mob-12 mr-auto">{{__('Background Color')}}</span>
                        <input type="hidden" name="custom_color" placeholder="#FFFFFF" id="pickcolor"
                               value="@if($biolink->custom_color){{"$biolink->custom_color"}}@endif"
                               class="call-picker">
                        <div class="color-holder call-picker"></div>
                        <div class="color-picker" id="color-picker" style="display: none"></div>
                    </div>

                    <div class="align-self-center d-flex mb-1 mb-md-3 w-100">
                        <label for="imageMenu" class="fw-bold fs-mob-12 mr-auto">{{ __('Background Image') }}</label>
                        <div class="my-3">
                            <div class="d-flex">
                                <div class="reviewimage-upload">
                                    <div class="previewimage-edit">
                                        <input type="file" id="imageUpload_bg" name="bg_image"
                                               accept=".png, .jpg, .jpeg">
                                        <label for="imageUpload_bg"></label>
                                    </div>
                                    <div class="image-preview">
                                        <div id="bg_image"
                                             style="background-image: @if($biolink->bg_image) @if($biolink->bg_image != "") url({{asset('/files/bg_image/'. $biolink->bg_image)}}); @else url({{asset('/images/none_thumb.jpg')}}); @endif @else url({{asset('/images/none_thumb.jpg')}}); @endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p id="image_error"
                               class="text-danger d-none py-1 fs-mob-12">{{__('Please upload image!')}}</p>
                        </div>
                    </div>

                </div>

                {{-- Media object --}}
                <div class="pl-4 pr-4 pt-4 pb-2 card m-3">
                    @php
                        $background_image = url(asset('/images/none_thumb.jpg'));
                        $use_profile_image_check = "";
                        if($biolink->profile_image) {
                            if($biolink->profile_image != "") {
                               $background_image = url(asset('/files/profile_image/'. $biolink->profile_image));
                            }
                        }
                        if($biolink->use_profile_image){
                            $use_profile_image_check = "checked";
                        }
                    @endphp
                    <x-image-upload-block
                        image_label="{{__('Biolink Image')}}"
                        use_profile_image="use_profile_image"
                        checked={{$use_profile_image_check}}
                            imageUpload="imageUpload"
                        accept=".png, .jpg, .jpeg"
                        profile_image="profile_image"
                        background_image={{$background_image}}
                    />

                    {{-- Description boxes  --}}
                    <div class="">
                        <input type="text" class="form-control" placeholder="Title"
                               value="@if($biolink->page_name){{$biolink->page_name}}@endif"
                               name="page_name">
                        <textarea class="form-control mt-2" placeholder="Description..."
                                  name="introduction"
                                  value="@if($biolink->introduction){{$biolink->introduction}}@endif"
                        >@if($biolink->introduction)
                                {{$biolink->introduction}}
                            @endif</textarea>
                        <div class="pt-2 pb-2 float-left">
                            <div class="form-check form-check-inline pl-0">
                                <input class="form-check-input form-check-input-custom d-none" type="radio"
                                       name="text_align" id="text_align_left"
                                       @if($biolink->text_align == "left") checked @endif
                                       value="left">
                                <label class="form-check-label form-check-label-custom w-14" for="text_align_left"><i
                                        class="fa fa-align-left"
                                        aria-hidden="true"></i></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-check-input-custom d-none" type="radio"
                                       name="text_align" id="text_align_center"
                                       @if($biolink->text_align == "center") checked @endif
                                       value="center">
                                <label class="form-check-label form-check-label-custom w-14" for="text_align_center"><i
                                        class="fa fa-align-center"
                                        aria-hidden="true"></i></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-check-input-custom d-none" type="radio"
                                       name="text_align" id="text_align_right"
                                       @if($biolink->text_align == "right") checked @endif
                                       value="right">
                                <label class="form-check-label form-check-label-custom w-14" for="text_align_right"><i
                                        class="fa fa-align-right"
                                        aria-hidden="true"></i></label>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Banner Block  --}}
                <div class="p-2 card m-3">
                    <div
                        class="p-2 border-1 m-2 d-flex justify-content-between align-items-center form-check form-switch">
                        <i class="fa fa-ellipsis-v text-gray-400" hidden aria-hidden="true"></i>&emsp;
                        <p class="fw-medium mr-auto">{{__('Banners Block')}}</p>
                        <i class="fa fa-edit mr-16" data-bs-toggle="modal" data-bs-target="#bannerBlockSettings"></i>
                        <input class="form-check-input ml-0" type="checkbox" role="switch" id="bannerBlock_en"
                               @if($biolink->banner_en_btn) checked @endif
                               name="banner_en_btn">
                    </div>

                    {{-- Event BLock  --}}

                    <div
                        class="p-2 border-1 m-2 d-flex justify-content-between align-items-center form-check form-switch">
                        <i class="fa-solid fa-ellipsis-vertical"></i>&emsp;
                        <p class="fw-medium mr-auto">{{__('Events Block')}}</p>
                        <i class="fa fa-edit mr-16" data-bs-toggle="modal" data-bs-target="#eventBlockSettings"></i>
                        <input class="form-check-input ml-0" type="checkbox" role="switch" id="eventBlock_en"
                               @if($biolink->event_en_btn) checked @endif
                               name="event_en_btn">
                    </div>

                    {{-- Menu Block  --}}

                    <div
                        class="p-2 border-1 m-2 d-flex justify-content-between align-items-center form-check form-switch">
                        <i class="fa-solid fa-ellipsis-vertical"></i>&emsp;
                        <p class="fw-medium mr-auto">{{__('Menu Block')}}</p>
                        <i class="fa fa-edit mr-16" data-bs-toggle="modal" data-bs-target="#menuBlockSettings"></i>
                        <input class="form-check-input ml-0" type="checkbox" role="switch" id="memuBlock_en"
                               @if($biolink->menu_en_btn) checked @endif
                               name="menu_en_btn">
                    </div>


                    {{-- Review BLock  --}}

                    <div
                        class="p-2 border-1 m-2 d-flex justify-content-between align-items-center form-check form-switch">
                        <i class="fa-solid fa-ellipsis-vertical"></i>&emsp;
                        <p class="fw-medium mr-auto">{{__('Review Block')}}</p>
                        <i class="fa fa-edit mr-16" data-bs-toggle="modal" data-bs-target="#reviewBlockSettings"></i>
                        <input class="form-check-input ml-0" type="checkbox" role="switch" id="reviewBlock_en"
                               @if($biolink->review_en_btn) checked @endif
                               name="review_en_btn">
                    </div>


                    {{-- Store Block  --}}

                    <div
                        class="p-2 border-1 m-2 d-flex justify-content-between align-items-center form-check form-switch">
                        <i class="fa-solid fa-ellipsis-vertical"></i>&emsp;
                        <p class="fw-medium mr-auto">{{__('Store Block')}}</p>
                        <i class="fa fa-edit mr-16" data-bs-toggle="modal" data-bs-target="#storeBlockSettings"></i>
                        <input class="form-check-input ml-0" type="checkbox" role="switch" id="storeBlock_en"
                               @if($biolink->store_en_btn) checked @endif
                               name="store_en_btn">
                    </div>


                    {{-- New Store Block  --}}

                    <div
                        class="p-2 border-1 m-2 d-flex justify-content-between align-items-center form-check form-switch">
                        <i class="fa-solid fa-ellipsis-vertical"></i>&emsp;
                        <p class="fw-medium mr-auto">{{__('New Stores Block')}}</p>
                        <i class="fa fa-edit mr-16" data-bs-toggle="modal"
                           data-bs-target="#newstoreBlockSettings"></i>
                        <input class="form-check-input ml-0" type="checkbox" role="switch" id="newstoreBlock_en"
                               @if($biolink->newstore_en_btn) checked @endif
                               name="newstore_en_btn">
                    </div>
                </div>

                {{-- SNS Links --}}
                <div class="p-3 card m-3">
                    <span class="fw-bold fs-mob-12 mr-auto mb-2">{{__('SNS Links Settings')}}</span>
                    @php
                        $instagram_en = false;
                        $instagram_url = "";
                        $instagram_url_value = $biolink->instagram_url;
                        $instagram_en_check = "";
                        if($biolink->instagram_url){
                            $instagram_url = $biolink->instagram_url;
                        }
                        if($biolink->instagram_en){
                            $instagram_en_check = "checked";
                        }
                    @endphp
                    <x-s-n-s-input
                        fa_icon='fa-brands fa-instagram'
                        url='{{$instagram_url_value}}'
                        url_id='instagram_url'
                        url_en_id='instagram_en'
                        url_en_checked='{{$instagram_en_check}}'
                        aria_label='{{__("Text input with checkbox")}}'
                    />

                    @php
                        $blog_en = false;
                        $blog_url = "";
                        $blog_url_value = $biolink->blog_url;
                        $blog_en_check = "";
                        if($biolink->blog_url){
                            $blog_url = $biolink->blog_url;
                        }
                        if($biolink->blog_en){
                            $blog_en_check = "checked";
                        }
                    @endphp
                    <x-s-n-s-input
                        fa_icon='fa-solid fa-blog'
                        url='{{$blog_url_value}}'
                        url_id='blog_url'
                        url_en_id='blog_en'
                        url_en_checked='{{$blog_en_check}}'
                        aria_label='{{__("Text input with checkbox")}}'
                    />

                    @php
                        $youtube_en = false;
                        $youtube_url = "";
                        $youtube_url_value = $biolink->youtube_url;
                        $youtube_en_check = "";
                        if($biolink->youtube_url){
                            $youtube_url = $biolink->youtube_url;
                        }
                        if($biolink->youtube_en){
                            $youtube_en_check = "checked";
                        }
                    @endphp
                    <x-s-n-s-input
                        fa_icon='fa-brands fa-youtube'
                        url='{{$youtube_url_value}}'
                        url_id='youtube_url'
                        url_en_id='youtube_en'
                        url_en_checked='{{$youtube_en_check}}'
                        aria_label='{{__("Text input with checkbox")}}'
                    />

                    @php
                        $facebook_en = false;
                        $facebook_url = "";
                        $facebook_url_value = $biolink->facebook_url;
                        $facebook_en_check = "";
                        if($biolink->facebook_url){
                            $facebook_url = $biolink->facebook_url;
                        }
                        if($biolink->facebook_en){
                            $facebook_en_check = "checked";
                        }
                    @endphp
                    <x-s-n-s-input
                        fa_icon='fa-brands fa-facebook'
                        url='{{$facebook_url_value}}'
                        url_id='facebook_url'
                        url_en_id='facebook_en'
                        url_en_checked='{{$facebook_en_check}}'
                        aria_label='{{__("Text input with checkbox")}}'
                    />

                    @php
                        $tiktok_en = false;
                        $tiktok_url = "";
                        $tiktok_url_value = $biolink->tiktok_url;
                        $tiktok_en_check = "";
                        if($biolink->tiktok_url){
                            $tiktok_url = $biolink->tiktok_url;
                        }
                        if($biolink->tiktok_en){
                            $tiktok_en_check = "checked";
                        }
                    @endphp
                    <x-s-n-s-input
                        fa_icon='fa-brands fa-tiktok'
                        url='{{$tiktok_url_value}}'
                        url_id='tiktok_url'
                        url_en_id='tiktok_en'
                        url_en_checked='{{$tiktok_en_check}}'
                        aria_label='{{__("Text input with checkbox")}}'
                    />

                </div>
                {{-- END SNS Links --}}

                <div class="p-0 card m-3 border-0 float-right">
                    <button type="submit" class="btn btn-sm btn-outline-primary w-fit">Update</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal for all banners preview -->
    <div class="modal fade" id="bannerBlockSettings" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white py-0">
                    <h5 class="modal-title">{{__('Banners Block Setup')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class=fa-2x>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Display menu details inside the modal -->
                    <form action="{{ route('design.update.biolink.banner', compact('currentTenant')) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="pl-4 pr-4 pt-4 pb-2 card m-3">
                            @php
                                $background_image = url(asset('/images/none_thumb.jpg'));
                                $use_banner_image_check = "";
                                $banner_block_title = $biolink->banner_block_title;
                                if($biolink->banner_image) {
                                    if($biolink->banner_image != "") {
                                       $background_image = url(asset('/files/banner_image/'. $biolink->banner_image));
                                    }
                                }
                                if($biolink->banner_en_btn){
                                    $use_banner_image_check = "checked";
                                }
                            @endphp
                            {{-- banner_block_title--}}
                            <x-imagebanner-upload-block
                                image_label="{{__('Banner Image')}}"
                                use_profile_image="banner_en_btn"
                                checked="{{$use_banner_image_check}}"
                                imageUpload="imageUpload_banner"
                                accept=".png, .jpg, .jpeg"
                                profile_image="banner_image"
                                background_image={{$background_image}}
                            />
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="banner_block_title"
                                       name="banner_block_title" value="{{$banner_block_title}}"
                                       placeholder="{{$banner_block_title}}" autocomplete="false">
                                <label for="banner_block_title">{{__("Title")}}</label>
                            </div>
                        </div>
                        <div class="p-0 card m-3 border-0 float-right">
                            <button type="submit" class="btn btn-sm btn-outline-success w-fit">{{__('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for all events preview -->
    <div class="modal fade" id="eventBlockSettings" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white py-0">
                    <h5 class="modal-title">{{__('Events Block Setup')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class=fa-2x>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Display menu details inside the modal -->
                    <form action="{{ route('design.update.biolink.events', compact('currentTenant')) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="pl-4 pr-4 pt-4 pb-2 card m-3">
                            @php
                                $background_image = url(asset('/images/none_thumb.jpg'));
                                $use_events_image_check = "";
                                $events_block_title = $biolink->events_block_title;
                                if($biolink->events_image) {
                                    if($biolink->events_image != "") {
                                       $background_image = url(asset('/files/events_image/'. $biolink->events_image));
                                    }
                                }
                                if($biolink->event_en_btn){
                                    $use_events_image_check = "checked";
                                }
                            @endphp
                            {{-- events_block_title--}}
                            <x-imagebanner-upload-block
                                image_label="{{__('Events Image')}}"
                                use_profile_image="event_en_btn"
                                checked="{{$use_events_image_check}}"
                                imageUpload="imageUpload_events"
                                accept=".png, .jpg, .jpeg"
                                profile_image="events_image"
                                background_image={{$background_image}}
                            />
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="events_block_title"
                                       name="events_block_title" value="{{$events_block_title}}"
                                       placeholder="{{$events_block_title}}" autocomplete="false">
                                <label for="events_block_title">{{__("Title")}}</label>
                            </div>
                        </div>
                        <div class="p-0 card m-3 border-0 float-right">
                            <button type="submit" class="btn btn-sm btn-outline-success w-fit">{{__('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for all menu preview -->
    <div class="modal fade" id="menuBlockSettings" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white py-0">
                    <h5 class="modal-title">{{__('Menu Block Setup')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class=fa-2x>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Display menu details inside the modal -->
                    <form action="{{ route('design.update.biolink.menus', compact('currentTenant')) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="pl-4 pr-4 pt-4 pb-2 card m-3">
                            @php
                                $background_image = url(asset('/images/none_thumb.jpg'));
                                $use_menu_image_check = "";
                                $menu_block_title = $biolink->menu_block_title;
                                if($biolink->menu_image) {
                                    if($biolink->menu_image != "") {
                                       $background_image = url(asset('/files/menu_image/'. $biolink->menu_image));
                                    }
                                }
                                if($biolink->menu_en_btn){
                                    $use_menu_image_check = "checked";
                                }
                            @endphp
                            {{-- menu_block_title--}}
                            <x-imagebanner-upload-block
                                image_label="{{__('Menu Image')}}"
                                use_profile_image="menu_en_btn"
                                checked="{{$use_menu_image_check}}"
                                imageUpload="imageUpload_menu"
                                accept=".png, .jpg, .jpeg"
                                profile_image="menu_image"
                                background_image={{$background_image}}
                            />
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="menu_block_title"
                                       name="menu_block_title" value="{{$menu_block_title}}"
                                       placeholder="{{$menu_block_title}}" autocomplete="false">
                                <label for="menu_block_title">{{__("Title")}}</label>
                            </div>
                        </div>
                        <div class="p-0 card m-3 border-0 float-right">
                            <button type="submit" class="btn btn-sm btn-outline-success w-fit">{{__('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for all review preview -->
    <div class="modal fade" id="reviewBlockSettings" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white py-0">
                    <h5 class="modal-title">{{__('Review Block Setup')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class=fa-2x>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Display menu details inside the modal -->
                    <form action="{{ route('design.update.biolink.reviews', compact('currentTenant')) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="pl-4 pr-4 pt-4 pb-2 card m-3">
                            @php
                                $background_image = url(asset('/images/none_thumb.jpg'));
                                $use_review_image_check = "";
                                $reviews_block_title = $biolink->reviews_block_title;
                                if($biolink->reviews_image) {
                                    if($biolink->reviews_image != "") {
                                       $background_image = url(asset('/files/reviews_image/'. $biolink->reviews_image));
                                    }
                                }
                                if($biolink->review_en_btn){
                                    $use_review_image_check = "checked";
                                }
                            @endphp
                            {{-- reviews_block_title--}}
                            <x-imagebanner-upload-block
                                image_label="{{__('Review Image')}}"
                                use_profile_image="review_en_btn"
                                checked="{{$use_review_image_check}}"
                                imageUpload="imageUpload_review"
                                accept=".png, .jpg, .jpeg"
                                profile_image="reviews_image"
                                background_image={{$background_image}}
                            />
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="reviews_block_title"
                                       name="reviews_block_title" value="{{$reviews_block_title}}"
                                       placeholder="{{$reviews_block_title}}" autocomplete="false">
                                <label for="reviews_block_title">{{__("Title")}}</label>
                            </div>
                        </div>
                        <div class="p-0 card m-3 border-0 float-right">
                            <button type="submit" class="btn btn-sm btn-outline-success w-fit">{{__('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for all store preview -->
    <div class="modal fade" id="storeBlockSettings" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white py-0">
                    <h5 class="modal-title">{{__('Store Block Setup')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class=fa-2x>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Display store details inside the modal -->
                    <form action="{{ route('design.update.biolink.stores', compact('currentTenant')) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="pl-4 pr-4 pt-4 pb-2 card m-3">
                            @php
                                $background_image = url(asset('/images/none_thumb.jpg'));
                                $use_store_image_check = "";
                                $store_block_title = $biolink->store_block_title;
                                if($biolink->store_image) {
                                    if($biolink->store_image != "") {
                                       $background_image = url(asset('/files/store_image/'. $biolink->store_image));
                                    }
                                }
                                if($biolink->store_en_btn){
                                    $use_menu_image_check = "checked";
                                }
                            @endphp
                            {{-- menu_block_title--}}
                            <x-imagebanner-upload-block
                                image_label="{{__('Menu Image')}}"
                                use_profile_image="menu_en_btn"
                                checked="{{$use_menu_image_check}}"
                                imageUpload="imageUpload_menu"
                                accept=".png, .jpg, .jpeg"
                                profile_image="menu_image"
                                background_image={{$background_image}}
                            />
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="menu_block_title"
                                       name="menu_block_title" value="{{$menu_block_title}}"
                                       placeholder="{{$menu_block_title}}" autocomplete="false">
                                <label for="menu_block_title">{{__("Title")}}</label>
                            </div>
                        </div>
                        <div class="p-0 card m-3 border-0 float-right">
                            <button type="submit" class="btn btn-sm btn-outline-success w-fit">{{__('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for new stores preview -->
    <div class="modal fade" id="newstoreBlockSettings" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white py-0">
                    <h5 class="modal-title">{{__('New Store Block Setup')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class=fa-2x>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Display menu details inside the modal -->
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            var colorList = ['FFFFFF', '000000', 'ffc000', 'e2f0d9'];
            var picker = $('#color-picker');
            for (var i = 0; i < colorList.length; i++) {
                picker.append('<li class="color-item" data-hex="' + '#' + colorList[i] + '" style="background-color:' + '#' + colorList[i] + ';"></li>');
            }
            $('body').click(function () {
                picker.fadeOut();
            });
            $('.call-picker').click(function (event) {
                event.stopPropagation();
                picker.fadeIn();
                picker.children('li').hover(function () {
                    var codeHex = $(this).data('hex');

                    $('.color-holder').css('background-color', codeHex);
                    $('#pickcolor').val(codeHex);
                });
            });
        </script>
        <script>
            function removeFile(fileUploaderID) {
                var fileInput = document.getElementById("" + fileUploaderID);
                fileInput.value = null;
            }

            function readURL(input, image_id) {
                removeFile(image_id);
                var reader;
                if (input.files && input.files[0]) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        $('#' + image_id).css('background-image', 'url(' + e.target.result + ')');
                        $('#' + image_id).hide();
                        $('#' + image_id).fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#imageUpload").on('change', function () {
                readURL(this, 'profile_image');
            });

            $("#imageUpload_bg").on('change', function () {
                readURL(this, 'bg_image');
            });

            $("#imageUpload_banner").on('change', function () {
                readURL(this, 'banner_image');
            });

            $("#imageUpload_events").on('change', function () {
                readURL(this, 'events_image');
            });

            $("#imageUpload_menu").on('change', function () {
                readURL(this, 'menu_image');
            });

            $("#imageUpload_review").on('change', function () {
                readURL(this, 'reviews_image');
            });

        </script>
        <script>
            $(document).ready(function () {
                if ('{{$biolink->custom_color}}') {
                    $('.color-holder.call-picker').css('background', '{{$biolink->custom_color}}');
                }
            });
        </script>

    @endpush

</x-app-layout>
