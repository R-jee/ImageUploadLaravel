<?php

namespace App\Http\Controllers;

use App\Helper\Data;
use App\Models\Biolink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class DesignController extends Controller
{
    public function index(Request $request)
    {
        $currentTenant = $request->session()->get('sessCompanyContext') ?? null;
        if (!$currentTenant) {
            $currentTenant = auth()->user()->tenant;
        }
        $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
        return view('biolink/design/index', compact('currentTenant', 'biolink'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateBiolink(Request $request)
    {
        $requestData = $request;
        try {

            $this->authorize('update design');
            $currentTenant = $request->session()->get('sessCompanyContext') ?? null;
            if (!$currentTenant) {
                $currentTenant = auth()->user()->tenant;
            }

            $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
            $biolink->custom_color = $request['custom_color'] ?? "FFFFFF";
            $biolink->text_align = $request['text_align'] ?? "left";
            $biolink->page_name = $request['page_name'] ?? null;
            $biolink->introduction = $request['introduction'] ?? null;
            $biolink->use_profile_image = ($request['use_profile_image'] == 'on') ? 1 : 0;
            $biolink->banner_en_btn = ($request['banner_en_btn'] == 'on') ? 1 : 0;
            $biolink->event_en_btn = ($request['event_en_btn'] == 'on') ? 1 : 0;
            $biolink->menu_en_btn = ($request['menu_en_btn'] == 'on') ? 1 : 0;
            $biolink->review_en_btn = ($request['review_en_btn'] == 'on') ? 1 : 0;
            $biolink->store_en_btn = ($request['store_en_btn'] == 'on') ? 1 : 0;
            $biolink->newstore_en_btn = ($request['newstore_en_btn'] == 'on') ? 1 : 0;
            $biolink->instagram_en = ($request['instagram_en'] == 'on') ? 1 : 0;
            $biolink->instagram_url = $request['instagram_url'] ?? null;
            $biolink->facebook_en = ($request['facebook_en'] == 'on') ? 1 : 0;
            $biolink->facebook_url = $request['facebook_url'] ?? null;
            $biolink->twitter_en = ($request['twitter_en'] == 'on') ? 1 : 0;
            $biolink->twitter_url = $request['twitter_url'] ?? null;
            $biolink->youtube_en = ($request['youtube_en'] == 'on') ? 1 : 0;
            $biolink->youtube_url = $request['youtube_url'] ?? null;
            $biolink->blog_en = ($request['blog_en'] == 'on') ? 1 : 0;
            $biolink->blog_url = $request['blog_url'] ?? null;
            $biolink->tiktok_en = ($request['tiktok_en'] == 'on') ? 1 : 0;
            $biolink->tiktok_url = $request['tiktok_url'] ?? null;
            $biolink->naver_smart_store_en = ($request['naver_smart_store_en'] == 'on') ? 1 : 0;
            $biolink->naver_smart_store_url = $request['naver_smart_store_url'] ?? null;

            $biolink->banner_block_title = $request['banner_block_title'] ?? "View All Banners";
            $biolink->events_block_title = $request['events_block_title'] ?? "View All Events";
            $biolink->menu_block_title = $request['menu_block_title'] ?? "View All Menus";
            $biolink->reviews_block_title = $request['reviews_block_title'] ?? "View All Reviews";
            $biolink->store_block_title = $request['store_block_title'] ?? "View All Stores";
            $biolink->new_store_block_title = $request['new_store_block_title'] ?? "View All New Stores";


            if ($biolink->update()) {
                // upload background Image
                if (isset($request['bg_image'])) {
                    $bgImageUploadCheck = $this->uploadBiolinkImage($requestData, "files/bg_image", "bg_image");
                    if ($bgImageUploadCheck['message']) {
                        $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
                        $biolink->bg_image = $bgImageUploadCheck['filename'];
                        $biolink->update();
                    }
                }

                // upload biolink logo Image
                if (isset($request['profile_image'])) {
                    $profileImageUploadCheck = $this->uploadBiolinkImage($requestData, "files/profile_image", "profile_image");
                    if ($profileImageUploadCheck['message']) {
                        $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
                        $biolink->profile_image = $profileImageUploadCheck['filename'];
                        $biolink->update();
                    }
                }

                // upload banner Image
                if (isset($request['banner_image'])) {
                    $bannerImageUploadCheck = $this->uploadBiolinkImage($requestData, "files/banner_image", "banner_image");
                    if ($bannerImageUploadCheck['message']) {
                        $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
                        $biolink->banner_image = $bannerImageUploadCheck['filename'];
                        $biolink->update();
                    }
                }
            }

            return redirect()->route('design')->withSuccess(__('Updated successfully.'));
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return back()->withError(__("$errorMessage"));
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateBiolinkBanner(Request $request)
    {
        $requestData = $request;
        try {

            $this->authorize('update design');
            $currentTenant = $request->session()->get('sessCompanyContext') ?? null;
            if (!$currentTenant) {
                $currentTenant = auth()->user()->tenant;
            }

            $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
            $biolink->banner_en_btn = ($request['banner_en_btn'] == 'on') ? 1 : 0;
            $biolink->banner_block_title = $request['banner_block_title'] ?? "View All Banners";
            if ($biolink->update()) {
                // upload banner Image
                if (isset($request['banner_image'])) {
                    $bannerImageUploadCheck = $this->uploadBiolinkImage($requestData, "files/banner_image", "banner_image");
                    if ($bannerImageUploadCheck['message']) {
                        $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
                        $biolink->banner_image = $bannerImageUploadCheck['filename'];
                        $biolink->update();
                    }
                }
            }

            return redirect()->route('design')->withSuccess(__('Updated successfully.'));
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return back()->withError(__("$errorMessage"));
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateBiolinkEvents(Request $request)
    {
        $requestData = $request;
        try {

            $this->authorize('update design');
            $currentTenant = $request->session()->get('sessCompanyContext') ?? null;
            if (!$currentTenant) {
                $currentTenant = auth()->user()->tenant;
            }

            $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
            $biolink->event_en_btn = ($request['event_en_btn'] == 'on') ? 1 : 0;
            $biolink->events_block_title = $request['events_block_title'] ?? "View All Events";
            if ($biolink->update()) {
                // upload events Image
                if (isset($request['events_image'])) {
                    $eventsImageUploadCheck = $this->uploadBiolinkImage($requestData, "files/events_image", "events_image");
                    if ($eventsImageUploadCheck['message']) {
                        $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
                        $biolink->events_image = $eventsImageUploadCheck['filename'];
                        $biolink->update();
                    }
                }
            }

            return redirect()->route('design')->withSuccess(__('Updated successfully.'));
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return back()->withError(__("$errorMessage"));
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateBiolinkMenus(Request $request)
    {
        $requestData = $request;
        try {

            $this->authorize('update design');
            $currentTenant = $request->session()->get('sessCompanyContext') ?? null;
            if (!$currentTenant) {
                $currentTenant = auth()->user()->tenant;
            }

            $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
            $biolink->menu_en_btn = ($request['menu_en_btn'] == 'on') ? 1 : 0;
            $biolink->menu_block_title = $request['menu_block_title'] ?? "View All Menus";
            if ($biolink->update()) {
                // upload menus Image
                if (isset($request['menu_image'])) {
                    $menuImageUploadCheck = $this->uploadBiolinkImage($requestData, "files/menu_image", "menu_image");
                    if ($menuImageUploadCheck['message']) {
                        $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
                        $biolink->menu_image = $menuImageUploadCheck['filename'];
                        $biolink->update();
                    }
                }
            }

            return redirect()->route('design')->withSuccess(__('Updated successfully.'));
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return back()->withError(__("$errorMessage"));
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateBiolinkReviews(Request $request)
    {
        $requestData = $request;
        try {

            $this->authorize('update design');
            $currentTenant = $request->session()->get('sessCompanyContext') ?? null;
            if (!$currentTenant) {
                $currentTenant = auth()->user()->tenant;
            }

            $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
            $biolink->review_en_btn = ($request['review_en_btn'] == 'on') ? 1 : 0;
            $biolink->reviews_block_title = $request['reviews_block_title'] ?? "View All Reviews";
            if ($biolink->update()) {
                // upload reviews Image
                if (isset($request['reviews_image'])) {
                    $reviewsImageUploadCheck = $this->uploadBiolinkImage($requestData, "files/reviews_image", "reviews_image");
                    if ($reviewsImageUploadCheck['message']) {
                        $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
                        $biolink->reviews_image = $reviewsImageUploadCheck['filename'];
                        $biolink->update();
                    }
                }
            }

            return redirect()->route('design')->withSuccess(__('Updated successfully.'));
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return back()->withError(__("$errorMessage"));
        }
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function updateBiolinkStores(Request $request)
    {
        $requestData = $request;
        try {

            $this->authorize('update design');
            $currentTenant = $request->session()->get('sessCompanyContext') ?? null;
            if (!$currentTenant) {
                $currentTenant = auth()->user()->tenant;
            }

            $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
            $biolink->store_en_btn = ($request['store_en_btn'] == 'on') ? 1 : 0;
            $biolink->stores_block_title = $request['stores_block_title'] ?? "View All Stores";
            if ($biolink->update()) {
                // upload stores Image
                if (isset($request['stores_image'])) {
                    $storesImageUploadCheck = $this->uploadBiolinkImage($requestData, "files/stores_image", "stores_image");
                    if ($storesImageUploadCheck['message']) {
                        $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
                        $biolink->stores_image = $storesImageUploadCheck['filename'];
                        $biolink->update();
                    }
                }
            }

            return redirect()->route('design')->withSuccess(__('Updated successfully.'));
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return back()->withError(__("$errorMessage"));
        }
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function updateBiolinkNewStores(Request $request)
    {
        $requestData = $request;
        try {

            $this->authorize('update design');
            $currentTenant = $request->session()->get('sessCompanyContext') ?? null;
            if (!$currentTenant) {
                $currentTenant = auth()->user()->tenant;
            }

            $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
            $biolink->newstore_en_btn = ($request['newstore_en_btn'] == 'on') ? 1 : 0;
            $biolink->newstores_block_title = $request['newstores_block_title'] ?? "View All Newstores";
            if ($biolink->update()) {
                // upload newstores Image
                if (isset($request['newstores_image'])) {
                    $newstoresImageUploadCheck = $this->uploadBiolinkImage($requestData, "files/newstores_image", "newstores_image");
                    if ($newstoresImageUploadCheck['message']) {
                        $biolink = Biolink::where('tenant_id', $currentTenant->id)->first();
                        $biolink->newstores_image = $newstoresImageUploadCheck['filename'];
                        $biolink->update();
                    }
                }
            }

            return redirect()->route('design')->withSuccess(__('Updated successfully.'));
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return back()->withError(__("$errorMessage"));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function snsOrderUpdate(Request $request)
    {
        dd($request);
        // Extract and process the sorted order from the request
        $sortedOrder = $request->input('order');

        // Update the order in the database, if necessary

        // Return a response (e.g., success message)
        return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * @param $jfifImagePath
     * @return array
     */
    public function saveImageAtPublicPath($jfifImagePath, $imagePublicPath)
    {
        $destinationPath = public_path($imagePublicPath);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        try {
            $image = Image::make($jfifImagePath);
            $parts = explode('/', $image->mime);
            $outputFormat = strtolower(end($parts));
            $filename = 'biolink_image_' . rand(999, 9999999) . '_' . time() . '.' . $outputFormat;
            $image->save($destinationPath . '/' . $filename);
            return ['message' => true, 'filename' => $filename, 'url' => $jfifImagePath, 'image_full_url' => $destinationPath . '/' . $filename, 'public_url' => url('/' . $imagePublicPath) . '/' . $filename];
        } catch (\Exception $e) {
            return ['message' => false, 'filename' => "", 'url' => $jfifImagePath, 'image_full_url' => "", 'public_url' => ""];
        }
    }

    /**
     * @param $request
     * @param $imagePublicPath
     * @param $imagefileUploaderName
     * @return array|false
     */
    public function uploadBiolinkImage($request, $imagePublicPath, $imagefileUploaderName)
    {
        if ($request->hasFile($imagefileUploaderName)) {
            $file = $request->file($imagefileUploaderName);
            return $this->saveImageAtPublicPath($file, $imagePublicPath);
        }
        return false;
    }


}
