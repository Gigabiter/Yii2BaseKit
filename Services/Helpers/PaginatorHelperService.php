<?php

namespace kosuhin\Yii2BaseKit\Services\Helpers;

use yii\data\Pagination;

class PaginatorHelperService
{
    public function getFromLimit($page, $pageCount, $total)
    {
        $paginator = new Pagination([
            'totalCount' => $total,
            'page' => $page,
            'pageSize' => $pageCount
        ]);

        return [
            'offset' => $paginator->getOffset(),
            'limit' => $paginator->getLimit(),
        ];
    }
}