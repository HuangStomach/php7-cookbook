<?php
namespace Application\Entity;

class Product extends Base {
    const TABLE_NAME = 'products';
    protected $sku = '';
    protected $title = ' ';
    protected $description = ' ';
    protected $price = 0.0;
    protected $special = 0;
    protected $link = ' ';

    
}