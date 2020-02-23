<?php

namespace Gouguoyin\LogViewer\controllers;

use Gouguoyin\LogViewer\LogViewerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, LogViewerService $logViewerService)
    {
        if ($request->has('file')) {
            $logViewerService->setLogPath($request->input('file'));
            return view('log-viewer::detail', [
                'logViewerService' => $logViewerService,
            ]);
        }else{
            return view('log-viewer::home', [
                'logViewerService' => $logViewerService,
            ]);
        }
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
