<?php

declare(strict_types=1);


namespace App\Domain\VO;




use Common\Type\ValueObject;

class CartItem extends ValueObject
{
    private ProductId $productId;
    private Natural $units;

    /**
     * CartItem constructor.
     * @param ProductId $productId
     * @param Natural $units
     */
    public function __construct(ProductId $productId, Natural $units)
    {
        $this->productId = $productId;
        $this->units = $units;
    }


    public function getProductId(): ProductId
    {
        return $this->productId;
    }


    public function getUnits(): Natural
    {
        return $this->units;
    }

    /**
     * @param ValueObject|self $o
     *
     * @return bool
     */
    protected function equalValues(ValueObject $o): bool
    {
        return ($o->getProductId()->equals($this->getProductId()) && $o->getUnits()->equals($this->getUnits()));
    }
}
