<?php
namespace Application\Entity;

class Purchase extends Base {
    const TABLE_NAME = 'purchases';
    protected $transaction = '';
    protected $date = null;
    protected $product = null;
    protected $quantity = 0;
    protected $salePrice = 0.0;
    protected $customerId = 0;
    protected $productId = 0;

    public function getProduct() {
        return $this->product;
    }

    public function setProduct(Product $product) {
        $this->product = $product;
    }
}