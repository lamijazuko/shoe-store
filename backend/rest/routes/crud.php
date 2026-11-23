<?php

register_crud_routes('/categories', new CategoryService());
register_crud_routes('/products', new ProductService());

$user_service = new UserService();
register_crud_routes('/users', $user_service, [$user_service, 'public_user']);

register_crud_routes('/orders', new OrderService());
register_crud_routes('/order-items', new OrderItemService());
