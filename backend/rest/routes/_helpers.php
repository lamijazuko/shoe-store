<?php

function api_error_payload($status, $message)
{
    return [
        'error' => [
            'status' => $status,
            'message' => $message,
        ],
    ];
}

function api_halt($status, $message)
{
    Flight::jsonHalt(api_error_payload($status, $message), $status);
}

function api_error($status, $message)
{
    Flight::json(api_error_payload($status, $message), $status);
}

function request_json()
{
    $body = Flight::request()->getBody();
    if (trim($body) === '') {
        return [];
    }

    $data = json_decode($body, true);
    if (!is_array($data) || json_last_error() !== JSON_ERROR_NONE) {
        throw new InvalidArgumentException('Request body must be a JSON object.');
    }

    return $data;
}

function register_crud_routes($path, $service, $sanitizer = null)
{
    $format = function ($entity) use ($sanitizer) {
        return $sanitizer === null ? $entity : $sanitizer($entity);
    };

    Flight::route('GET ' . $path, function () use ($service, $format) {
        api_run(function () use ($service, $format) {
            return array_map($format, $service->get_all());
        });
    });

    Flight::route('GET ' . $path . '/@id:[0-9]+', function ($id) use ($service, $format) {
        api_run(function () use ($service, $format, $id) {
            return $format($service->get_by_id((int) $id));
        });
    });

    Flight::post($path, function () use ($service) {
        api_run(function () use ($service) {
            return $service->create(request_json());
        }, 201);
    });

    Flight::put($path . '/@id:[0-9]+', function ($id) use ($service) {
        api_run(function () use ($service, $id) {
            return $service->update((int) $id, request_json());
        });
    });

    Flight::delete($path . '/@id:[0-9]+', function ($id) use ($service) {
        api_run(function () use ($service, $id) {
            return $service->delete((int) $id) ? ['deleted' => true] : null;
        });
    });
}

function api_run($callback, $status = 200)
{
    try {
        $result = $callback();
        if ($result === null) {
            api_halt(404, 'Resource not found.');
        }
        Flight::json(['data' => $result], $status);
    } catch (InvalidArgumentException $error) {
        api_error(400, $error->getMessage());
    } catch (RuntimeException $error) {
        $status = in_array($error->getCode(), [401, 403], true) ? (int) $error->getCode() : 500;
        api_error($status, $error->getMessage());
    } catch (Throwable $error) {
        api_error(500, 'Internal server error.');
    }
}
