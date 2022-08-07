<?php

namespace app\controllers;

use app\kernel\Database;
use app\kernel\Request;
use app\kernel\Response;

class ShopController
{
    public function createItem(Request $request)
    {

    }

    public function getAllItems(Request $request, Response $response, Database $database)
    {
        try {
            $query = 'SELECT * FROM items';
            $queryParams = $request->getQueryParams();
            if (!empty($queryParams)) {
                $params = [];
                $query = 'SELECT * FROM items WHERE ';
                $item_code = $queryParams['itemcode'] ?? false;
                $expiry_date = $queryParams['expirydate'] ?? false;
                if ($item_code !== false) {
                    $query .= 'item_code = :itemcode';
                    $params[':itemcode'] = $item_code;
                }
                if ($expiry_date !== false) {
                    if (strpos($query, ':') !== false) {
                        $query .= ' AND ';
                    }
                    $query .= 'expiry_date > :expirydate';
                    $params[':expirydate'] = $expiry_date;
                }

                $data = $database->prepare($query)->execute($params)->fetchAll();

            } else {
                $data = $database->query($query)->fetchAll();
            }
            $response_array = array(
                'status' => 'success',
                'message' => 'Data fetched successfully',
                'data' => $data
            );
        } catch (\Exception $exception) {
            $response_array = array(
                'status' => 'failed',
                'message' => 'Something went wrong',
                'data' => []
            );
        }

        $response->sendJson($response_array);
    }

    public function updateStock(Request $request, Response $response, Database $database)
    {
        try {
            $query = 'SELECT * FROM items';
            $requestBody = $request->getBody();
            if (!empty($requestBody)) {
                $params = [];
                $query = 'SELECT * FROM items WHERE ';
                $item_code = $requestBody['itemcode'] ?? false;
                if ($item_code !== false) {
                    $query .= 'item_code = :itemcode';
                    $params[':itemcode'] = $item_code;
                }
                $data = $database->prepare($query)->execute($params)->fetch();
                if (!empty($data)) {
//                    var_dump($data);
                    echo intval($data['current_stock']);
//                    exit;
                    if (intval($data['current_stock']) <= 0) {
                        $response_array = array(
                            'status' => 'failed',
                            'message' => 'Current Stock is 0',
                            'data' => []
                        );
                    } else {
                        $new_stock = intval($data['current_stock']) - 1;
                        if ($new_stock < 0) {
                            $response_array = array(
                                'status' => 'failed',
                                'message' => 'Cannot reduce stock due to empty stock',
                                'data' => []
                            );
                        } else {
                            $query = 'UPDATE items SET current_stock=:newstock WHERE item_code=:itemcode';
                            $params = [
                                'newstock' => $new_stock,
                                'itemcode' => $item_code
                            ];
                            $database->prepare($query)->execute($params);
                            $response_array = array(
                                'status' => 'failed',
                                'message' => 'Stock has been updated successfully',
                                'data' => []
                            );
                        }
                    }
                } else {
                    $response_array = array(
                        'status' => 'failed',
                        'message' => 'No data found',
                        'data' => []
                    );
                }
            } else {
                $response_array = array(
                    'status' => 'failed',
                    'message' => 'Item code is missing',
                    'data' => []
                );
            }
        } catch (\Exception $exception) {
            $response_array = array(
                'status' => 'failed',
                'message' => 'Something went wrong',
                'data' => [$exception]
            );
        }

        $response->sendJson($response_array);
    }
}