<?php

namespace Gouguoyin\LogViewer\controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Gouguoyin\LogViewer\LogViewerService;

class HomeController extends Controller
{
    public function __construct(Request $request)
    {
        View::share('keywords', $request->input('keywords'));
    }

    /**
     * @param Request $request
     * @param LogViewerService $logViewerService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home(Request $request, LogViewerService $logViewerService)
    {
        if ($request->has('file')) {
            $logViewerService->setLogPath($request->input('file'));
            $viewName = 'log-viewer::detail';
        }else{
            $viewName = 'log-viewer::home';
        }

        return view($viewName, ['logViewerService' => $logViewerService,]);
    }

    /**
     * 文件下载
     * @param Request $request
     * @param LogViewerService $logViewerService
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request, LogViewerService $logViewerService)
    {
        $logViewerService->setLogPath($request->input('file'));
        return response()->download($logViewerService->getLogPath());
    }

    public function delete(Request $request, LogViewerService $logViewerService)
    {
        $logViewerService->setLogPath($request->input('file'));
        if(File::delete($logViewerService->getLogPath())){
            return ['status' => 'success', 'message' => trans('log-viewer::log-viewer.delete.success_message'), 'redirect' => route('home')];
        }
        return ['status' => 'fail', 'message' => trans('log-viewer::log-viewer.delete.success_fail')];
    }

}
