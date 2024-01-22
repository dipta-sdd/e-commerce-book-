<?php




$request = $_SERVER['REQUEST_URI'];
$requests = explode('?', $request); // Split the URL at the query string

switch ($requests[0]) {
    case '/':
    case '':
        require __DIR__ . '/home.php';
        break;
    
    case '/genre':
        // Handle requests that contain 'genre' followed by a query parameter
        require __DIR__ . '/genre.php';
        break;
    case '/dashboard':    require __DIR__ . '/dashboard.php';   break;
    case '/profile':    require __DIR__ . '/profile.php';   break;
    case '/edit_profile':    require __DIR__ . '/edit_profile.php';   break;
    case '/my_items':    require __DIR__ . '/my_items.php';   break;
    case '/add_item':    require __DIR__ . '/add_item.php';   break;
    case '/stock':    require __DIR__ . '/stock.php';   break;
    case '/my_item':    require __DIR__ . '/stock.php';   break;
    case '/cart':    require __DIR__ . '/cart.php';   break;
    case '/my_order':    require __DIR__ . '/my_order.php';   break;
    case '/ongoing_order':    require __DIR__ . '/ongoing_order.php';   break;
    case '/complete_order':    require __DIR__ . '/complete_order.php';   break;
    case '/change_password':    require __DIR__ . '/change_password.php';   break;
    case '/pending_order':    require __DIR__ . '/pending_order.php';   break;
    case '/processing_order':    require __DIR__ . '/processing_order.php';   break;
    case '/shipped_order':    require __DIR__ . '/shipped_order.php';   break;
    case '/delivered_order':    require __DIR__ . '/delivered_order.php';   break;
    case '/signup':    require __DIR__ . '/signup.html';   break;
    case '/login':    require __DIR__ . '/login.html';   break;
    case '/reset_password':    require __DIR__ . '/reset_password.html';   break;
    case '/user':    require __DIR__ . '/user.php';   break;
    case '/withdraw':    require __DIR__ . '/withdraw.php';   break;
    case '/edit_book':    require __DIR__ . '/edit_book.php';   break;
    case '/users':    require __DIR__ . '/users.php';   break;
    case '/book':
        if (isset($requests[1])) {
            $bookQuery = $requests[1];
            require __DIR__ . '/book.php';
        } else {
            http_response_code(404);
            require __DIR__ . '/views/404.php';
        }
        break;
    case '/order':
        if (isset($requests[1])) {
            $bookQuery = $requests[1];
            require __DIR__ . '/order.php';
        } else {
            http_response_code(404);
            require __DIR__ . '/views/404.php';
        }
        break;
    // Other cases...

    default:
        http_response_code(404);
        require __DIR__ . '/404.php';
        break;
}





?>