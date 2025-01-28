<?php

namespace App\Services;

use App\Services\CommerceToolsService;
use PhpParser\Node\Expr\Cast\Object_;

class CommerceToolsDataFetcher
{
    private CommerceToolsService $commerceToolsService;

    public function __construct(CommerceToolsService $commerceToolsService)
    {
        $this->commerceToolsService = $commerceToolsService;
    }

    public function fetchCurrentOrders($queryArgs)
    {
        $apiClient = $this->commerceToolsService->createApiClient();
        return $apiClient->orders()->get()->withWhere($queryArgs)->withSort('createdAt desc')->execute();
    }

    public function getCurrentOrderCount($queryArgs): string
    {
        $orders = $this->fetchCurrentOrders($queryArgs);
        return ($orders->getTotal());
    }
}