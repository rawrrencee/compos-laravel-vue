<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GlobalSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class CommonController extends Controller
{
    protected $COMPANY_NAME = 'COMPANY_NAME';
    protected $HardcodedDataController;

    public function __construct(HardcodedDataController $HardcodedDataController)
    {
        $this->HardcodedDataController = $HardcodedDataController;
    }

    public function getCompanyName()
    {
        return GlobalSettings::where('global_key', 'COMPANY_NAME')->first()?->global_value;
    }

    public function getRoutes()
    {
        return collect(Route::getRoutes())->map(function ($route) {
            return $route->uri();
        });
    }

    public function showPhoto(Request $request)
    {
        return response()->file(storage_path('app/private/' . $request['img_path']));
    }

    public function deletePhoto($img_path)
    {
        if (isset($img_path) && Storage::disk('private')->exists($img_path)) {
            Storage::disk('private')->delete($img_path);
            return true;
        }
        return false;
    }

    public function formatUtcDateToSingaporeDate(?string $dateString)
    {
        if (empty($dateString)) return null;

        $dateInUtc = Carbon::createFromFormat($this->HardcodedDataController->getFrontendUtcDateTimeFormat(), $dateString, 'UTC');
        $dateInSingapore = $dateInUtc->setTimezone($this->HardcodedDataController->getTimezone())->format($this->HardcodedDataController->getSqlDateTimeFormat());

        return $dateInSingapore;
    }

    public function formatUtcDateToSqlDate(?string $dateString)
    {
        if (empty($dateString)) return null;

        $dateInUtc = Carbon::createFromFormat($this->HardcodedDataController->getFrontendUtcDateTimeFormat(), $dateString, 'UTC')->format($this->HardcodedDataController->getSqlDateTimeFormat());

        return $dateInUtc;
    }

    public function formatSqlDateTimeToSingaporeDate(?string $dateString)
    {
        if (empty($dateString)) return null;

        $dateInUTC = Carbon::createFromFormat($this->HardcodedDataController->getSqlDateTimeFormat(), $dateString, 'UTC');

        $dateInSingaporeTimezone = $dateInUTC->setTimezone($this->HardcodedDataController->getTimezone());

        return $dateInSingaporeTimezone;
    }

    public function formatException(\Exception $e)
    {
        return 'Message: ' . $e->getMessage() . ' Line: ' . $e->getLine();
    }

    public function handleException(\Exception $e, String $type = 'default', String $messageModifier = 'update')
    {
        return redirect()->back()
            ->with('show', true)
            ->with('type', $type)
            ->with('status', 'error')
            ->with('message', 'Failed to ' . $messageModifier . ' record: ' . $this->formatException($e));
    }

    public function redirectBackWithGenericError()
    {
        return redirect()->back()
            ->with('show', true)
            ->with('type', 'default')
            ->with('status', 'error')
            ->with('message', 'An error occurred.');
    }
}
