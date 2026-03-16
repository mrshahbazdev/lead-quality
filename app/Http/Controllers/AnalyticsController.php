<?php

namespace App\Http\Controllers;

use App\Services\LeadQualityAnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(LeadQualityAnalyticsService $analyticsService)
    {
        $growthData = $analyticsService->getNetworkGrowth();
        $fulfillment = $analyticsService->getFulfillmentRates();
        $industryMap = $analyticsService->getIndustrySuccessMap();

        return view('analytics.index', compact('growthData', 'fulfillment', 'industryMap'));
    }
}
