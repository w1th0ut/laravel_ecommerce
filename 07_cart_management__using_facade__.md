# Chapter 7: Cart Management (using Facade)

Welcome back! In the last chapter, [Authentication System](06_authentication_system_.md), we learned how to manage users, allowing them to register, log in, and stay authenticated. Now that we know *who* our user is, we can build features specific to them, like a shopping cart that remembers what they've added.

Imagine you're browsing an online store. You see a cool t-shirt, click "Add to Cart," then maybe look at some pants, and add those too. All the items you select are held in a temporary list until you decide to buy them at checkout. This temporary list is your **Shopping Cart**.

The Cart Management system handles this. It needs to:
*   Keep track of which products the user wants to buy.
*   Remember the quantity for each product.
*   Allow changing the quantity or removing items.
*   Calculate the total cost.

In our `laravel_ecommerce` project, we use a convenient way to interact with the cart logic: the **`Cart` Facade**.

Think of the Cart Management logic as a complex machine hidden away. You don't need to know *how* it works internally (how it stores data, how it calculates totals). You just need a simple button or switch to tell it what to do ("Add this product!", "Update that quantity!"). The **`Cart` Facade** is like that simple button panel. It provides a clean, easy-to-use interface (like `Cart::add(...)` or `Cart::getTotal()`) that hides the underlying complexity.

In simpler terms, the Cart Management system (accessed via the `Cart` Facade) answers the question: **"How do we temporarily store and manage the products a user wants to buy before they check out?"**

## Using the `Cart` Facade in the `CartController`

The main place where cart actions happen is the `CartController`. This controller handles the requests related to adding, updating, removing, and viewing items in the cart, using the `Cart` Facade to perform the actual operations.

Let's look at how the `CartController` uses the `Cart` Facade methods.

### 1. Adding a Product to the Cart

When a user clicks an "Add to Cart" button (often a form submitting a POST request to a route like `/cart/add/{product}`), the `CartController@add` method is triggered.

```php
// File: app/Http/Controllers/CartController.php (snippet)

use App\Models\Product;
use Illuminate\Http\Request;
use Cart; // Import the Cart Facade!

class CartController extends Controller
{
    // Handles POST /cart/add/{product}
    public function add(Request $request, Product $product) // Uses Route Model Binding (Chapter 2/4)
    {
        // Validate the quantity input
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock_quantity // Don't add more than available stock
        ]);

        // Use the Cart Facade to add the item
        Cart::add(
            $product->id,            // Unique ID of the item
            $product->name,          // Name of the item
            $product->current_price, // Price per item (using the accessor from Chapter 5)
            $request->quantity,      // Quantity from the form
            [ /* attributes like image, slug */ ] // Optional: store extra product details
        );

        // Redirect back to the previous page with a success message
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    // ... other methods ...
}
```

The key line is `Cart::add(...)`. This static method call on the `Cart` Facade is how we tell the cart system to add an item. We just provide the necessary details (ID, name, price, quantity) and the Facade handles the rest.

### 2. Updating Item Quantity

If a user changes the quantity of an item on the cart page (often a form submitting a PATCH request to `/cart/update/{id}`), the `CartController@update` method handles it.

```php
// File: app/Http/Controllers/CartController.php (snippet)

use Illuminate\Http\Request;
use Cart; // Import the Cart Facade!

class CartController extends Controller
{
    // ... add method ...

    // Handles PATCH /cart/update/{id}
    public function update(Request $request, $id) // $id is the product ID from the route
    {
        // Validate the quantity input
        $request->validate([
            'quantity' => 'required|integer|min:1' // Ensure quantity is at least 1
            // (Note: A more robust check would verify against product stock here)
        ]);

        // Use the Cart Facade to update the item quantity
        Cart::update($id, [
            'quantity' => $request->quantity // Pass the new quantity
        ]);

        // Redirect back to the cart page
        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    // ... other methods ...
}
```

Again, the `Cart::update(...)` method is the simple interface provided by the Facade. We give it the item's unique ID and an array of data to update (in this case, just the new `quantity`).

### 3. Removing an Item

To remove an item from the cart (often a form submitting a DELETE request to `/cart/remove/{id}`), the `CartController@remove` method is used.

```php
// File: app/Http/Controllers/CartController.php (snippet)

use Cart; // Import the Cart Facade!

class CartController extends Controller
{
    // ... add, update methods ...

    // Handles DELETE /cart/remove/{id}
    public function remove($id) // $id is the product ID from the route
    {
        // Use the Cart Facade to remove the item
        Cart::remove($id);

        // Redirect back to the cart page
        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    // ... other methods ...
}
```

`Cart::remove($id)` is the simple Facade call to delete an item based on its ID.

### 4. Clearing the Cart

To remove all items from the cart (often a form submitting a DELETE request to `/cart/clear`), the `CartController@clear` method is used.

```php
// File: app/Http/Controllers/CartController.php (snippet)

use Cart; // Import the Cart Facade!

class CartController extends Controller
{
    // ... add, update, remove methods ...

    // Handles DELETE /cart/clear
    public function clear()
    {
        // Use the Cart Facade to clear the entire cart
        Cart::clear();

        // Redirect back to the cart page
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }

    // ... other methods ...
}
```

`Cart::clear()` empties the cart with a single, easy-to-remember call.

### 5. Viewing the Cart Contents

To display the items and total on the cart page (`/cart`, a GET request), the `CartController@index` method fetches the data *from* the cart system using the Facade.

```php
// File: app/Http/Controllers/CartController.php (snippet)

use Cart; // Import the Cart Facade!

class CartController extends Controller
{
    // Handles GET /cart
    public function index()
    {
        // Get all items from the cart
        $cartItems = Cart::getContent();

        // Get the total value of items in the cart
        $cartTotal = Cart::getTotal();

        // Get the total quantity of items (sum of quantities)
        $cartCount = Cart::getTotalQuantity();

        // Pass the cart data to the view
        return view('cart.index', compact('cartItems', 'cartTotal', 'cartCount'));
    }

    // ... other methods ...
}
```

Here, we use `Cart::getContent()`, `Cart::getTotal()`, and `Cart::getTotalQuantity()` to retrieve information from the cart. These methods return the current state of the cart, which the controller then passes to the `cart.index` [Blade View](03_blade_views_.md) to be displayed.

## Displaying Cart Data in a View (`cart/index.blade.php`)

The `cart/index.blade.php` [View](03_blade_views_.md) receives the `$cartItems`, `$cartTotal`, and `$cartCount` variables from the `CartController@index` method. It uses standard Blade syntax (Chapter 3) to display this information.

```html
{{-- File: resources/views/cart/index.blade.php (snippet) --}}

@extends('layouts.app') {{-- Uses the main layout (Chapter 3) --}}

@section('title', 'Shopping Cart') {{-- Sets the page title --}}

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Shopping Cart</h2>

    @if($cartItems->count() > 0) {{-- Check if there are items (Eloquent Collection method) --}}
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        {{-- Loop through each item in the $cartItems collection --}}
                        @foreach($cartItems as $item)
                            <div class="cart-item">
                                {{-- Display item details like name, quantity, price, subtotal --}}
                                <p>{{ $item->name }}</p>
                                <p>Quantity: {{ $item->quantity }}</p>
                                <p>Price: ${{ number_format($item->price, 2) }}</p>
                                <p>Subtotal: ${{ number_format($item->price * $item->quantity, 2) }}</p>

                                {{-- Forms to update or remove item (using PATCH/DELETE methods) --}}
                                <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    {{-- ... quantity input and update button ... --}}
                                </form>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    {{-- ... remove button ... --}}
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">Order Summary</div>
                    <div class="card-body">
                        {{-- Display the total count and total price --}}
                        <p>Items ({{ $cartCount }}): ${{ number_format($cartTotal, 2) }}</p>
                        {{-- ... Checkout button etc. ... --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- ... Clear Cart button form ... --}}
    @else
        {{-- Content to show if the cart is empty --}}
        <div class="text-center py-5">
            <h4>Your cart is empty</h4>
            {{-- ... link to products page ... --}}
        </div>
    @endif
</div>
@endsection
```

The view directly uses the variables `$cartItems`, `$cartTotal`, and `$cartCount` provided by the Controller. It doesn't interact with the `Cart` Facade itself for displaying this data; the Facade was used by the Controller to *get* the data, and the view just displays it.

You might also notice the `Cart::getTotalQuantity()` call directly in the `resources/views/layouts/app.blade.php` [View](03_blade_views_.md) inside the navigation bar. This shows that Facades can sometimes be conveniently used directly in views for simple tasks like displaying a count, although complex logic is usually better kept in Controllers.

```html
{{-- File: resources/views/layouts/app.blade.php (snippet) --}}

<li class="nav-item">
    <a class="nav-link" href="{{ route('cart.index') }}">
        Cart
        <span class="badge bg-light text-dark">
            {{ Cart::getTotalQuantity() }} {{-- Using the Facade directly in the view --}}
        </span>
    </a>
</li>

```

This small snippet in the main layout ensures the cart item count is always visible in the navigation bar.

## What is a Facade?

Let's formalize the concept of a **Facade** in Laravel. A Facade provides a static interface (`Cart::method(...)`) to classes that are managed by Laravel's **Service Container**.

*   **Service Container:** Think of this as Laravel's central registry or manager for all the complex "services" or objects your application might need (like the mailer service, the cache service, the database connector, or our Cart Service). When you need one of these services, you ask the Container for it.
*   **Facade:** A Facade is a class (like `App\Facades\Cart`) that extends `Illuminate\Support\Facades\Facade`. It has a special static method (`getFacadeAccessor()`) that tells Laravel which key in the Service Container (`'cart'` in our case) corresponds to the actual object it represents (our `CartService`).

When you call `Cart::add(...)`, here's what really happens behind the scenes (simplified):

1.  You call the static method `add` on the `Cart` Facade class.
2.  Laravel's Facade system intercepts this static call.
3.  It looks at the `Cart` Facade class and calls its `getFacadeAccessor()` method, which returns the string `'cart'`.
4.  Laravel goes to its Service Container and finds the object registered under the key `'cart'`. This object is an instance of our `App\Services\CartService`.
5.  Laravel then calls the `add` method on that *instance* of `CartService`, passing along the arguments you provided (`$product->id`, etc.).
6.  The `CartService` object executes its logic (adding the item to the session, etc.).
7.  The result is returned back through the Facade to your code.

So, the Facade isn't the thing *doing* the work; it's just providing a clean, static *shortcut* to the object that *does* the work. This is powerful because you get the ease of static syntax (`Cart::add(...)`) without the downsides of truly static classes (which are harder to test and manage dependencies for).

## How it Works Under the Hood (Simplified Facade Call)

Let's visualize the Facade call flow:

```mermaid
sequenceDiagram
    participant Controller
    participant CartFacade(Cart::add)
    participant LaravelFacadeSystem
    participant ServiceContainer
    participant CartService

    Controller->>CartFacade(Cart::add): Call static method add(...)
    CartFacade(Cart::add)->>LaravelFacadeSystem: Intercept static call
    LaravelFacadeSystem->>LaravelFacadeSystem: Get accessor ('cart') from Cart Facade class
    LaravelFacadeSystem->>ServiceContainer: Ask for service 'cart'
    ServiceContainer-->>LaravelFacadeSystem: Return CartService object instance
    LaravelFacadeSystem->>CartService: Call method add(...) on the instance
    CartService-->>LaravelFacadeSystem: Return result
    LaravelFacadeSystem-->>Controller: Return result (via the Facade call)
```

The `Cart` Facade is just the starting point; the real logic happens in the `CartService` class retrieved from the Service Container.

## Looking at the Code Files

Let's peek at the files that make this work.

The actual logic for managing the cart (adding, removing, calculating totals) lives in the `App\Services\CartService` class:

```php
// File: app/Services/CartService.php (simplified snippet)

namespace App\Services;

use Illuminate\Support\Facades\Session; // We're using Session to store the cart

class CartService
{
    protected $cartKey = 'cart'; // Key for session storage

    public function getContent()
    {
        // Get cart data from the session
        return Session::get($this->cartKey, []);
    }

    public function add($id, $name, $price, $quantity = 1, $attributes = [])
    {
        $cart = $this->getContent(); // Get current cart

        if (isset($cart[$id])) {
            // If item exists, increase quantity
            $cart[$id]['quantity'] += $quantity;
        } else {
            // If new item, add it
            $cart[$id] = [
                'id' => $id,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'attributes' => $attributes,
            ];
        }

        Session::put($this->cartKey, $cart); // Save updated cart back to session
        return $cart;
    }

    // ... update, remove, clear, getTotal, getTotalQuantity methods ...
    // These methods contain the actual logic, also using Session::put/get/forget
}
```

As you can see, the `CartService` class contains the real implementation using Laravel's `Session` Facade (which works just like our `Cart` Facade, providing static access to the session manager).

The `Cart` Facade itself is defined in `app/Facades/Cart.php`. This file is very small:

```php
// File: app/Facades/Cart.php

namespace App\Facades;

use Illuminate\Support\Facades\Facade; // Extend the base Facade class

class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        // This must return the key used in the Service Container
        // to register the actual CartService instance.
        return 'cart';
    }
}
```

This file just exists to tell Laravel: "When someone uses the `Cart` Facade, look for the service registered under the name `'cart'` in the Service Container."

Finally, this `'cart'` key is linked to the `CartService` class in a **Service Provider**. In our project, this setup is typically handled by the `joelwmale/laravel-cart` package itself, which registers its `CartService` implementation into Laravel's Service Container under the name `'cart'`, making it available via our custom `Cart` Facade. You can see this configuration often in `config/app.php` or within the package's own Service Provider file, where something like this happens:

```php
// Inside a ServiceProvider's register method (simplified concept)

$this->app->singleton('cart', function ($app) {
    // Register CartService as a singleton (only one instance created)
    return new \App\Services\CartService(); // Or the package's service class
});
```

This registration step is what connects the `'cart'` key to the `CartService` class, allowing the `Cart` Facade to work its magic.

## Conclusion

Cart Management is a crucial part of any e-commerce site. In our project, we interact with the cart logic using the `Cart` Facade. You've learned that a Facade provides a simple, static interface to an underlying class (our `CartService`) managed by Laravel's Service Container. This allows us to perform complex operations like adding, updating, and removing items from the cart using easy-to-read static calls like `Cart::add(...)`. The actual logic lives separately in the `CartService` class, often storing data in the user's session.

This pattern of using Facades to access services is common throughout Laravel, making your code clean and readable while keeping the complex implementation details hidden away.

Now that we have cart management in place, we need to look at another layer of control: restricting access to certain parts of the application, specifically the administrative sections. This is handled using Middleware, which we'll explore next!

[Next Chapter: Admin Middleware](08_admin_middleware_.md)

---
