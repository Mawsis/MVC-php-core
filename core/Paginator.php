<?php

namespace app\core;

class Paginator
{
    public static function paginate(QueryBuilder $query, Response &$response, int $perPage = 10, int $page = 1): array
    {
        $total = self::getTotalRecords($query);
        $offset = ($page - 1) * $perPage;
        $query = $query->limit($perPage)->offset($offset);
        $data = $query->get();


        $paginationData = [
            'total' => (int) $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'url' => self::removePageParam($_SERVER['REQUEST_URI']),
        ];

        $response->assign('pagination', $paginationData);

        return $data;
    }

    private static function getTotalRecords($query)
    {
        $countQuery = clone $query;
        return count($countQuery->get());
    }

    private static function removePageParam(string $url): string
    {
        $parsedUrl = parse_url($url);
        if (!isset($parsedUrl['query'])) {
            return $url;
        }

        parse_str($parsedUrl['query'], $queryParams);
        unset($queryParams['page']);

        $newQueryString = http_build_query($queryParams);
        return $parsedUrl['path'] . ($newQueryString ? '?' . $newQueryString : '');
    }
}