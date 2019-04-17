<?php
namespace Application\Entity;

class Customer extends Base {
    protected $purchases = [];

    public function addPurchase($purchase) {
        $this->purchases[] = $purchase;
    }

    public function getPurchases() {
        return $this->purchases;
    }
}
