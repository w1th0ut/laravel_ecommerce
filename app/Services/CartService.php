<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class CartService
{
    protected $cartKey = 'cart';

    public function getContent()
    {
        return Session::get($this->cartKey, []);
    }

    public function add($id, $name, $price, $quantity = 1, $attributes = [])
    {
        $cart = $this->getContent();
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'id' => $id,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'attributes' => $attributes,
            ];
        }
        
        Session::put($this->cartKey, $cart);
        return $cart;
    }

    public function update($id, $data)
    {
        $cart = $this->getContent();
        
        if (isset($cart[$id])) {
            $cart[$id] = array_merge($cart[$id], $data);
            Session::put($this->cartKey, $cart);
        }
        
        return $cart;
    }

    public function remove($id)
    {
        $cart = $this->getContent();
        unset($cart[$id]);
        Session::put($this->cartKey, $cart);
        return $cart;
    }

    public function clear()
    {
        Session::forget($this->cartKey);
        return [];
    }

    public function getTotal()
    {
        $cart = $this->getContent();
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }

    public function getTotalQuantity()
    {
        $cart = $this->getContent();
        $quantity = 0;
        
        foreach ($cart as $item) {
            $quantity += $item['quantity'];
        }
        
        return $quantity;
    }
} 